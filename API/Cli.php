<?php

class CLI
{
    // $server['port_number'] = webserver_object;
    private $WebServers = array();
    private $MongoServers = array();
    private $HostName = "localhost";
    private $PortNumber = "8000";

    public function __construct($arrguments = array("host" => "localhost", "port" => "8000"))
    {
        echo "CLI | -h for help\n";
        $this->HostName = $arrguments["host"];
        $this->PortNumber = $arrguments["port"];
    }

    public function Run()
    {
        while (true) {
            $input = readline(">> ");
            // Replacing all the double spaces and line end
            $instructions = explode(";", preg_replace(array('/\s+/', '/[\t\n]/'), '', $input));

            foreach ($instructions as $instruction) {

                $instruction = explode("(", str_replace(")", "", $instruction));
                if ($instruction[0] == "exit") {
                    exit(0);
                }
                // getting the arguments as array 
                $args = $this->GetArguments($instruction[1]);
                if ($instruction[0] == "start") {
                    $this->StartServers($args);
                } elseif ($instruction[0] == "stop") {
                    $this->StopServers($args);
                } elseif ($instruction[0] == "config") {
                    $config_file = $args['name'];
                    $json = file_get_contents($config_file);
                    // Decode the JSON file
                    $server_data = json_decode($json, true);
                    if (array_key_exists('WebServer', $server_data)) {
                        try{
                            $this->StartServers($server_data["WebServer"]);
                        }catch (Exception $e){
                            printf("Failed to start the server check logs for more information");
                        }
                        
                    }
                }

            }
        }
    }

    /**
     * @param mixed $args - string of arguments
     * @return array - array of the arguments 'name of the element' => 'data of the element'
     */
    private function GetArguments($args)
    {
        $arrguments = array();
        if (!$args) {
            return $arrguments;
        }
        $args = explode(",", $args);
        if ($args) {
            foreach ($args as $arg) {
                $data = explode("=", $arg);
                if ($data) {
                    $arrguments["$data[0]"] = "$data[1]";
                }
            }
        }

        return $arrguments;
    }

    /**
     * @param array $args is a array containing values of for the server creation
     */
    private function StartServers($args)
    {
        $port = array_key_exists("port", $args) ? $args['port'] : $this->PortNumber;

        if (array_key_exists("server", $args)) {
            // echo "server argument found\n";
            if ($args["server"] == "web") {
                $this->WebServers["$port"] = new WebServer($args);
                $this->WebServers["$port"]->Start();

            } elseif ($args["server"] == "mongo") {
                // create mopngo server 
            }
        } else {
            // Throw an error that server type is not mentioned 
        }
    }

    private function StopServers($args)
    {
        if (array_key_exists("server", $args)) {
            if ($args["server"] == "web") {
                if (array_key_exists("port", $args)) {
                    $port = $args['port'];
                    // stop only for specific ports
                    $this->WebServers["$port"]->Stop();
                } else {
                    // stop for all the ports
                    foreach ($this->WebServers as $sName => $obj) {

                        $obj->Stop();
                    }
                }
            } elseif ($args["server"] == "mongo") {
                if (array_key_exists("port", $args)) {
                    $port = $args['port'];
                    // stop only for specific ports
                    $this->MongoServers["mongo_$port"]->Stop();
                } else {
                    // stop for all the ports
                    foreach ($this->MongoServers as $sName => $obj) {
                        $obj->Stop();
                    }
                }
            }


        } else {
            // stop all the servers that are running in background
            if (!empty($this->WebServers)) {
                foreach ($this->WebServers as $server) {
                    $server->Stop();
                }
            }
            if (!empty($this->MongoServers)) {
                foreach ($this->MongoServers as $server) {
                    $server->Stop();
                }
            }
        }

    }
}
?>