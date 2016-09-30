<?php

namespace Mesd\FormTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;

use \Exception;

/**
 * Address list validator
 *
 * Validate an address list following the RFC 2822 - Internet Message Format
 * standards.
 *
 * @package    Mesd\FormTypeBundle\Validator\Constraints
 * @author     Richard Heyes <richard@phpguru.org>
 * @author     Chuck Hagenbuch <chuck@horde.org>
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @copyright  2001 - 2010 Richard Heyes
 * @license    <http://opensource.org/licenses/bsd-license.php> New BSD License
 * @version    0.1.1
 * @see        http://www.faqs.org/rfcs/rfc2822.html [RFC 2822 - Internet Message Format]
 */
class AddressListValidator extends ConstraintValidator
{

    /**
     * The address being parsed by the RFC822 object.
     * @var string $address
     */
    private $address = '';

    /**
     * The default domain to use for unqualified addresses.
     * @var string $defaultDomain
     */
    private $defaultDomain = 'localhost';

    /**
     * Should we return a nested array showing groups, or flatten everything?
     * @var boolean $nestGroups
     */
    private $nestGroups = true;

    /**
     * Whether or not to validate atoms for non-ascii characters.
     * @var boolean $validate
     */
    private $validate = true;

    /**
     * The array of raw addresses built up as we parse.
     * @var array $addresses
     */
    private $addresses = array();

    /**
     * The final array of parsed address information that we build up.
     * @var array $structure
     */
    private $structure = array();

    /**
     * The current error message, if any.
     * @var string $error
     */
    private $error = null;

    /**
     * An internal counter/pointer.
     * @var integer $index
     */
    private $index = null;

    /**
     * The number of groups that have been found in the address list.
     * @var integer $numGroups
     * @access public
     */
    private $numGroups = 0;

    /**
    * A limit after which processing stops
    * @var int $limit
    */
    private $limit = null;

    private $errors = array();

