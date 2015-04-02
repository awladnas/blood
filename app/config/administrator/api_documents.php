<?php

/**
 * Api document model config
 */

return array(

    'title' => 'Api Documents',
    'single' => 'Api Document',
    'model' => 'LifeLi\models\Documents\Document',
    'form_width' => 600,

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'url' => array(
            'title' => 'URL',
        ),
        'api_version' => array(
            'title' => 'Version',
        ),

        'request_method' => array(
            'title' => 'Method',
        )
    ),
    'rules' => array(
        'url' => 'required',
        'output_format' => 'required',
        'api_version' => 'required',
        'request_method' => 'required'
    ),
    'edit_fields' => array(
        'url' => array(
            'title' => 'URL',
        ),
        'input_format' => array(
            'title' => 'Input',
            'type' => 'textarea',
            'height' => 300
        ),
        'output_format' => array(
            'title' => 'Output',
            'type' => 'textarea',
            'height' => 500,
        ),
        'api_version' => array(
            'title' => 'Version',
        ),

        'request_method' => array(
            'title' => 'Method',
        ),
        'Description' => array(
            'title' => 'Description',
            'type' => 'textarea',
            'height' => 300
        )
    ),
    'filters' => array(
        'url' => array(
            'title' => 'URL',
            'type'  => 'text'
        ),
        'api_version' => array(
            'title' => 'Version',
            'type'  => 'text'
        ),

        'request_method' => array(
            'title' => 'Method',
            'type'  => 'text'
        ),
    )
);