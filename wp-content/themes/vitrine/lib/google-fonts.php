<?php

class epico_GoogleFonts {

    protected $jsonObject = null;

    public function __construct($jsonFile)
    {
        $content = include $jsonFile;

        if(FALSE === $content)
        {
            //Prevent access errors
            $jsonObject = new stdClass();
            $jsonObject->items = array();
            return;
        }

        $this->jsonObject = $content;
    }

    public function GetJson()
    {
        return $this->jsonObject;
    }

    public function GetFontNames()
    {
        $names = array();

        foreach($this->jsonObject as $font => $extra)
        {

            $variants_array = array();
            $font_array = array();

            if(isset($extra['variants']))
            {
                foreach ($extra['variants'] as $key) {
                    $variants_array[] = $key['id'];
                }
            }
            
            $font_array[$font] = $variants_array;

            $key = json_encode($font_array);
            $names[$key] = $font;
        }

        return $names;
    }

    public function GetFontByName($name)
    {
        foreach($this->jsonObject as $font)
        {
            if($font == $name)
                return $font;
        }

        return null;
    }
}