    /**
     * Validate an address list value
     *
     * Required ConstraintValidator method.
     * Used to validate an address list value.
     * 
     * @param  string     $value      An address list
     * @param  Constraint $constraint A constraint object
     */
    /**
     * Starts the whole process. The address must either be set here
     * or when creating the object. One or the other.
     *
     * @access public
     * @param string  $address         The address(es) to validate.
     * @param string  $defaultDomain  Default domain/host etc.
     * @param boolean $nest_groups     Whether to return the structure with groups nested for easier viewing.
     * @param boolean $validate        Whether to validate atoms. Turn this off if you need to run addresses through before encoding the personal names, for instance.
     *
     * @return array A structured array of addresses.
     */
    public function validate($address, Constraint $constraint)
    {
        // constraint must be UsPostalCode
        if (!$constraint instanceof AddressList)
        {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AddressList');
        }

        // only validate non-empty values
        if (null === $address || '' === $address) {
            return;
        }

        // value is a scalar value or an object
        // with a __toString method returning a scalar value
        if (!is_scalar($address) && !(is_object($address) && method_exists($address, '__toString')))
        {
            throw new UnexpectedTypeException($address, 'string');
        }

        // typehint
        $address = (string) $address;

        // Unfold any long lines in $this->address.
        $this->address = preg_replace('/\r?\n/', "\r\n", $address);
        $this->address = preg_replace('/\r\n(\t| )+/', ' ', $this->address);
        
        while ($this->address = $this->splitAddresses($this->address));
        
        if ($this->address === false || isset($this->error))
        {
            $this->addViolation($constraint->message, $this->address);
        }

        // Validate each address individually.  If we encounter an invalid
        // address, stop iterating and return an error immediately.
        foreach ($this->addresses as $address)
        {
            $valid = $this->validateAddress($address);
            
            if ($valid === false || isset($this->error))
            {
                $this->addViolation($constraint->addressMessage, $address['address']);
            }

            if (!$this->nestGroups)
            {
                $this->structure = array_merge($this->structure, $valid);
            }
            else
            {
                $this->structure[] = $valid;
            }
        }

        return $this->structure;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function addViolation($message, $value)
    {
        $this->context->addViolation(
            $message,
            array('%string%' => $value)
        );
    }

    private function validateSymfonyAddress($address)
    {
        $validator = new EmailValidator();
        $validator->validate($address, new Email());
    }

    /**
     * Splits an address into separate addresses.
     *
     * @param string $address The addresses to split.
     * @return boolean Success or failure.
     */
    public function splitAddresses($address)
    {
        if (!empty($this->limit) && count($this->addresses) == $this->limit)
        {
            return '';
        }

        if ($this->isGroup($address) && !isset($this->error))
        {
            $splitChar = ';';
            $isGroup   = true;
        }
        elseif (!isset($this->error))
        {
            $splitChar = ',';
            $isGroup   = false;
        }
        elseif (isset($this->error))
        {
            return false;
        }

        // Split the string based on the above ten or so lines.
        $parts  = explode($splitChar, $address);
        $string = $this->splitCheck($parts, $splitChar);

        // If a group...
        if ($isGroup)
        {
            // If $string does not contain a colon outside of
            // brackets/quotes etc then something's fubar.

            // First check there's a colon at all:
            if (strpos($string, ':') === false)
            {
                $this->error = 'Invalid address: ' . $string;
                return false;
            }

            // Now check it's outside of brackets/quotes:
            if (!$this->splitCheck(explode(':', $string), ':'))
            {
                return false;
            }

            // We must have a group at this point, so increase the counter:
            $this->numGroups++;
        }

        // $string now contains the first full address/group.
        // Add to the addresses array.
        $this->addresses[] = array(
            'address' => trim($string),
            'group'   => $isGroup
        );

        // Remove the now stored address from the initial line, the +1
        // is to account for the explode character.
        $address = trim(substr($address, strlen($string) + 1));

        // If the next char is a comma and this was a group, then
        // there are more addresses, otherwise, if there are any more
        // chars, then there is another address.
        if ($isGroup && substr($address, 0, 1) == ',')
        {
            $address = trim(substr($address, 1));
            
            return $address;

        }
        elseif (strlen($address) > 0)
        {
            return $address;

        }
        else
        {
            return '';
        }

        // If you got here then something's off
        return false;
    }

    /**
     * Checks for a group at the start of the string.
     *
     * @param string $address The address to check.
     * @return boolean Whether or not there is a group at the start of the string.
     */
    public function isGroup($address)
    {
        // First comma not in quotes, angles or escaped:
        $parts  = explode(',', $address);
        $string = $this->splitCheck($parts, ',');

        // Now we have the first address, we can reliably check for a
        // group by searching for a colon that's not escaped or in
        // quotes or angle brackets.
        if (count($parts = explode(':', $string)) > 1)
        {
            $string2 = $this->splitCheck($parts, ':');
            return ($string2 !== $string);
        }
        else
        {
            return false;
        }
    }

    /**
     * A common function that will check an exploded string.
     *
     * @param array $parts The exloded string.
     * @param string $char  The char that was exploded on.
     * @return mixed False if the string contains unclosed quotes/brackets, or the string on success.
     */
    public function splitCheck($parts, $char)
    {
        $string = $parts[0];

        for ($i = 0; $i < count($parts); $i++)
        {
            if (
                    $this->hasUnclosedQuotes($string)
                 || $this->hasUnclosedBrackets($string, '<>')
                 || $this->hasUnclosedBrackets($string, '[]')
                 || $this->hasUnclosedBrackets($string, '()')
                 || substr($string, -1) == '\\'
                )
            {
                if (isset($parts[$i + 1]))
                {
                    $string = $string . $char . $parts[$i + 1];
                }
                else
                {
                    $this->error = 'Invalid address spec. Unclosed bracket or quotes';

                    return false;
                }
            }
            else
            {
                $this->index = $i;

                break;
            }
        }

        return $string;
    }

    /**
     * Checks if a string has unclosed quotes or not.
     *
     * @param string $string  The string to check.
     * @return boolean  True if there are unclosed quotes inside the string,
     *                  false otherwise.
     */
    public function hasUnclosedQuotes($string)
    {
        $string  = trim($string);
        $iMax    = strlen($string);
        $inQuote = false;
        $i       = $slashes = 0;

        for (; $i < $iMax; ++$i)
        {
            switch ($string[$i])
            {
            case '\\':
                ++$slashes;
                break;
            case '"':
                if (0 == $slashes % 2)
                {
                    $inQuote = !$inQuote;
                }
                // Fall through to default action below.
            default:
                $slashes = 0;

                break;
            }
        }

        return $inQuote;
    }

    /**
     * Checks if a string has an unclosed brackets or not. IMPORTANT:
     * This function handles both angle brackets and square brackets;
     *
     * @param string $string The string to check.
     * @param string $chars  The characters to check for.
     * @return boolean True if there are unclosed brackets inside the string, false otherwise.
     */
    public function hasUnclosedBrackets($string, $chars)
    {
        $numAngleStart = substr_count($string, $chars[0]);
        $numAngleEnd   = substr_count($string, $chars[1]);

        $this->hasUnclosedBracketsSub($string, $numAngleStart, $chars[0]);
        $this->hasUnclosedBracketsSub($string, $numAngleEnd, $chars[1]);

        if ($numAngleStart < $numAngleEnd)
        {
            $this->error = 'Invalid address spec. Unmatched quote or bracket (' . $chars . ')';
        
            return false;
        }
        else
        {
            return ($numAngleStart > $numAngleEnd);
        }
    }

    /**
     * Sub function that is used only by hasUnclosedBrackets().
     *
     * @param string $string The string to check.
     * @param integer &$num    The number of occurences.
     * @param string $char   The character to count.
     * @return integer The number of occurences of $char in $string, adjusted for backslashes.
     */
    public function hasUnclosedBracketsSub($string, &$num, $char)
    {
        $parts = explode($char, $string);

        for ($i = 0; $i < count($parts); $i++)
        {
            if (substr($parts[$i], -1) == '\\' || $this->hasUnclosedQuotes($parts[$i]))
            {
                $num--;
            }

            if (isset($parts[$i + 1]))
            {
                $parts[$i + 1] = $parts[$i] . $char . $parts[$i + 1];
            }
        }

        return $num;
    }

    /**
     * Function to begin checking the address.
     *
     * @param string $address The address to validate.
     * @return mixed False on failure, or a structured array of address information on success.
     */
    public function validateAddress($address)
    {
        $isGroup   = false;
        $addresses = array();

        if ($address['group'])
        {
            $isGroup = true;

            // Get the group part of the name
            $parts     = explode(':', $address['address']);
            $groupname = $this->splitCheck($parts, ':');
            $structure = array();

            // And validate the group part of the name.
            if (!$this->validatePhrase($groupname))
            {
                $this->error = 'Group name did not validate.';
                return false;
            }
            else
            {
                // Don't include groups if we are not nesting
                // them. This avoids returning invalid addresses.
                if ($this->nestGroups)
                {
                    $structure = new \stdClass;
                    $structure->groupname = $groupname;
                }
            }

            $address['address'] = ltrim(substr($address['address'], strlen($groupname . ':')));
        }

        // If a group then split on comma and put into an array.
        // Otherwise, Just put the whole address in an array.
        if ($isGroup)
        {
            while (strlen($address['address']) > 0)
            {
                $parts              = explode(',', $address['address']);
                $addresses[]        = $this->splitCheck($parts, ',');
                $address['address'] = trim(substr($address['address'], strlen(end($addresses) . ',')));
            }
        }
        else
        {
            $addresses[] = $address['address'];
        }

        // Trim the whitespace from all of the address strings.
        array_map('trim', $addresses);

        // Validate each mailbox.
        // Format could be one of: name <geezer@domain.com>
        //                         geezer@domain.com
        //                         geezer
        // ... or any other format valid by RFC 822.
        for ($i = 0; $i < count($addresses); $i++)
        {
            if (!$this->validateMailbox($addresses[$i]))
            {
                if (empty($this->error))
                {
                    $this->error = 'Validation failed for: ' . $addresses[$i];
                }

                return false;
            }
        }

        // Nested format
        if ($this->nestGroups)
        {
            if ($isGroup) {
                $structure->addresses = $addresses;
            }
            else
            {
                $structure = $addresses[0];
            }

        // Flat format
        }
        else
        {
            if ($isGroup)
            {
                $structure = array_merge($structure, $addresses);
            }
            else
            {
                $structure = $addresses;
            }
        }

        return $structure;
    }

    /**
     * Function to validate a phrase.
     *
     * @param  string  $phrase The phrase to check.
     * @return boolean         Success or failure.
     */
    public function validatePhrase($phrase)
    {
        // Splits on one or more Tab or space.
        $parts       = preg_split('/[ \\x09]+/', $phrase, -1, PREG_SPLIT_NO_EMPTY);
        $phraseParts = array();
        
        while (count($parts) > 0)
        {
            $phraseParts[] = $this->splitCheck($parts, ' ');
            
            for ($i = 0; $i < $this->index + 1; $i++)
            {
                array_shift($parts);
            }
        }

        foreach ($phraseParts as $part)
        {
            // If quoted string:
            if (substr($part, 0, 1) == '"')
            {
                if (!$this->validateQuotedString($part))
                {
                    return false;
                }
                
                continue;
            }

            // Otherwise it's an atom:
            if (!$this->validateAtom($part))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Function to validate an atom which from rfc822 is:
     * atom = 1*<any CHAR except specials, SPACE and CTLs>
     *
     * If validation ($this->validate) has been turned off, then
     * validateAtom() doesn't actually check anything. This is so that you
     * can split a list of addresses up before encoding personal names
     * (umlauts, etc.), for example.
     *
     * @param string $atom The string to check.
     * @return boolean Success or failure.
     */
    public function validateAtom($atom)
    {
        if (!$this->validate)
        {
            // Validation has been turned off; assume the atom is okay.
            return true;
        }

        // Check for any char from ASCII 0 - ASCII 127
        if (!preg_match('/^[\\x00-\\x7E]+$/i', $atom, $matches))
        {
            return false;
        }

        // Check for specials:
        if (preg_match('/[][()<>@,;\\:". ]/', $atom))
        {
            return false;
        }

        // Check for control characters (ASCII 0-31):
        if (preg_match('/[\\x00-\\x1F]+/', $atom))
        {
            return false;
        }

        return true;
    }

    /**
     * Function to validate quoted string, which is:
     * quoted-string = <"> *(qtext/quoted-pair) <">
     *
     * @param string $qstring The string to check
     * @return boolean Success or failure.
     */
    public function validateQuotedString($qstring)
    {
        // Leading and trailing "
        $qstring = substr($qstring, 1, -1);

        // Perform check, removing quoted characters first.
        return !preg_match('/[\x0D\\\\"]/', preg_replace('/\\\\./', '', $qstring));
    }

    /**
     * Function to validate a mailbox, which is:
     * mailbox =   addr-spec         ; simple address
     *           / phrase route-addr ; name and route-addr
     *
     * @param string &$mailbox The string to check.
     * @return boolean Success or failure.
     */
    public function validateMailbox(&$mailbox)
    {
        // A couple of defaults.
        $phrase   = '';
        $comment  = '';
        $comments = array();

        // Catch any RFC822 comments and store them separately.
        $mailbox = $mailbox;

        while (strlen(trim($mailbox)) > 0)
        {
            $parts          = explode('(', $mailbox);
            $beforeComment = $this->splitCheck($parts, '(');
            
            if ($beforeComment != $mailbox)
            {
                // First char should be a (.
                $comment    = substr(str_replace($beforeComment, '', $mailbox), 1);
                $parts      = explode(')', $comment);
                $comment    = $this->splitCheck($parts, ')');
                $comments[] = $comment;

                // +2 is for the brackets
                $mailbox = substr($mailbox, strpos($mailbox, '('.$comment)+strlen($comment)+2);
            }
            else
            {
                break;
            }
        }

        foreach ($comments as $comment) {
            $mailbox = str_replace("($comment)", '', $mailbox);
        }

        $mailbox = trim($mailbox);

        // Check for name + route-addr
        if (substr($mailbox, -1) == '>' && substr($mailbox, 0, 1) != '<')
        {
            $parts     = explode('<', $mailbox);
            $name      = $this->splitCheck($parts, '<');
            $phrase    = trim($name);
            $routeAddr = trim(substr($mailbox, strlen($name.'<'), -1));

            if ($this->validatePhrase($phrase) === false || ($routeAddr = $this->validateRouteAddr($routeAddr)) === false)
            {
                return false;
            }

        // Only got addr-spec
        }
        else
        {
            // First snip angle brackets if present.
            if (substr($mailbox, 0, 1) == '<' && substr($mailbox, -1) == '>')
            {
                $addrSpec = substr($mailbox, 1, -1);
            }
            else
            {
                $addrSpec = $mailbox;
            }

            if (($addrSpec = $this->validateAddrSpec($addrSpec)) === false)
            {
                return false;
            }
        }

        // Construct the object that will be returned.
        $mbox = new \stdClass();

        // Add the phrase (even if empty) and comments
        $mbox->personal = $phrase;
        $mbox->comment  = isset($comments) ? $comments : array();

        if (isset($routeAddr))
        {
            $mbox->mailbox = $routeAddr['localPart'];
            $mbox->host    = $routeAddr['domain'];
            
            $routeAddr['adl'] !== '' ? $mbox->adl = $routeAddr['adl'] : '';
        }
        else
        {
            $mbox->mailbox = $addrSpec['localPart'];
            $mbox->host    = $addrSpec['domain'];
        }

        $mailbox = $mbox;

        return true;
    }

    /**
     * This function validates a route-addr which is:
     * route-addr = "<" [route] addr-spec ">"
     *
     * Angle brackets have already been removed at the point of
     * getting to this function.
     *
     * @param string $routeAddr The string to check.
     * @return mixed False on failure, or an array containing validated address/route information on success.
     */
    public function validateRouteAddr($routeAddr)
    {
        // Check for colon.
        if (strpos($routeAddr, ':') !== false)
        {
            $parts = explode(':', $routeAddr);
            $route = $this->splitCheck($parts, ':');
        }
        else
        {
            $route = $routeAddr;
        }

        // If $route is same as $routeAddr then the colon was in
        // quotes or brackets or, of course, non existent.
        if ($route === $routeAddr)
        {
            unset($route);
            $addrSpec = $routeAddr;
            
            if (($addrSpec = $this->validateAddrSpec($addrSpec)) === false)
            {
                return false;
            }
        }
        else
        {
            // Validate route part.
            if (($route = $this->validateRoute($route)) === false)
            {
                return false;
            }

            $addrSpec = substr($routeAddr, strlen($route . ':'));

            // Validate addr-spec part.
            if (($addrSpec = $this->validateAddrSpec($addrSpec)) === false)
            {
                return false;
            }
        }

        if (isset($route))
        {
            $return['adl'] = $route;
        }
        else
        {
            $return['adl'] = '';
        }

        return array_merge($return, $addrSpec);
    }

    /**
     * Function to validate a route, which is:
     * route = 1#("@" domain) ":"
     *
     * @param string $route The string to check.
     * @return mixed False on failure, or the validated $route on success.
     */
    public function validateRoute($route)
    {
        // Split on comma.
        $domains = explode(',', trim($route));

        foreach ($domains as $domain)
        {
            $domain = str_replace('@', '', trim($domain));
            
            if (!$this->validateDomain($domain))
            {
                return false;
            }
        }

        return $route;
    }

    /**
     * Function to validate a domain, though this is not quite what
     * you expect of a strict internet domain.
     *
     * domain = sub-domain *("." sub-domain)
     *
     * @param string $domain The string to check.
     * @return mixed False on failure, or the validated domain on success.
     */
    public function validateDomain($domain)
    {
        // Note the different use of $subdomains and $subDomains
        $subdomains = explode('.', $domain);

        while (count($subdomains) > 0)
        {
            $subDomains[] = $this->splitCheck($subdomains, '.');
            
            for ($i = 0; $i < $this->index + 1; $i++)
            {
                array_shift($subdomains);
            }
        }

        foreach ($subDomains as $subDomain)
        {
            if (!$this->validateSubdomain(trim($subDomain)))
            {
                return false;
            }
        }

        // Managed to get here, so return input.
        return $domain;
    }

    /**
     * Function to validate a subdomain:
     *   subdomain = domain-ref / domain-literal
     *
     * @param string $subdomain The string to check.
     * @return boolean Success or failure.
     */
    public function validateSubdomain($subdomain)
    {
        if (preg_match('|^\[(.*)]$|', $subdomain, $arr))
        {
            if (!$this->validateDliteral($arr[1]))
            {
                return false;
            }
        }
        else
        {
            if (!$this->validateAtom($subdomain))
            {
                return false;
            }
        }

        // Got here, so return successful.
        return true;
    }

    /**
     * Function to validate a domain literal:
     *   domain-literal =  "[" *(dtext / quoted-pair) "]"
     *
     * @param string $dliteral The string to check.
     * @return boolean Success or failure.
     */
    public function validateDliteral($dliteral)
    {
        return !preg_match('/(.)[][\x0D\\\\]/', $dliteral, $matches) && $matches[1] != '\\';
    }

    /**
     * Function to validate an addr-spec.
     *
     * addr-spec = local-part "@" domain
     *
     * @param string $addrSpec The string to check.
     * @return mixed False on failure, or the validated addr-spec on success.
     */
    public function validateAddrSpec($addrSpec)
    {
        $addrSpec = trim($addrSpec);

        // Split on @ sign if there is one.
        if (strpos($addrSpec, '@') !== false)
        {
            $parts     = explode('@', $addrSpec);
            $localPart = $this->splitCheck($parts, '@');
            $domain    = substr($addrSpec, strlen($localPart . '@'));

        // No @ sign so assume the default domain.
        }
        else
        {
            $localPart = $addrSpec;
            $domain    = $this->defaultDomain;
        }

        if (($localPart = $this->validateLocalPart($localPart)) === false)
        {
            return false;
        }

        if (($domain    = $this->validateDomain($domain)) === false)
        {
            return false;
        }

        // Got here so return successful.
        return array('localPart' => $localPart, 'domain' => $domain);
    }

    /**
     * Function to validate the local part of an address:
     *   local-part = word *("." word)
     *
     * @param string $localPart
     * @return mixed False on failure, or the validated local part on success.
     */
    public function validateLocalPart($localPart)
    {
        $parts = explode('.', $localPart);
        $words = array();

        // Split the localPart into words.
        while (count($parts) > 0) {
            $words[] = $this->splitCheck($parts, '.');
            for ($i = 0; $i < $this->index + 1; $i++) {
                array_shift($parts);
            }
        }

        // Validate each word.
        foreach ($words as $word) {
            // word cannot be empty (#17317)
            if ($word === '') {
                return false;
            }
            // If this word contains an unquoted space, it is invalid. (6.2.4)
            if (strpos($word, ' ') && $word[0] !== '"')
            {
                return false;
            }

            if ($this->validatePhrase(trim($word)) === false) return false;
        }

        // Managed to get here, so return the input.
        return $localPart;
    }

    /**
     * Returns an approximate count of how many addresses are in the
     * given string. This is APPROXIMATE as it only splits based on a
     * comma which has no preceding backslash. Could be useful as
     * large amounts of addresses will end up producing *large*
     * structures when used with parseAddressList().
     *
     * @param  string $data Addresses to count
     * @return int          Approximate count
     */
    public function approximateCount($data)
    {
        return count(preg_split('/(?<!\\\\),/', $data));
    }

    /**
     * This is a email validating function separate to the rest of the
     * class. It simply validates whether an email is of the common
     * internet form: <user>@<domain>. This can be sufficient for most
     * people. Optional stricter mode can be utilised which restricts
     * mailbox characters allowed to alphanumeric, full stop, hyphen
     * and underscore.
     *
     * @param  string  $data   Address to check
     * @param  boolean $strict Optional stricter mode
     * @return mixed           False if it fails, an indexed array
     *                         username/domain if it matches
     */
    public function isValidInetAddress($data, $strict = false)
    {
        $regex = $strict ? '/^([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})$/i' : '/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})$/i';
        
        if (preg_match($regex, trim($data), $matches))
        {
            return array($matches[1], $matches[2]);
        }
        else
        {
            return false;
        }
    }


}
