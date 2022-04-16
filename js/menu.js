var menuon = false;
var btnChiudiMenu = document.getElementById("menubutton-chiudi");
var btnApriMenu = document.getElementById("menubutton-apri");

function togglemenu() {
    menuon = !menuon;
    if (menuon) {
        document.getElementById("menu").className = "openMenu";
		btnChiudiMenu.style = "display: block;";
		btnApriMenu.style = "display: none;";
    }
    else {
        document.getElementById("menu").className =  "closedMenu";
		btnChiudiMenu.style = "display: none;";
		btnApriMenu.style = "display: block;";
    }
}

window.addEventListener("resize", function() {
	if(window.innerWidth > 865){
		btnChiudiMenu.style = "display: none;";
		btnApriMenu.style = "display: none;";	
	}
	
	else{
		btnChiudiMenu.style = "display: none;";
		btnApriMenu.style = "display: block;";
	}
});
