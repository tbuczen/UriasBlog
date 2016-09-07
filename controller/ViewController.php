<?php

class ViewController
{
    private $htmlVars;

    public function __construct()
    {
        $this->htmlVars = new stdClass();
    }

    /**
     * Assign variable to template
     * @param $var
     * @param mixed $value
     */
    public function assign($var, $value = ''){
        $this->htmlVars->$var = $value;
    }

    /**
     * Render whole prepared page @see prepare
     */
    public function renderOne($viewName,$dir = "front"){
        if ($this->htmlVars) {
            foreach ($this->htmlVars as $nmstr => $value) {
                $$nmstr = $value;
            }
        }
        include_once "view/$dir".DS."$viewName.phtml";
    }

    /**
     * Render view with along with header and footer, if ts not found - render 404
     * @param $viewName
     */
    public function renderAll($viewName,$viewDir = "front"){
        if ($this->htmlVars) {
            foreach ($this->htmlVars as $nmstr => $value) {
                $$nmstr = $value;
            }
        }
        include_once "view/$viewDir/header.phtml";
        if((@include_once "view/$viewDir/$viewName.phtml") === false)
        {
            //throw exception?
            include_once "view/$viewDir/404.phtml";
        }
        include_once "view/$viewDir/footer.phtml";
    }

}