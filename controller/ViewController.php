<?php

class ViewController
{
    private $htmlVars;

    public function __construct(){
        $this->htmlVars = new stdClass();
    }

    public function minifyCss($dir = null,array $files){
        $directory = $dir ?? "view/css/";
        $buffer = $this->getDirFilesBuffer($directory,$files);
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        echo($buffer);
    }

    public function minifyJs(string $dir = null,array $files) {
        $directory = $dir ?? "view/css/";
        $input = $this->getDirFilesBuffer($directory,$files);
        global $SS, $CC;
        $input = preg_split('#(' . $SS . '|' . $CC . '|\/[^\n]+?\/(?=[.,;]|[gimuy]|$))#', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $output = "";
        foreach($input as $v) {
            if(trim($v) === "") continue;
            if(
                ($v[0] === '"' && substr($v, -1) === '"') ||
                ($v[0] === "'" && substr($v, -1) === "'") ||
                ($v[0] === '/' && substr($v, -1) === '/')
            ) {
                // Remove if not detected as important comment ...
                if(strpos($v, '//') === 0 || (strpos($v, '/*') === 0 && strpos($v, '/*!') !== 0 && strpos($v, '/*@cc_on') !== 0)) continue;
                $output .= $v; // String, comment or regex ...
            } else {
                $inp = preg_replace(
                    array('#\s*\/\/.*$#m','#\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#','#[;,]([\]\}])#','#\btrue\b#', '#\bfalse\b#', '#\breturn\s+#'),
                    array("",'$1','$1','!0', '!1', 'return '),$v);

                $output .= $inp;
            }
        }
        //header("Content-type:text/javascript");
        echo preg_replace(
            array('#(' . $CC . ')|([\{,])([\'])(\d+|[a-z_]\w*)\3(?=:)#i','#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i'),
            array('$1$2$4','$1.$3'),$output);
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
            $error = "View : '" . "view/$viewDir/$viewName.phtml" . "' not found !";
            include_once "view/$viewDir/404.phtml";
        }
        include_once "view/$viewDir/footer.phtml";
    }

    private function getDirFilesBuffer($directory,$files = null)
    {
        if(empty($files))
            $files = array_diff(scandir($directory), array('..', '.'));
        $buffer = "";
        foreach ($files as $cssFile) {
            $buffer .= file_get_contents($directory . $cssFile);
        }
        return $buffer;
    }

}