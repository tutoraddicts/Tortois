package main

import (
	"bufio"
	"embed"
	"encoding/json"
	"fmt"
	"log"
	"os"
	"os/exec"
)

func ReadFileFromEmbeded(path *string, _content *embed.FS) string {
	_byteData, _ := _content.ReadFile(*path)
	return string(_byteData)
}

func CreateDirectory(path *string) bool {
	// Check if the directory already exists
	if _, err := os.Stat(*path); !os.IsNotExist(err) {
		fmt.Printf("directory already exists: %s \n", *path)
		return false
	}

	// Create the directory
	fmt.Printf("Creating Directiory - %s \n", *path)
	err := os.MkdirAll(*path, 0755)
	if err != nil {
		fmt.Errorf("failed to create directory %s: %w \n", *path, err)
		return false
	}

	return true
}

func WriteContent(path *string, content *string) {
	// Create the file
	file, err := os.Create(*path)
	if err != nil {
		fmt.Println("Error creating file:", err)
		return
	}
	defer file.Close() // Ensure the file is closed

	// Write content to the file
	_, err = file.WriteString(*content)
	if err != nil {
		fmt.Println("Error writing to file:", err)
		return
	}

	// fmt.Println("Content written to", *path)
}

// WaitForUserInput waits for user input and returns the input string
func WaitForUserInput(prompt string) string {
	reader := bufio.NewReader(os.Stdin)
	fmt.Print(prompt)

	input, err := reader.ReadString('\n')
	if err != nil {
		fmt.Println("Error reading input:", err)
		return ""
	}

	// Remove newline character from the end of the input
	return input[:len(input)-1]
}

func RunCommand(command string, argument ...string) {
	//  Run the App_setup.php file with arguments
	cmd := exec.Command(command, argument...)
	// Buffers to capture output and error
	cmd.Stdout = os.Stdout
	cmd.Stderr = os.Stderr
	cmd.Run()
}

func GetFromJson(content []byte) *map[string]any {
	var data map[string]any

	// data = json:var u User
	err := json.Unmarshal(content, &data)
	if err != nil {
		log.Print(err)
	}
	// fmt.Printf("u: %+v\n", u)

	return &data
}
