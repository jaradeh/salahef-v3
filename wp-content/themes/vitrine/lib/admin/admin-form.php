<?php

require_once(EPICO_THEME_LIB . '/forms/fieldtemplate.php');
require_once(EPICO_THEME_LIB . '/forms/theme-options-provider.php');

class epico_AdminForm extends epico_FieldTemplate
{
    protected $template = array();

    function __construct()
    {
        $this->template = epico_admin_get_form_settings();
        parent::__construct(new epico_ThemeOptionsProvider(), EPICO_THEME_LIB . '/forms/templates');
    }

    public function GetHeader()
    {
        return $this-> epico_GetTemplate('admin-header');
    }

    public function GetBody()
    {
        return $this-> epico_GetTemplate('admin-sidebar') .
               $this-> epico_GetTemplate('admin-panels');
    }

    public function epico_GetImage($filename, $alt='', $class='')
    {
        return $this-> epico_GetTemplate('image', array('filename'=>$filename, 'alt'=>$alt, 'class'=>$class));
    }
}