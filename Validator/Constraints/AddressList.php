<?php

namespace Mesd\FormTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class AddressList extends Constraint
{
    public $message = 'address.list.is.not.valid';
    public $addressMessage = '%string%.or.an.address.near.it.is.not.valid';
}
