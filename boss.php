<?php

/**
 * 
 * --------------------
 * DESCRIPTION
 * --------------------
 *
 * [$CURRENT_DIR] ->    a global variable $GLOBALS["CURRENT_DIR"] to store the 
 *                      current directory of the script. Then, the script includes several PHP files using the require 
 *                      function, such as 
 *                      Cli.php, CreateApplication.php, and Server.php, which contain various classes and functions 
 *                      required for different tasks.
 * 
 * Next, the script defines several functions and an array variable $webServers. 
 * print_help() -> function displays a help message that describes the available command-line arguments. 
 * The get_arguments() -> function parses the command-line arguments and returns an array of key-value pairs. 
 * $webServers -> array is used to store information about web servers that the user may want to start.
 * 
 * After defining these elements, the script checks the command-line arguments using a for loop. 
 * If the argument is -h, the script calls the print_help() function to display the help message. 
 * If the argument is -cli, the script creates an instance of the CLI class and runs it. 
 * If the argument is -s, the script creates a web server instance with the arguments provided by the user and starts it using the Start() method of the WebServer class. 
 * If the argument is -mongo, the script does nothing as this feature is not yet implemented. 
 * If the argument is -config, the script reads a JSON file specified by the user, parses it, and starts a web server using the configuration data provided by the user. 
 * Finally, if the argument is -create, the script creates a new application with the name specified by the user using the CreateApplication class.
 * 
 * 
 * -------------------------
 * EXAMPLES
 * -------------------------
 * 
 * -h: Prints all the available command-line arguments and exits the program. Example: php my_program.php -h
 * -cli: Opens a command line interface (CLI) for the user to interact with. Example: php my_program.php -cli
 * -s: Starts a web server with the given arguments. Arguments should be provided as a comma-separated list of key-value pairs, where each key and value are separated by an equal sign. Example: php my_program.php -s port=8000,logpath=/var/log/myapp.log
 * -mongo: Starts a MongoDB server with the given arguments. Arguments should be provided in the same format as for -s. Example: php my_program.php -mongo port=27017,logpath=/var/log/mongodb.log
 * -config: Starts one or more servers based on a configuration file. The path to the configuration file should be provided as an additional argument after -config. Example: php my_program.php -config /path/to/config.json
 * -create: Creates a basic application with the given name. The name should be provided as an additional argument after -create. Example: php my_program.php -create myapp
 */


// Set the current directory to the directory containing this file
$CURRENT_DIR = $GLOBALS["CURRENT_DIR"] = dirname(__FILE__);

// Load required files for the API
require("API/Cli.php");
require("API/CreateApplication.php");
require("API/Server.php");

// Note: Instead of manually requiring each file, the code could use a loop
// to automatically include all PHP files in the "API" directory. The code for
// doing so is commented out below.

// Load all PHP files in the "API" directory
// foreach (glob("$CURRENT_DIR/API/*.php") as $filename) {
//     require($filename);
// }

// Print the command-line arguments and exit if -h is provided
function print_help()
{
    echo "-h | Print all the arguments\n";
    echo "-cli | open command line interface to know more check documentation\n";
    echo "-c | specify the config file path to know more check documentation\n";
    echo "-s | to start mongo server 'port=8800,logpath=log.log'\n";
    echo "-m | to start mongo server 'port=8800,logpath=log.log'\n";
    echo "-config | to start a server or number of servers using configaration files\n";
    echo "-create | to create a basic application to start with -create App_Name";
    echo " To Know more check the Documentation";
    exit(0);
}

// Parse the arguments provided by the user
function get_arguments($args)
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

// Print help and exit if no arguments were provided
if ($argc < 1) {
    echo "-h | Print all the arguments\n";
    exit(0);
}

// Loop through each argument provided by the user
for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] == "-h") {
        // Print help and exit
        if (function_exists(print_help())) {
            print_help();
        }
    } elseif ($argv[$i] == "-cli") {
        // Start the CLI
        $cli = new CLI();
        $cli->Run();
    } elseif ($argv[$i] == "-s") {
        // Start a web server with the provided arguments
        $arguments = get_arguments($argv[++$i]);
        (new WebServer($arguments))->Start();
    } elseif ($argv[$i] == "-mongo") {
        // Start a MongoDB server with the provided arguments
        // This feature is not yet implemented
    } elseif ($argv[$i] == "-config") {
        // Start one or more servers based on a configuration file
        $config_file = $argv[++$i];
        $json = file_get_contents($config_file);
        // Decode the JSON file
        $server_data = json_decode($json, true);
        if (array_key_exists('WebServer', $server_data)) {
            (new WebServer($server_data["WebServer"]))->Start_With_Config();
        }
        // Other features yet to be created
        // For example, database configuration
    } elseif ($argv[$i] == "-create") {
        // create a basic application to start with
        // example usage: php my_script.php -create MyAppName

        // Check if an app name is provided
        if ($argv[++$i] == null) {
            printf("Please Provide the Application Name");
            exit(0);
        }
        // Get the application name from the argument
        $app_name = $argv[$i];

        // Call the CreateApplication class to create a new application with the given name
        // The CreateApplication class is responsible for creating the directory structure for the 
        // application with some default files such as an index.php
        new CreateApplication($app_name);
    }

}