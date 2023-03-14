
var colorPickerValue =  document.getElementById("color-picker").getAttribute('value');
let firstHomepageChild = document.getElementById("homepage").firstElementChild;
let firstGridChild = document.getElementById("grid").firstElementChild;
const coloreMap = new Map();


firstHomepageChild.style.visibility = "visible";
var lastHomepage = firstHomepageChild.id;

firstGridChild.style.visibility = "visible";
var lastGrid = firstGridChild.id;

document.getElementById("color-picker").addEventListener("change", function(event){
    colorPickerValue = event.target.value;
});


function onClick(div) {
    var currentHomepage = getCurrentHomepage();

    if (div.getAttribute("name") == "header_" + currentHomepage) {
        var elms = document.querySelectorAll("[name='header_"+currentHomepage+"']");
        for (var i = 0; i < elms.length; i++) 
            elms[i].style.backgroundColor = colorPickerValue;

    } else if (div.getAttribute("name") == "footer") {
        var elms = document.querySelectorAll("[name='footer_"+currentHomepage+"']");
        for (var i = 0; i < elms.length; i++) 
            elms[i].style.backgroundColor = colorPickerValue;
            
    } else {
        div.style.backgroundColor = colorPickerValue; 
    }

    coloreMap.set(div.getAttribute("name"), colorPickerValue);

    // console.log(...coloreMap.entries());
    // console.log(coloreMap.size)
}

function alertColor(){
    alert(colorPickerValue.toString());
}

function changeHomepage() {
    var currentHomepage = getCurrentHomepage();

    if (lastHomepage != "") {
        let a = document.getElementById(lastHomepage);
        a.style.visibility = "collapse";
    }

    let p = document.getElementById(currentHomepage);
    p.style.visibility = "visible";
    lastHomepage = currentHomepage;

    changeGrid();
}

function changeGrid() {
    var e = document.getElementById("gridOption");
    var currentGrid = e.options[e.selectedIndex].text + "_" + getCurrentHomepage();

    if (lastGrid != "") {
        let a = document.getElementById(lastGrid);
        a.style.visibility = "collapse";
    }

    let p = document.getElementById(currentGrid);
    p.style.visibility = "visible";
    lastGrid = currentGrid;
}

function getCurrentHomepage() {
    var a = document.getElementById("homepageOption");
    return a.options[a.selectedIndex].text;
}

function changeFont() {
    var e = document.getElementById("fontOption");
    var selectedFont = e.options[e.selectedIndex].text;
    console.log(selectedFont);
}