<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/24/2015
 * Time: 2:26 PM
 */

namespace AronKerr\Checkr\InputFilter;


use Zend\InputFilter\InputFilter;

class CreateReportInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'package',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => array(
                            'tasker_standard',
                            'tasker_pro',
                            'driver_standard',
                            'driver_pro'
                        )
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'candidate_id',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));
    }
}