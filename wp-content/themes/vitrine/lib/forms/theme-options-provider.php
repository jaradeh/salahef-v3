<?php

require_once('ivalueprovider.php');

class epico_ThemeOptionsProvider implements IValueProvider {
    public function epico_GetValue($key)
    {
        return epico_opt($key);
    }
}