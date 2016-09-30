<?php
// src/App/FormTypeBundle/Form/Type/PrefixType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrefixType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'choices' => array(
                'Mr'    => 'Mr',
                'Mrs'   => 'Mrs',
                'Ms'    => 'Ms',
                'Miss'  => 'Miss',
                'Mx'    => 'Mx',
                'Rev'   => 'Rev',
                'Fr'    => 'Fr',
                'Dr'    => 'Dr',
                'Atty'  => 'Atty',
                'Prof'  => 'Prof',
                'Hon'   => 'Hon',
                'Pres'  => 'Pres',
                'Gov'   => 'Gov',
                'Coach' => 'Coach',
                'Ofc'   => 'Ofc',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_form_type_prefix';
    }
}
