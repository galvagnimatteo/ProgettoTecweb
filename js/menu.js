var menuon = false;
var btnChiudiMenu = document.getElementById("menubutton-chiudi");
var btnApriMenu = document.getElementById("menubutton-apri");

function togglemenu() {
    menuon = !menuon;
    if (menuon) {
		document.getElementById("menu").className = "openMenu";
		document.getElementById("menu").setAttribute('aria-hidden', 'false');
		btnChiudiMenu.style = "display: block;";
		btnApriMenu.style = "display: none;";
    }
    else {
		document.getElementById("menu").className = "closedMenu";
		document.getElementById("menu").setAttribute('aria-hidden', 'true');
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
		btnChiudiMenu.setAttribute('aria-hidden', 'true');
		btnApriMenu.style = "display: none;";
		btnApriMenu.setAttribute('aria-hidden', 'true');
	}

	else{
		if (menuon) {
			btnChiudiMenu.style = "display: block;";
			btnChiudiMenu.setAttribute('aria-hidden', 'false');
			btnApriMenu.style = "display: none;";
			btnApriMenu.setAttribute('aria-hidden', 'true');
		} else {
			btnChiudiMenu.style = "display: none;";
			btnChiudiMenu.setAttribute('aria-hidden', 'true');
			btnApriMenu.style = "display: block;";
			btnApriMenu.setAttribute('aria-hidden', 'false');
		}
	}
});
