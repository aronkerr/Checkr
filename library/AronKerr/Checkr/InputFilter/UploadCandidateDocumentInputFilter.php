<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/24/2015
 * Time: 2:26 PM
 */

namespace AronKerr\Checkr\InputFilter;


use Zend\InputFilter\InputFilter;

class UploadCandidateDocumentInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'type',
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
                            'driver_license',
                            'state_id_card',
                            'passport',
                            'ssn_card'
                        )
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'file',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'MimeType',
                    'options' => array(
                        'mimeType' => array(
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/bmp',
                            'image/tiff',
                            'application/pdf'
                        )
                    )
                )
            )
        ));
    }
}