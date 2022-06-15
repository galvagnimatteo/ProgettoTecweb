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
		document.getElementById("menu").className = "closedMenu";
		btnChiudiMenu.style = "display: none;";
		btnApriMenu.style = "display: block;";
    }
}

window.addEventListener("resize", function() {
	if(window.innerWidth >= 925){
		//chiudi menu in ogni caso, non è proprio il massimo ma vabbe
		menuon = true;
		togglemenu();

		btnChiudiMenu.style = "display: none;";
		btnApriMenu.style = "display: none;";
	}

	else{
		if (menuon) {
			btnChiudiMenu.style = "display: block;";
			btnApriMenu.style = "display: none;";
		} else {
			btnChiudiMenu.style = "display: none;";
			btnApriMenu.style = "display: block;";
		}
	}
});

btnChiudiMenu.onclick = function () { togglemenu(); };
btnApriMenu.onclick = function () { togglemenu(); };

