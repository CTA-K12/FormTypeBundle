<?php
// src/App/FormTypeBundle/Form/Type/StateType.php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StateType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'choices' => array(
                'AL' => 'Alabama',  
                'AK' => 'Alaska',  
                'AZ' => 'Arizona',  
                'AR' => 'Arkansas',  
                'CA' => 'California',  
                'CO' => 'Colorado',  
                'CT' => 'Connecticut',  
                'DE' => 'Delaware',  
                'DC' => 'District Of Columbia',  
                'FL' => 'Florida',  
                'GA' => 'Georgia',  
                'HI' => 'Hawaii',  
                'ID' => 'Idaho',  
                'IL' => 'Illinois',  
                'IN' => 'Indiana',  
                'IA' => 'Iowa',  
                'KS' => 'Kansas',  
                'KY' => 'Kentucky',  
                'LA' => 'Louisiana',  
                'ME' => 'Maine',  
                'MD' => 'Maryland',  
                'MA' => 'Massachusetts',  
                'MI' => 'Michigan',  
                'MN' => 'Minnesota',  
                'MS' => 'Mississippi',  
                'MO' => 'Missouri',  
                'MT' => 'Montana',
                'NE' => 'Nebraska',
                'NV' => 'Nevada',
                'NH' => 'New Hampshire',
                'NJ' => 'New Jersey',
                'NM' => 'New Mexico',
                'NY' => 'New York',
                'NC' => 'North Carolina',
                'ND' => 'North Dakota',
                'OH' => 'Ohio',  
                'OK' => 'Oklahoma',  
                'OR' => 'Oregon',  
                'PA' => 'Pennsylvania',  
                'RI' => 'Rhode Island',  
                'SC' => 'South Carolina',  
                'SD' => 'South Dakota',
                'TN' => 'Tennessee',  
                'TX' => 'Texas',  
                'UT' => 'Utah',  
                'VT' => 'Vermont',  
                'VA' => 'Virginia',  
                'WA' => 'Washington',  
                'WV' => 'West Virginia',  
                'WI' => 'Wisconsin',  
                'WY' => 'Wyoming',
                'AS' => 'American Samoa',
                'FM' => 'Federated States of Micronesia',
                'GU' => 'Guam',
                'MH' => 'Marshall Islands',
                'MP' => 'Northern Mariana Islands',
                'PW' => 'Palau',
                'PR' => 'Puerto Rico',
                'VI' => 'Virgin Islands',
                'AA' => 'Armed Forces Americas (except Canada)',
                'AE' => 'Armed Forces Canada/Europe/Africa/Middle East',
                'AP' => 'Armed Forces Pacific',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'mesd_form_type_state';
    }
}