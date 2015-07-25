<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/23/2015
 * Time: 11:22 PM
 */

namespace AronKerr\Checkr\InputFilter;


use Zend\InputFilter\InputFilter;

class FetchAllCandidatesInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'email',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array('name' => 'EmailAddress')
            )
        ));

        $this->add(array(
            'name' => 'full_name',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));

        $this->add(array(
            'name' => 'adjudication',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
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
            'name' => 'created_after',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'created_before',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'geo_id',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));
    }
}