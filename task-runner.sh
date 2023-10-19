# colors
# red="\u001b[31m"
# green="\u001b[32m"
# yellow="\u001b[33m"
# clr="\u001b[0m"

# colors - better compatibility on macs
red="\e[1;31m"
green="\e[1;32m"
yellow="\e[1;33m"
clr="\e[1;0m"

event=$1
path=$2
src=$3
dest=$4
subtask=

buildJsSub() {
    local path="$1"
    local src="$2"
    local dest="$3"

    filename=$(basename -- "$path")
    filenameMin=${filename%.*}.min.js

    filePathStructure=${path#"$src"}
    fileDirStructure=${filePathStructure%"$filename"}
    fullDestDir="$dest/$fileDirStructure"

    existingMinFilePath=${path%.*}.min.js
    existingMapPath=${existingMinFilePath%.*}.js.map

    printf "\nCompiling ${path}${clr} "

    mkdir -p "$fullDestDir"

    if [[ -e "$existingMinFilePath" ]]; then
        cp "$existingMinFilePath" "$fullDestDir"

        if [[ -e "$existingMapPath" ]]; then
            cp "$existingMapPath" "$fullDestDir"
        fi
    else
        terser "$path" -o "$fullDestDir/$filenameMin" --source-map --compress --mangle
    fi

    printf "${green}✓${clr}\n"
}

buildJs() {
    local subtask="$1"
    printf "\n${yellow}Building ${subtask} js... ${clr}"
    npm run js:${subtask}
}

watchJs() {
    local path="$1"
    local subtask=

    if [[ $path == *".../js/"* ]]; then
        buildJsSub "$path" "src" "dist"
    fi
}

buildSass() {
    # local subtask="$1"
    printf "\n${yellow}Building ${subtask} css... ${clr}"
    npm run scss

    if [[ ! `type -p unbuffer` ]]; then
        lint=`npm run stylelint`
    else
        lint=`unbuffer npm run stylelint`
    fi

    printf "${lint}"

    if [[ $lint == *"error"* ]]; then
        printf "${red}\n\nBuild Failed!${clr}\n\n"
        exit 1
    fi
}

watchSass() {
    local path="$1"
    # local subtask=

    npm run scss
    npm run stylelint
}

if [[ $event == "build:sass" ]]; then
    buildSass
    printf "${green}\n\nSass Build Complete ✓${clr}\n\n"
fi

if [[ $event == "build:js" ]]; then
    buildJs ""

    printf "${green}\n\nJs Build Complete ✓${clr}\n\n"
fi

if [[ $event == "build:js:"* ]]; then
    buildJsSub "$path" "$src" "$dest"
fi

if [[ $event == "change" ]]; then
    if [[ $path == *".js" ]]; then
        watchJs $path
    fi

    if [[ $path == *".scss" ]]; then
        watchSass $path
    fi
fi
