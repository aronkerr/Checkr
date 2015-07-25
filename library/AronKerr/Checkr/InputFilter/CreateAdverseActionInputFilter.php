<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/23/2015
 * Time: 11:22 PM
 */

namespace AronKerr\Checkr\InputFilter;


use Zend\InputFilter\InputFilter;

class CreateAdverseActionInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'post_notice_scheduled_at',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'c'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'items',
            'required' => true
        ));
    }
}