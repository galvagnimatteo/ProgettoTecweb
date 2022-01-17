document.body.className = document.body.className.replace("no-js", "js");

var menuon = false;

function togglemenu() {
    menuon = !menuon;
    console.log(menuon)
    if (menuon) {
        document.getElementById("menu").className = "openMenu";
    }
    else {
        document.getElementById("menu").className =  "closedMenu";
    }
}
