$outputExecutable = "tortois.exe"

function Write-log {
    param (
        [string]$massage,
        [bool]$write_it = $false
    )

    Write-host $massage
    
}
function CheckGPP {
    # Attempt to get g++ version
    $gppVersion = g++ --version 2>$null
    # Check if g++ is installed
    if ($LASTEXITCODE -eq 0) {
        Write-log "g++ is installed."
        Write-log $gppVersion
    }
    else {
        Write-Error "g++ is not installed."
        return -1;
    }
}

function CheckPhp {
    # Attempt to get php version
    $phpVersion = php --version 2>$null
    # Check if php is installed
    if ($LASTEXITCODE -eq 0) {
        Write-log "php is installed."
        Write-log $phpVersion
    }
    else {
        Write-Error "php is not installed."
        return -1;
    }
}

function CheckGo {
    # Attempt to get go version
    $goVersion = go version 2>$null
    # Check if php is installed
    if ($LASTEXITCODE -eq 0) {
        Write-log "golang is installed."
        Write-log $goVersion
    }
    else {
        Write-Error "golang is not installed."
        return -1;
    }
    
}

function BuildCpp {
    # Collect all .cpp and .hpp files from the directory and subdirectories
    $cppFiles = Get-ChildItem -Path . -Recurse -Include *.cpp, *.hpp

    # Check if any .cpp files were found
    if ($cppFiles.Count -eq 0) {
        Write-log "No .cpp or .hpp files found in the specified directory."
        exit 1
    }

    # Prepare the command to compile the files
    $cppFilesList = $cppFiles.FullName -join " "
    $gppCommand = "g++ $cppFilesList -o $outputExecutable"

    # Print the g++ command for debugging
    Write-log "Running: $gppCommand"

    # Run the g++ command to build the executable
    try {
        Invoke-Expression $gppCommand
        Write-log "Build successful. Executable created: $outputExecutable"
    }
    catch {
        Write-log "Build failed. Please check the error messages above."
    }
}

function BuildGo() {
    # go mod init
    go mod init torotis
    go mod tidy
    Write-log -massage "Buidling the Go Application"
    go build -o InstallTortois.exe
    # Write-log -massage "Executing the Go Application"
    # ./tortois.exe
}

CheckGo
CheckGPP
CheckPhp
# BuildCpp
BuildGo



