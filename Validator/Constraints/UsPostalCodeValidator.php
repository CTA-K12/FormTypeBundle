<?php

namespace Mesd\FormTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UsPostalCodeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        // constraint must be UsPostalCode
        if (!$constraint instanceof UsPostalCode)
        {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UsPostalCode');
        }

        // only validate non-empty values
        if (null === $value || '' === $value) {
            return;
        }

        // value is a scalar value or an object
        // with a __toString method returning a scalar value
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString')))
        {
            throw new UnexpectedTypeException($value, 'string');
        }

        // typehint
        $value = (string) $value;

        // default type to 'zip' if not set
        if (null == $constraint->type)
        {
            $constraint->type = 'zip';
        }

        // validate zip 5
        if ('zip5' === $constraint->type)
        {
            if (!preg_match($constraint::ZIP_5, $value))
            {
                $this->context->addViolation($this->getMessage($constraint, $constraint->type), array(
                    '{{ value }}' => $this->formatValue($value),
                ));
            }

            return;
        }

        // validate zip 4
        if ('zip4' === $constraint->type)
        {
            if (!preg_match($constraint::ZIP_4, $value))
            {
                $this->context->addViolation($this->getMessage($constraint, $constraint->type), array(
                    '{{ value }}' => $this->formatValue($value),
                ));
            }

            return;
        }

        // now just try to validate against the whole zip regex
        if (!preg_match($constraint::ZIP, $value))
        {
            $this->context->addViolation($this->getMessage($constraint, $constraint->type), array(
                    '{{ value }}' => $this->formatValue($value),
                ));
        }
    }

    protected function getMessage($constraint, $type = null)
    {
        if (null !== $constraint->message)
        {
            return $constraint->message;
        }
        elseif ('zip5' === $type)
        {
            return $constraint->zip5Message;
        }
        elseif ('zip4' === $type)
        {
            return $constraint->zip4Message;
        }

        return $constraint->zipMessage;
    }
}
