var menuon = false;

function togglemenu() {
    menuon = !menuon;
    if (menuon) {
        document.getElementById("menu").className = "openMenu";
    }
    else {
        document.getElementById("menu").className =  "closedMenu";
    }
}
