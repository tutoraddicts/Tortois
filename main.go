package main

import (
	"bufio"
	"fmt"
	"io/fs"
	"os/exec"

	"io"
	"log"
	"net/http"
	"os"
	"strings"
)

var (
	LatestVersion = "1.0"
)

func main() {
	var Version string

	reader := bufio.NewReader(os.Stdin)
	fmt.Printf("Which Version you want to install (Leave Blank for Latest Version): ")
	Version, _ = reader.ReadString('\n')
	Version = strings.TrimSpace(Version)
	if Version == "" {
		fmt.Printf("Going to Download Latest Package of Tortois \n")
		Version = LatestVersion
	}

	// string.replace()
	DownloadUrl := fmt.Sprintf("https://github.com/tutoraddicts/Tortois/releases/download/%s/tortois.exe", Version)
	// DownloadUrl :=
	// DownloadMinor := "https://github.com/tutoraddicts/Tortois/releases/download/Minor/tortois.exe"

	fmt.Printf("Download URL: %s \n", DownloadUrl)

	DownloadPath := os.TempDir() + "/tortois.exe"
	DownloadFile(DownloadPath, DownloadUrl)
	// os.TempDir()
	installTotois(DownloadPath)

	fmt.Printf("Press Enter to Finish the Installation...")
	reader.ReadString('\n')

}

func copyFile(src, dst string) error {
	sourceFile, err := os.Open(src)
	if err != nil {
		return err
	}
	defer sourceFile.Close()

	destinationFile, err := os.Create(dst)
	if err != nil {
		return err
	}
	defer destinationFile.Close()

	_, err = io.Copy(destinationFile, sourceFile)
	if err != nil {
		return err
	}

	fmt.Printf("File copied successfully from %s to %s \n", src, dst)
	return nil
}

func DownloadFile(filepath string, url string) {

	file, err := os.Create(filepath)
	if err != nil {
		log.Fatal(err)
	}
	client := http.Client{
		CheckRedirect: func(r *http.Request, via []*http.Request) error {
			r.URL.Opaque = r.URL.Path
			return nil
		},
	}
	// Put content on file
	resp, err := client.Get(url)
	if err != nil {
		log.Fatal(err)
	}
	defer resp.Body.Close()

	size, _ := io.Copy(file, resp.Body)

	defer file.Close()

	fmt.Printf("Downloaded a file %s with size %d \n", filepath, size)
}

// filepath is the path where the exe file is installed
func installTotois(filepath string) {
	os.Mkdir("C:/Tortois", fs.ModeAppend)

	copyFile(filepath, "C:/Tortois/tortois.exe")

	path := os.Getenv("PATH")
	// fmt.Printf("Path Variable is : %s \n", path)

	if !strings.Contains(path, "C:\\Tortois") {
		fmt.Printf("Adding Tortois in the Path \n")
		// cmdCommand := "setx path \"%path%;C:/Tortois/tortois.exe\""
		// fmt.Printf("Command to execute in cmd : %s \n", cmdCommand)
		cmd := exec.Command("setx", "/M", "path", fmt.Sprintf("%sC:\\Tortois", path))
		err := cmd.Run()
		if err != nil {
			fmt.Printf("Facing issue while adding PATH : \n %s", err)
		}
	} else {
		fmt.Printf("Path already set to the desired location \n")
	}
}
