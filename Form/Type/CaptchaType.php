<?php
/**
 * CaptchaType.php file
 *
 * File that contains the ck editor form type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/FormTypeBundle/Form/Type/CaptchaType.php
 * @package    Mesd\FormTypeBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CKEditor Form Type Class
 *
 * The ckeditor form type class. Declares and configures the form type
 *
 * @package    Mesd\FormTypeBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 */
class CaptchaType extends AbstractType
{
    /**
     * Set Default Options
     *
     * Set the default options
     *
     * @since  0.1.0
     * @param  OptionsResolverInterface $resolver
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array());
    }

    /**
     * Get Parent
     *
     * Return the formtype tag name
     *
     * @since  0.2.0
     * @return string The string 'text'
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Get Name
     *
     * Return the name of the form type
     *
     * @since  0.2.0
     * @return string The string 'mesd_form_type_captcha'
     */
    public function getName()
    {
        return 'mesd_form_type_captcha';
    }

}
