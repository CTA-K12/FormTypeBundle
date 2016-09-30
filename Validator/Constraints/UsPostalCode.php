<?php

namespace Mesd\FormTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UsPostalCode extends Constraint
{
    const ZIP   = '/^\d{5}(?:[-\s]\d{4})?$/';
    const ZIP_5 = '/^\d{5}$/';
    const ZIP_4 = '/^\d{4}$/';

    public $zipMessage  = '{{ value }}.is.not.a.valid.zip.code';
    public $zip5Message = '{{ value }}.is.not.a.valid.zip.code';
    public $zip4Message = '{{ value }}.is.not.a.valid.zip.4.code';
    public $type;
    public $message;
}
