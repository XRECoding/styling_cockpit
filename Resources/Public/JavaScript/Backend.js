var colorPickerValue =  document.getElementById("color-picker").getAttribute('value');
let firstHomepageChild = document.getElementById("homepage").firstElementChild;
let firstGridChild = document.getElementById("grid").firstElementChild;
const coloreMap = new Map();

// save the current selected font
var selectedFont = document.getElementById("fontOption").options[document.getElementById("fontOption").selectedIndex].text;

// save the current text size
var selectedTextSize = document.getElementById("textSizeOption").options[document.getElementById("textSizeOption").selectedIndex].text;

// save current text alignment
var selectedtextAlignment = document.getElementById("textAlignmentOption").options[document.getElementById("textAlignmentOption").selectedIndex].text;

// credit for the hex conversion: http://wowmotty.blogspot.com/2009/06/convert-jquery-rgb-output-to-hex-color.html
var hexDigits = new Array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

//Function to convert rgb color to hex format
function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
    return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}

// save the current element color in the map
// header
coloreMap.set("header_homepage1", rgb2hex(document.getElementById("homepage1_header").style.backgroundColor));
coloreMap.set("header_homepage2", rgb2hex(document.getElementById("homepage2_header").style.backgroundColor));
//footer
coloreMap.set("footer_homepage1", rgb2hex(document.getElementById("homepage1footer").style.backgroundColor));
coloreMap.set("footer_homepage2", rgb2hex(document.getElementById("homepage2footer").style.backgroundColor));
// hp1
coloreMap.set("homepage1_homepage1_main1", rgb2hex(document.getElementById("homepage1_homepage1_main1").style.backgroundColor));
coloreMap.set("homepage1_homepage1_main2", rgb2hex(document.getElementById("homepage1_homepage1_main2").style.backgroundColor));
coloreMap.set("homepage1_homepage1_main3", rgb2hex(document.getElementById("homepage1_homepage1_main3").style.backgroundColor));
// hp2
coloreMap.set("homepage2_homepage2_main1", rgb2hex(document.getElementById("homepage2_homepage2_main1").style.backgroundColor));
coloreMap.set("homepage2_homepage2_main2", rgb2hex(document.getElementById("homepage2_homepage2_main2").style.backgroundColor));
// 1 spaltig
coloreMap.set("1spaltig_normal_homepage1", rgb2hex(document.getElementById("1spaltig_normal_homepage1").style.backgroundColor));
coloreMap.set("1spaltig_normal_homepage2", rgb2hex(document.getElementById("1spaltig_normal_homepage2").style.backgroundColor));
// 2 spalten 30/70
coloreMap.set("2Spalten-30-70_main_links_homepage1", rgb2hex(document.getElementById("2Spalten-30-70_main_links_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-30-70_main_links_homepage2", rgb2hex(document.getElementById("2Spalten-30-70_main_links_homepage2").style.backgroundColor));
coloreMap.set("2Spalten-30-70_main_rechts_homepage1", rgb2hex(document.getElementById("2Spalten-30-70_main_rechts_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-30-70_main_rechts_homepage2", rgb2hex(document.getElementById("2Spalten-30-70_main_rechts_homepage2").style.backgroundColor));
// 2 spalten 50/50
coloreMap.set("2Spalten-50-50_main_links_homepage1", rgb2hex(document.getElementById("2Spalten-50-50_main_links_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-50-50_main_links_homepage2", rgb2hex(document.getElementById("2Spalten-50-50_main_links_homepage2").style.backgroundColor));
coloreMap.set("2Spalten-50-50_main_rechts_homepage1", rgb2hex(document.getElementById("2Spalten-50-50_main_rechts_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-50-50_main_rechts_homepage2", rgb2hex(document.getElementById("2Spalten-50-50_main_rechts_homepage2").style.backgroundColor));
// 2 spalten 70/30
coloreMap.set("2Spalten-70-30_main_links_homepage1", rgb2hex(document.getElementById("2Spalten-70-30_main_links_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-70-30_main_links_homepage2", rgb2hex(document.getElementById("2Spalten-70-30_main_links_homepage2").style.backgroundColor));
coloreMap.set("2Spalten-70-30_main_rechts_homepage1", rgb2hex(document.getElementById("2Spalten-70-30_main_rechts_homepage1").style.backgroundColor));
coloreMap.set("2Spalten-70-30_main_rechts_homepage2", rgb2hex(document.getElementById("2Spalten-70-30_main_rechts_homepage2").style.backgroundColor));


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

    } else if (div.getAttribute("name") == "footer_" + currentHomepage) {
        var elms = document.querySelectorAll("[name='footer_"+currentHomepage+"']");
        for (var i = 0; i < elms.length; i++)
            elms[i].style.backgroundColor = colorPickerValue;

    } else {
        div.style.backgroundColor = colorPickerValue;
    }

    if (div.getAttribute("name").includes("header") || div.getAttribute("name").includes("footer") ||
        div.getAttribute("name").includes("homepage1_main") || div.getAttribute("name").includes("homepage2_main")){
        coloreMap.set(div.getAttribute("name"), colorPickerValue);
    } else {
        coloreMap.set(div.getAttribute("name").concat("_").concat(currentHomepage), colorPickerValue);
    }

}


/**
 * {@changeHomepage}
 * Gets triggert if new homepage in frontend is selected.
 * Sets the olf homepage to collapse and the new one
 * to visible so that the selected homepage is shown in
 * the frontend
 */
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

/**
 * {@changeGrid}
 * Gets triggert if new grid in frontend is selected.
 * Sets the olf grid to collapse and the new one
 * to visible so that the selected grid is shown in
 * the frontend
 */
function changeGrid() {
    var e = document.getElementById("gridOption");
    var currentGrid = e.options[e.selectedIndex].text + "_" + getCurrentHomepage();

    if (lastGrid != "") {
        let a = document.getElementById(lastGrid);
        console.log(lastGrid);
        a.style.visibility = "collapse";
    }

    let p = document.getElementById(currentGrid);
    p.style.visibility = "visible";
    lastGrid = currentGrid;
}


function alertText() {
    alert("Der Header / Footer kann nur über die Homepage Layouts angepasst werden.")
}



require(['TYPO3/CMS/Core/Ajax/AjaxRequest'], function (AjaxRequest) {
    var saveButton = document.getElementsByClassName("testButton");
    let writeCSS = function (element){
        // making array because ajax doesn't accept map
        const arr = Array.from(coloreMap);
        var pageID = document.getElementById("pageID").value;
        new AjaxRequest(TYPO3.settings.ajaxUrls.stylingcockpit_dosomething)
            .withQueryArguments({colorArray: arr, pageID: pageID, font: selectedFont, size: selectedTextSize, alignment: selectedtextAlignment})
            .get()
            .then(async function (response) {
                const resolved = await response.resolve();
                console.log(resolved.result);
            });
    }

    for (let i = 0; i < saveButton.length; i++) {
        saveButton[i].addEventListener('click', writeCSS, false);
    }
});

/**
 * {@getCurrentHomepage}
 * Fetches the text of the currently selected homepage
 */
function getCurrentHomepage() {
    var a = document.getElementById("homepageOption");
    return a.options[a.selectedIndex].text;
}

/**
 * {@changeFont}
 * Gets triggered if new font in frontend is selected.
 * Sets the font globally to the new font
 */
function changeFont() {
    var e = document.getElementById("fontOption");
    selectedFont = e.options[e.selectedIndex].text;
    //console.log(selectedFont);
}

function changeTextSize() {
    var e = document.getElementById("textSizeOption");
    selectedTextSize = e.options[e.selectedIndex].text;
    // console.log(selectedTextSize);
}

function changeTextAlignment() {
    var e = document.getElementById("textAlignmentOption");
    selectedtextAlignment = e.options[e.selectedIndex].text;
    // console.log(selectedtextAlignment);
}

