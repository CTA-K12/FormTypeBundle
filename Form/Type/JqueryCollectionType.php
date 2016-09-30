<?php
/**
 * ColorPickerType.php file
 *
 * File that contains the color picker type class
 *
 * Licence MIT
 * Copyright (c) 2016 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @filesource /src/Mesd/FormTypeBundle/Form/Type/ColorPickerType.php
 * @package    Mesd\FormTypeBundle\Form\Type
 * @copyright  2016 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.2.0
 */
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Color Picker Type Class
 *
 * The ckeditor form type class. Declares and configures the form type
 *
 * @package    Mesd\FormTypeBundle\Form\Type
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.2.0
 */
class JqueryCollectionType extends AbstractType
{
    /**
     * Set Default Options
     *
     * Set the default options
     *
     * @since  0.2.0
     * @param  OptionsResolverInterface $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
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
        return CollectionType::class;
    }
}
