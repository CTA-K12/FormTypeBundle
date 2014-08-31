<?php
/**
 * CKEditorType.php file
 *
 * File that contains the ck editor form type class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/FormTypeBundle/Form/Type/CKEditorType.php
 * @package    Mesd\FormTypeBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

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
class CKEditorType extends AbstractType
{
    /**
     * Get Default Options
     *
     * Get the default 
     *
     * @since  0.1.0
     * @param  string[]   $options A multidimensional array of k => v options
     */
    public function getDefaultOptions(array $options)
    {
        // this does nothing
        // i added the class to the field type
        $resolver->setDefaults(array(
          'attr' => array('class' => 'ckeditor')
        ));
    }

    /**
     * Get Parent
     *
     * Return the formtype tag name
     *
     * @since  0.1.0
     * @return string The string 'textarea'
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * Get Name
     *
     * Return the name of the form type
     *
     * @since  0.1.0
     * @return string The string 'ckeditor'
     */
    public function getName()
    {
        return 'ckeditor';
    }

}
