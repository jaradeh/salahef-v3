<?php

//Base class for generating html code from
//given template file
class epico_Template
{
    protected $templatesDir  = 'templates';

    function __construct($templatesDir = '')
    {
        if($templatesDir != '')
            $this->templatesDir = $templatesDir;
    }

    function epico_SetWorkingDirectory($dir)
    {
        $this->templatesDir = $dir;
    }

    function  epico_GetTemplate($file, $vars = array())
    {
        ob_start();
        require( epico_path_combine($this->templatesDir, $file) . '.php');
        return ob_get_clean();
    }

}