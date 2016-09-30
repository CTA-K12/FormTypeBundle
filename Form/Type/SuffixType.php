<?php
// src/App/FormTypeBundle/Form/Type/SuffixType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuffixType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'choices' => array(
                'II'   => 'II',
                'III'  => 'III',
                'IV'   => 'IV',
                'Jr'   => 'Jr',
                'Sr'   => 'Sr',
                'Ret'  => 'Ret',
                'Ph.D' => 'Ph.D',
                'RN'   => 'RN',
                'MD'   => 'MD',
                'JD'   => 'JD',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_form_type_suffix';
    }
}
