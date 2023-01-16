
var colorPickerValue =  document.getElementById("color-picker").getAttribute('value');
let firstHomepageChild = document.getElementById("homepage").firstElementChild;
let firstGridChild = document.getElementById("grid").firstElementChild;


firstHomepageChild.style.visibility = "visible";
var lastHomepage = firstHomepageChild.id;

firstGridChild.style.visibility = "visible";
var lastGrid = firstGridChild.id;



document.getElementById("color-picker").addEventListener("change", function(event){
    colorPickerValue = event.target.value;
});

function alertColor(){
    alert(colorPickerValue.toString());
}


function changeHomepage() {
    var e = document.getElementById("homepageOption");
    var currentHomepage = e.options[e.selectedIndex].text;

    if (lastHomepage != "") {
        let a = document.getElementById(lastHomepage);
        a.style.visibility = "collapse";
    }

    let p = document.getElementById(currentHomepage);
    p.style.visibility = "visible";
    lastHomepage = currentHomepage;
}

function changeGrid() {
    var e = document.getElementById("gridOption");
    var currentGrid = e.options[e.selectedIndex].text;

    if (lastGrid != "") {
        let a = document.getElementById(lastGrid);
        a.style.visibility = "collapse";
    }

    let p = document.getElementById(currentGrid);
    p.style.visibility = "visible";
    lastGrid = currentGrid;
}

require(['TYPO3/CMS/Core/Ajax/AjaxRequest'], function (AjaxRequest) {
    var saveButton = document.getElementsByClassName("testButton");
    let writeCSS = function (element){
        new AjaxRequest(TYPO3.settings.ajaxUrls.stylingcockpit_dosomething)
            .withQueryArguments({input: 1})
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
