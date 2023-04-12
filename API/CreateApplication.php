<?php
/**
 * This file is to creat the simple application structure
 */

class GenerateCode
{
    protected $CURRENT_DIR;
    protected $Sample_Config;
    private $Demo = "Demo";

    // reference files
    private $main_php_demo = "main.php.demo";
    private $renderView = "RenderView.php.demo";
    private $Controler_php_demo = "Controler.php.demo";
    private $Index_html_demo = "index.html.demo";

    // Code of the main file
    protected function Write_Main_Code($__AppName)
    {
        $main_code = sprintf(file_get_contents("$this->CURRENT_DIR/$this->Demo/$this->main_php_demo"), $__AppName,$__AppName,$__AppName);

        printf("Creating indexed file [$__AppName.php]\n");
        shell_exec("touch ./$__AppName.php");
        shell_exec("echo '$main_code' > $__AppName.php");


        // creating RenderView 
        $renderView = file_get_contents("$this->CURRENT_DIR/$this->Demo/$this->renderView");
        printf("Creating RenderView file [RenderView.php]\n");
        shell_exec("touch ./RenderView.php");
        $renderView = sprintf($renderView, $__AppName, '%s');
        file_put_contents("./RenderView.php", $renderView);
        // shell_exec(sprintf("echo '$renderView' > RenderView.php", $__AppName));
    }

    protected function Generate_Controller($__ControllerName, $__AppName)
    {
        $controller_code =sprintf(file_get_contents("$this->CURRENT_DIR/$this->Demo/$this->Controler_php_demo") ,$__ControllerName, $__ControllerName,$__ControllerName);
        printf("Creating Controller [%sController.php]\n", $__ControllerName);
        shell_exec(sprintf("touch ./$__AppName/Controller/%sController.php", $__ControllerName));
        shell_exec(sprintf("echo '$controller_code' > ./$__AppName/Controller/%sController.php", $__ControllerName));
    }

    protected function Generate_View($__ViewName, $__AppName)
    {
        $view_code =sprintf(file_get_contents("$this->CURRENT_DIR/$this->Demo/$this->Index_html_demo") ,$__ViewName, $__ViewName);
        
        printf("Creating Controller [%sView.php]\n", $__ViewName);
        shell_exec(sprintf("mkdir ./$__AppName/Views/%s", $__ViewName));
        shell_exec(sprintf("touch ./$__AppName/Views/%s/index.html", $__ViewName));
        shell_exec(sprintf("echo '$view_code' > ./$__AppName/Views/%s/index.html", $__ViewName));
    }
}
class CreateApplication extends GenerateCode
{
    protected $folders = array(
        "Models",
        "Controller",
        "Views"
    );


    /**
     * @param string $__AppName Name of the application default name is Application
     * example @("host" => "localhost", "port" => "8000")
     */
    public function __construct($__AppName = "Application")
    {
        $this->CURRENT_DIR = $GLOBALS["CURRENT_DIR"];
        $this->Sample_Config = json_decode(file_get_contents("$this->CURRENT_DIR/Demo/Server.Config.Json.demo"),true);

        var_dump($this->Sample_Config);

        // //create folders
        $this->Create_folders($__AppName);

        // //creating main file
        $this->Create_files($__AppName);

        // // Generate server config
        $this->Generate_Server_Config($__AppName);
    }

    private function Generate_Server_Config($__AppName)
    {
        
        $this->Sample_Config['Application'] = $__AppName;
        $this->Sample_Config['WebServer']["index"] = "$__AppName.php";

        $json_D = json_encode($this->Sample_Config);
        file_put_contents("./$__AppName.Server.Config.Json", $json_D);
    }

    private function Create_files($__AppName)
    {
        $this->Write_Main_Code($__AppName);
        $this->Generate_Controller("Default", $__AppName);
        $this->Generate_View("Default", $__AppName);
    }

    private function Create_folders($__AppName)
    {

        printf("Creating Main Application Directory with the name $__AppName\n");
        shell_exec("mkdir $__AppName");
        printf("Creating Directory for logs with the name of Log\n");
        shell_exec(sprintf("mkdir %s", $this->Sample_Config["WebServer"]["logfolder"]));
        // application structure
        foreach ($this->folders as $f) {
            printf("Creating Directory [./$__AppName/$f/]\n");
            shell_exec("mkdir ./$__AppName/$f/");
        }
    }
}

?>