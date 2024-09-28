package main

import (
	"embed"
	"fmt"
	"io/fs"
	"os"
	"strings"
)

// This file will hold the function and details of the Application which needs to be created

//go:embed templates/*
var ApplicationFolder embed.FS

type Application struct {
	ApplicationName string

	content        []fs.DirEntry
	embededContent embed.FS
	Folders        map[string]string
}

func NewApplication(_ApplicationName string) *Application {

	if _ApplicationName == "" {
		JsonContent, _ := os.ReadFile("Server.Config.json")
		var JsonObject = GetFromJson(JsonContent)

		if JsonObject == nil {
			fmt.Printf("Please use the the application inside the application folder to let it work\n")
			return nil
		}

		_ApplicationName, _ = (*JsonObject)["applicationName"].(string)
	} else {
		// When I am going to create the new application then we will create a Application Folder if not present then change the directory to it if not done
		if !CreateDirectory(&_ApplicationName) {
			return nil
		}
		os.Chdir(_ApplicationName)
	}

	_content, _ := ApplicationFolder.ReadDir("templates")
	return &Application{
		ApplicationName: _ApplicationName,
		content:         _content,
		embededContent:  ApplicationFolder,

		Folders: map[string]string{
			"root":        "./",
			"Controllers": "./Controllers/",
			"Models":      "./Models/",
			"Static":      "./Static/",
			"js":          "./Static/js/",
			"view":        "./Static/view/",
			"css":         "./Static/css/",
			"utils":       "./util/",
		},
	}
}

func (a *Application) TransferContent(src string, dest string, StrReplaces []string) {
	// StrReplaces - ["string_to_replace=string_to_add"]
	fmt.Printf("Creating - %s\n", dest)
	ByteData, _ := a.embededContent.ReadFile(src) // Getting the Controller Template Data
	StringData := string(ByteData)                // Converting the data to string and replacing controller name
	if len(StrReplaces) > 0 {
		for _, data := range StrReplaces {
			ReplaceData := strings.Split(data, "=")
			StringData = strings.ReplaceAll(StringData, ReplaceData[0], ReplaceData[1])
		}
	}
	WriteContent(&dest, &StringData)
}

func (a *Application) CreateController(_ControllerName string) {
	// Creating Controller data which include
	// 1. Controller Class
	// 2. View For Controller
	// 3. Css for Controller
	// 4. js For Controller

	// Creating Controller Class

	ControllerFileName := a.Folders["Controllers"] + _ControllerName + "Controller.php" // Creating the Controller File Name
	a.TransferContent("templates/Controller.template", ControllerFileName, []string{
		"{{ControllerName}}=" + _ControllerName,
	})

	// Creating View
	ViewFileName := a.Folders["view"] + _ControllerName + ".html"
	a.TransferContent("templates/view.template", ViewFileName, []string{
		"{{ControllerName}}=" + _ControllerName,
	})

	// Creating js
	JsFileName := a.Folders["js"] + _ControllerName + ".js"
	a.TransferContent("templates/js.template", JsFileName, []string{
		"{{ControllerName}}=" + _ControllerName,
	})

	// Creating css
	CssFileName := a.Folders["css"] + _ControllerName + ".css"
	a.TransferContent("templates/css.template", CssFileName, []string{
		"{{ControllerName}}=" + _ControllerName,
	})
}

func (a *Application) RemoveController(_ControllerName string) {
	// Remove Controller Class
	fmt.Printf("Removing Controller Class - %s", _ControllerName)
	os.Remove("Controller/" + a.Folders["Controllers"] + _ControllerName + "Controller.php")

	// Removing Controller View
	fmt.Printf("Removing Controller View - %s", _ControllerName)
	os.Remove(a.Folders["view"] + _ControllerName + ".html")

	// Removing Controller js
	fmt.Printf("Removing Controller js - %s", _ControllerName)
	os.Remove(a.Folders["js"] + _ControllerName + ".html")

	// Removing Controller css
	fmt.Printf("Removing Controller css - %s", _ControllerName)
	os.Remove(a.Folders["css"] + _ControllerName + ".html")

}

func (a *Application) CreateModel(ModelTableName string) {
	/*
	 * Creating Table Structure for the Model which will be used to comply with database
	 */
	ModelTableFileName := a.Folders["Models"] + ModelTableName + ".table.php" // Creating the Controller File Name
	a.TransferContent("templates/model.table.template", ModelTableFileName, []string{
		"{{ModelTableName}}=" + ModelTableName,
	})
}

func (a *Application) TransferUtilities() {
	/*
	 * Transfering all the utilities files data
	 */

	fmt.Printf("Generating all the utilities Files \n")
	for _, File := range a.content {
		SplittedName := strings.Split(File.Name(), ".")

		if SplittedName[0] == "util" { // means we got only utilities files which we are going to transfer
			a.TransferContent("templates/"+File.Name(), a.Folders["utils"]+SplittedName[1]+".php", []string{})
		}
	}
}

func (a *Application) TransferHtaccess() {
	a.TransferContent("templates/.htaccess.template", "./.htaccess", []string{})
}

func (a *Application) CreateAppSetupFile() {
	// It will get the app_setup.template and create it in the application folder
	a.TransferContent("templates/app_setup.template", "./app_setup.php", []string{})
}

func (a *Application) DeleteAppSetupFile() {
	//  Delete the App_setup php file
	os.Remove("app_setup.php")
}

func (a *Application) RunAppSetup(argument string) {
	//  Run the App_setup.php file with arguments
	RunCommand("php", "./app_setup.php", argument)
}

func (a *Application) CreateServerConfig() {
	a.TransferContent("templates/config.template", "./Server.Config.json", []string{
		"{{DataBase}}=" + a.ApplicationName,
		"{{ApplicationName}}=" + a.ApplicationName,
	})
}

func (a *Application) CreateFolders() {
	for _, Folder := range a.Folders {
		CreateDirectory(&Folder)
	}
}

func (a *Application) CreateApplication() {
	a.CreateFolders()

	a.CreateController("home")
	a.CreateModel("User")
	a.TransferUtilities()
	a.TransferHtaccess()
	a.CreateServerConfig()
	a.CreateAppSetupFile()

	WaitForUserInput("Update the Model as per your needs and Update the Server.Config.json and Press Enter to Continue...")
	a.RunAppSetup("-init_db")
	a.DeleteAppSetupFile()
}

func (a *Application) UpdateDatabase() {
	// Updating the tables of the database
	a.CreateAppSetupFile()
	a.RunAppSetup("-update_tables")
	a.DeleteAppSetupFile()
}

func (a *Application) StartServer(port string) {
	// Starting a Web Server
	a.CreateAppSetupFile()
	RunCommand("php", "./app_setup.php", "-start", port)
	a.DeleteAppSetupFile()
}

func (a *Application) Build() {
	a.CreateAppSetupFile()
	a.RunAppSetup("-build")
	a.DeleteAppSetupFile()
}
