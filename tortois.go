// main.go
package main

import (
	"fmt"
	"os"
	"strings"
)

var Flags = map[string]bool{
	"CreateController": false,
	"CreateModel":      false,
	"RemoveController": false,
	"updateDB":         false,
	"StartServer":      false,
}

func CreateApplication(_ApplicationName string) {

	if _ApplicationName == "" {
		fmt.Printf("Error : Not able to create the Appplication \nPlease provide a application name to create ex: -create=myapp\n")
		return
	}
	var Application = NewApplication(_ApplicationName)
	if Application == nil {
		fmt.Printf("Application is already created and present in the current folder please Remove the old files and then create new\n This Functionality is added to secure user from data loss\n")
		return
	}
	Application.CreateApplication()
}

func main() {

	NumberOfArguments := len(os.Args)
	for i := 1; i < NumberOfArguments; i++ {
		arg := strings.Split(os.Args[i], "=")
		// fmt.Printf(arg[0])
		switch arg[0] {
		case "-create":
			Flags["create"] = true
			if len(arg) < 2 {
				fmt.Printf("Error : Not able to create the Appplication \nPlease provide a application name to create ex: -create=myapp\n")
			} else {
				CreateApplication(arg[1])
			}
			break
		case "-updateDB":
			Flags["updateDB"] = true
			var Application = NewApplication("")
			if Application == nil {
				fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
				return
			}
			Application.UpdateDatabase()
			break
		case "-CreateController":
			Flags["CreateController"] = true
			if len(arg) < 2 {
				fmt.Printf("Error : no Controller Name Provided ex: -CreateController=newcontroller\n")
				break
			} else {
				var Application = NewApplication("")
				if Application == nil {
					fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
					return
				}
				Application.CreateController(arg[1])
			}
			break
		case "-RemoveController":
			Flags["RemoveController"] = true
			if len(arg) < 2 {
				fmt.Printf("Error : no Controller Name Provided ex: -RemoveController=oldController\n")
				break
			} else {
				var Application = NewApplication("")
				if Application == nil {
					fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
					return
				}
				Application.RemoveController(arg[1])
			}
			break
		case "-CreateTable":
			Flags["CreateTable"] = true
			if len(arg) < 2 {
				fmt.Printf("Error : no Controller Name Provided ex: -CreateTable=newtable\n")
				break
			} else {
				var Application = NewApplication("")
				if Application == nil {
					fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
					return
				}
				Application.CreateModel(arg[1])
			}
			break
		case "-StartServer":
			Flags["StartServer"] = true
			if len(arg) < 2 {
				fmt.Printf("Error : No Port number Provided ex: -StartServer=serverPort\n")
				break
			} else {
				var Application = NewApplication("")
				if Application == nil {
					fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
					return
				}
				Application.StartServer(arg[1])
			}
			break
		case "-build":
			var Application = NewApplication("")
			if Application == nil {
				fmt.Printf("Error: \nSomething Went wront not able to create Application Object\n")
				return
			}

			Application.Build()
			break
		default:
			fmt.Printf("Warning : No arugment has been passed hence no operation to do")
			break
		}
	}

	// for _, flag := range Flags {
	// 	switch(flag) {
	// 		cases
	// 	}
	// }

}
