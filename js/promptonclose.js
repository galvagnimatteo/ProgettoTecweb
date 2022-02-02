
window.addEventListener("beforeunload", function(e) {

	if (document.getElementById("aside-warn").dataset.show != "hide") {
		return "Attenzione! Assicurati di aver salvato i dati del biglietto perché non potrai più recuperarli";
	}
});


