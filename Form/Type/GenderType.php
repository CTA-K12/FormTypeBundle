<?php
// src/App/FormTypeBundle/Form/Type/GenderType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GenderType extends AbstractType
{
    public function getDefaultOptions(array $options)
    {
        return array(
            'choices' => array(
                'M' => 'Male',
                'F' => 'Female',
                'T' => 'Transgender',
                'N' => 'Gender Neutral',
            )
        );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'gender';
    }
}
