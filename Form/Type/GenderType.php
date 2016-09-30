<?php
// src/App/FormTypeBundle/Form/Type/GenderType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenderType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'choices' => array(
                'M' => 'Male',
                'F' => 'Female',
                'T' => 'Transgender',
                'N' => 'Gender Neutral',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_form_type_gender';
    }
}
