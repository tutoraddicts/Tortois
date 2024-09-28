# Tortois

## Table of Contents- [Tortois](#tortois)
- [Tortois](#tortois)
  - [Table of Contents- Tortois](#table-of-contents--tortois)
  - [Pre-requisite](#pre-requisite)
  - [Command Line Arguments](#command-line-arguments)
    - [`-create=<application_name>`](#-createapplication_name)
    - [`-updateDB`](#-updatedb)
    - [`-CreateController=<controller_name>`](#-createcontrollercontroller_name)
    - [`-RemoveController=<controller_name>`](#-removecontrollercontroller_name)
    - [`-StartServer=<port>`](#-startserverport)


## Pre-requisite

    You need two softwares to be installed in your system
        1. PHP
        2. GIT
        3. Golang only to build it

## Command Line Arguments

The script checks the command-line arguments using a for loop and performs actions based on the arguments provided.

### `-create=<application_name>`

- **Description:** Creates a new application with the specified name.
- **Usage:** `-create=myapp`
- **Example:**
  ```bash
  .\tortois.exe -create=myapp
  ```

### `-updateDB`

- **Description:** Updates the database for the current application.
- **Usage:** `-updateDB`
- **Example:**
  ```bash
  .\tortois.exe updateDB
  ```


### `-CreateController=<controller_name>`

- **Description:** Creates a new controller with the specified name.
- **Usage:** `-CreateController=newcontroller`
- **Example:**
  ```bash
  .\tortois.exe -CreateController=newcontroller
  ```

### `-RemoveController=<controller_name>`

- **Description:** Removes the specified controller.
- **Usage:** `-RemoveController=oldcontroller`
- **Example:**
- ```bash
  .\tortois.exe -RemoveController=oldcontroller
  ```

### `-CreateTable=<table_name>`

- **Description:** Creates a new table (model) with the specified name.
- **Usage:** `-CreateTable=newtable`
- **Example:**
- ```bash  
  .\tortois.exe -CreateTable=newtable
  ```

### `-StartServer=<port>`

- **Description:** Starts the server on the specified port.
- **Usage:** `-StartServer=8080`
- **Example:**  
- ```bash
  .\tortois.exe -StartServer=8080
  ```