<?php
// src/App/FormTypeBundle/Form/Type/GenderType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsPostalCodeType extends AbstractType
{
    const ZIP   = '^\d{5}(?:[-\s]\d{4})?$';
    const ZIP_5 = '^\d{5}$';
    const ZIP_4 = '^\d{4}$';

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type'       => 'zip',
        ));

        $resolver->setAllowedValues(array(
            'type' => array(
                'zip',
                'zip5',
                'zip4',
            ),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = array(
            'pattern'    => self::getPattern($options['type']),
            'max_length' => self::getMaxLength($options['type']),
        );
    }

    protected static function getPattern($type)
    {
        // validate zip 5
        if ('zip5' === $type)
        {
            return self::ZIP_5;
        }

        // validate zip 4
        if ('zip4' === $type)
        {
            return self::ZIP_4;
        }

        return self::ZIP;
    }

    protected static function getMaxLength($type)
    {
        // validate zip 5
        if ('zip5' === $type)
        {
            return 5;
        }

        // validate zip 4
        if ('zip4' === $type)
        {
            return 4;
        }

        return 10;
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'mesd_form_type_us_postal_code';
    }
}
