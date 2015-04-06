<?php

/**
 * Setting model config
 */

return array(

    'title' => 'Pages',
    'single' => 'Page',
    'model' => 'LifeLi\models\Pages\Page',
    'form_width' => 600,

    'columns' => array(
        'id' => array(
            'title' => 'Id',
        ),
        'about' => array(
            'title' => 'About',
        )
    ),
    'rules' => array(
        'about' => 'required'
    ),
    'edit_fields' => array(
        'about' => array(
            'title' => 'About',
            'type' => 'wysiwyg',
        )
    ),
    'filters' => array(
        'created_at' => array(
            'title' => 'Method',
            'type'  => 'date'
        ),
    ),
    'action_permissions'=> array(
        'delete' => false,
        'new' => false,
        'create' => false,
    ),
);