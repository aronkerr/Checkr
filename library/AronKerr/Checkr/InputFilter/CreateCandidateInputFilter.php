<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/23/2015
 * Time: 11:01 PM
 */

namespace AronKerr\Checkr\InputFilter;


use Zend\InputFilter\InputFilter;

class CreateCandidateInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'first_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'middle_name',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'last_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array('name' => 'EmailAddress')
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Digits')
            ),
            'validators' => array(
                array('name' => 'digits')
            )
        ));

        $this->add(array(
            'name' => 'zipcode',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Digits')
            ),
            'validators' => array(
                array('name' => 'digits'),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 5,
                        'max' => 10
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'dob',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'ssn',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'regex',
                    'options' => array(
                        'pattern' => '/^\d{3}-?\d{2}-?\d{4}$/'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'driver_license_number',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'driver_license_state',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper')
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 2,
                        'max' => 2
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'previous_driver_license_number',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'previous_driver_license_state',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper')
            ),
            'validators' => array(
                array('name' => 'digits'),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 2,
                        'max' => 2
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'copy_requested',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Boolean')
            )
        ));

        $this->add(array(
            'name' => 'custom_id',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'geo_ids',
            'required' => false
        ));
    }
}
