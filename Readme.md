# Tortois

## Table of Contents- [Tortois](#tortois)
- [Tortois](#tortois)
  - [Table of Contents- Tortois](#table-of-contents--tortois)
  - [Pre-requisite](#pre-requisite)
  - [Command Line Arguments](#command-line-arguments)
  - [Command Line Arguments](#command-line-arguments-1)
    - [Available Arguments](#available-arguments)


## Pre-requisite

    You need two softwares to be installed in your system
        1. PHP
        2. GIT

-h      | Print all the arguments
-cli    | open command line interface to know more check documentation
-c      | specify the config file path to know more check documentation
-s      | to start mongo server 'port=8800,logpath=log.log'
-mongo  | to start mongo server 'port=8800,logpath=log.log'

## Command Line Arguments

The script checks the command-line arguments using a for loop and performs actions based on the arguments provided.

## Command Line Arguments

The script checks the command-line arguments using a for loop and performs actions based on the arguments provided.

### Available Arguments

- **-h**: Prints all the available command-line arguments and exits the program.
  - Example: `php tortois.php -h`
- **-cli**: Opens a command line interface (CLI) for the user to interact with.
  - Example: `php tortois.php -cli`
- **-s**: Starts a web server with the given arguments. Arguments should be provided as a comma-separated list of key-value pairs, where each key and value are separated by an equal sign.
  - Example: `php tortois.php -s port=8000,logpath=/var/log/myapp.log`
- **-mongo**: Starts a MongoDB server with the given arguments. Arguments should be provided in the same format as for `-s`.
  - Example: `php tortois.php -mongo port=27017,logpath=/var/log/mongodb.log`
- **-config**: Starts one or more servers based on a configuration file. The path to the configuration file should be provided as an additional argument after `-config`.
  - Example: `php tortois.php -config /path/to/config.json`
- **-create**: Creates a basic application with the given name. The name should be provided as an additional argument after `-create`.
  - Example: `php tortois.php -create myapp`
