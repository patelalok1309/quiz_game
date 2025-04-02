const fs = require("fs");
function printDirectoryStructure(directoryPath, indent = "-") {
    if (!fs.existsSync(directoryPath)) {
        console.log("Directory does not exist");
        return;
    }

    const files = fs.readdirSync(directoryPath, {withFileTypes: true});

    for (const file of files) {
        if (file.name.startsWith(".")) {
            continue;
        }

        if (file.isDirectory()) {
            console.log(indent + "-" + file.name);
            printDirectoryStructure(
                directoryPath + "/" + file.name,
                indent + "-"
            );
        } else {
            console.log(indent + "-" + file.name);
        }
    }
}

printDirectoryStructure("./", "-");

