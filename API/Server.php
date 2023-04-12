<?php

class Server
{
    protected $HostName = "localhost";
    protected $PortNumber = "8000";
    protected $ProcessId = "";
    protected $LogPath = "";
    

    public function Stop()
    {
        // echo "kill $this->ProcessId";
        if ($this->ProcessId) {
            exec(sprintf("kill %s > %s 2", $this->ProcessId, "/dev/null"));
            echo "Development Server is Stopped [http://$this->HostName:$this->PortNumber]\n" ;
        } else {
            echo "No Development Server is Running on this [http://$this->HostName:$this->PortNumber] Location\n";
        }
    }
}
class WebServer extends Server
{
    private $DocRoot = "./";
    private $Index = "index.php";
    /**
     * @param mixed $__arguments - array of parameters to create the server
     * example @("host" => "localhost", "port" => "8000")
     */
    public function __construct($__arguments = array("host" => "localhost", "port" => "8000"))
    {
        $curpath = exec('pwd');
        if (array_key_exists("host", $__arguments)) {
            $this->HostName = $__arguments["host"];
        }
        if (array_key_exists("port", $__arguments)) {
            $this->PortNumber = $__arguments["port"];
        }
        if (array_key_exists("logfolder", $__arguments)) {
            $logfolder = $__arguments["logfolder"];
            $this->LogPath = "$logfolder/Webserver.log";
        } else {
            $this->LogPath = "$curpath/Log/Webserver.log";
        }
        if (array_key_exists("docroot", $__arguments)){
            $this->DocRoot = $__arguments['docroot'];
        }
        if (array_key_exists("index", $__arguments)){
            $this->Index = $__arguments['index'];
        }

    }
    public function Start()
    {
        $this->ProcessId = trim(shell_exec(sprintf("php -S $this->HostName:$this->PortNumber $this->Index -t $this->DocRoot > %s 2>&1 & echo $!", $this->LogPath)));
        echo "Development Server is Started [http://$this->HostName:$this->PortNumber] : $this->ProcessId\n";
    }
    public function Start_With_Config()
    {
        // shell_exec(sprintf("echo 'Server Root is $this->DocRoot' > %s 2>&1", $this->LogPath));
        $this->ProcessId = trim(shell_exec(sprintf("php -S $this->HostName:$this->PortNumber $this->Index -t $this->DocRoot > %s 2>&1", $this->LogPath)));
        shell_exec(sprintf("Development Server is Started [http://$this->HostName:$this->PortNumber] : $this->ProcessId > %s 2>&1", $this->LogPath));
        
    }

}
?>