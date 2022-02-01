var elem = document.getElementById("scene");
var autoCard = document.getElementById("autoRadioGroup");
var manualCard = document.getElementById("manualContainer");
var postiViciniCheck = document.getElementById("postiViciniCheck");
var autoRadioBtn = document.getElementById("selezAuto");
var manualRadioBtn = document.getElementById("selezManual");
var selectNumTicketInt = document.getElementById("selectNumTicketInt");
var selectNumTicketRed = document.getElementById("selectNumTicketRed");
var submitButton = document.getElementById("acquistaBtn");

var listaPosti = [];
const MAX_POSTI = 4;

manualRadioBtn.addEventListener("change", changeCard);
autoRadioBtn.addEventListener("change", changeCard);


selectNumTicketInt.addEventListener("change", calcolaPrezzoTot);
selectNumTicketRed.addEventListener("change", calcolaPrezzoTot);

selectNumTicketInt.addEventListener("change", dynamicOption);
selectNumTicketRed.addEventListener("change", dynamicOption);

selectNumTicketInt.addEventListener("change", pulisciPostiSelezionati);
selectNumTicketRed.addEventListener("change", pulisciPostiSelezionati);

var posti = document.getElementsByClassName("seat");
var inputListaPosti = document.getElementById("seatsString");

for (var i = 0; i < posti.length; i++) {
	var posto = posti[i];
	
	posto.addEventListener("click", selezionePosto);
}

changeCard(null);
calcolaPrezzoTot(null);


changeCard(null);

panzoom(elem, {
	maxZoom: 1.8,
	minZoom: 0.2,
	bounds: true,
	boundsPadding: 0.2,
});

/*var zoomFactor = Math.min(Math.max(document.documentElement.clientWidth, 0), 1204) / Math.min(Math.max(document.documentElement.clientHeight, 0), 900);

instance.smoothZoom(Math.floor(manualCard.offsetWidth/2 * 0.08), Math.floor(manualCard.offsetHeight/2), zoomFactor * 0.80); */


/*function removeClass(elem, remove) {
    var newClassName = "";
    var i;
    var classes = elem.className.split(" ");
    for(i = 0; i < classes.length; i++) {
        if(classes[i] !== remove) {
            newClassName += classes[i] + " ";
        }
    }
    elem.className = newClassName;
}

function addClass(elem, add) {
	elem.className += " " + add;
}*/

function changeCard(e) {
	if (autoRadioBtn.checked) {
		manualCard.style.display = "none";
		autoCard.style.display = "flex";
		postiViciniCheck.style.display = "flex";
	} 
	else {
		manualCard.style.display = "flex";
		postiViciniCheck.style.display = "none";
		autoCard.style.display = "none";
	}
}

function maxOption(max, target) {
	max = max + 1;
	var numChildren = target.options.length;
	
	if (numChildren <= max) {
		for (var i = numChildren; i < max; i++) {
			var opt = document.createElement("option");
			opt.value = String(i);
			opt.innerText = String(i);
			target.appendChild(opt);
		}
	}
	
	else {
		for (var i = numChildren; i > max; i--) {
			console.log(target.options[i-1])
			target.removeChild(target.options[i-1]);
		}
	}
	
}

function dynamicOption(e) {
	
	var val = parseInt(e.target.value);
	var target2 = selectNumTicketInt.getAttribute("id") == e.target.getAttribute("id") ?
				  selectNumTicketRed : selectNumTicketInt;
	
	if (val == 0 && parseInt(target2.value)==0) {
		maxOption(MAX_POSTI, target2);
		maxOption(MAX_POSTI, e.target);
	} else {
		maxOption(MAX_POSTI - val, target2);
		maxOption(val, e.target);
	}
}

function calcolaPrezzoTot(e) {
	var pInt = parseFloat(selectNumTicketInt.dataset.prezzoIntero);
	var pRid = parseFloat(selectNumTicketRed.dataset.prezzoRidotto);
	
	var nInt = parseInt(selectNumTicketInt.value);
	var nRid = parseInt(selectNumTicketRed.value);
	
	var result = pInt * nInt + pRid * nRid;
	
	submitButton.innerHTML = "Acquista " + String(nInt + nRid) + " biglietti, " + result.toFixed(2).replace('.', ',') + "€";

}

function selezionePosto(e) {
	
	var nInt = parseInt(selectNumTicketInt.value);
	var nRid = parseInt(selectNumTicketRed.value);
	var g = e.currentTarget;
	//console.log(g);
	var codice = g.dataset.codice;
	
	var index = listaPosti.indexOf(codice);
	
	if (index == -1 && listaPosti.length < nInt + nRid) {
		listaPosti.push(codice);
		g.setAttribute("class", "seat libero selezionato");
		inputListaPosti.setAttribute("value", listaPosti.join());
		calcolaPrezzoTot(null);
	} else if (index != -1) {
		listaPosti.splice(index, 1);
		g.setAttribute("class", "seat libero");
		inputListaPosti.setAttribute("value", listaPosti.join());
		calcolaPrezzoTot(null);
	}
	
}



function pulisciPostiSelezionati() {
	if (autoRadioBtn.checked) 
		return;
	else {
		
		listaPosti = [];
		inputListaPosti.setAttribute("value", listaPosti.join());
		var list = document.getElementsByClassName("seat");
		
		for (var i = 0; i < list.length; i++) {
			if (list[i].getAttribute("class") == "seat libero selezionato")
			list[i].setAttribute("class", "seat libero");
		}
		
		
		
	}
}

