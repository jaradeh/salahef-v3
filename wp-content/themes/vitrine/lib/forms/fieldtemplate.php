<?php
require (EPICO_THEME_LIB . '/forms/template.php');

class epico_FieldTemplate extends epico_Template {
    /* @var IValueProvider $valueProvider */
    private   $valueProvider = null;

    function __construct(IValueProvider $valueProvider, $templatesDir = '')
    {
        $this->valueProvider = $valueProvider;
        parent::__construct($templatesDir);
    }

    function epico_GetValue($key)
    {
        return $this->valueProvider->epico_GetValue($key);
    }

    public function GetField($key, array $settings, array $vars=null)
    {
        $params = array('key' => $key, 'settings' => $settings);

        if($vars != null)
            $params = array_merge($vars, $params);

        return $this-> epico_GetTemplate($settings['type'] . '-field', $params);
    }
}