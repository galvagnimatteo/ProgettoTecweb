var mappaPosti = document.getElementById("scene");
var textCard = document.getElementById("textSelectGroup");
var manualCard = document.getElementById("manualContainer");
var textRadioBtn = document.getElementById("selezTestuale");
var manualRadioBtn = document.getElementById("selezManual");
var selectNumTicketInt = document.getElementById("selectNumTicketInt");
var selectNumTicketRed = document.getElementById("selectNumTicketRed");
var submitButton = document.getElementById("acquistaBtn");
var selectContainers = document.getElementsByClassName("textSelectContainer");
var textSelectList = [];

var listaPosti = [];
var mappaPostiOccupati = {"a":[], "b":[], "c":[], "d":[], "e":[], "f":[], "g":[]};
arrayPostiOccupati = [];


var MAX_POSTI_LIBERI = 7*15 - arrayPostiOccupati.length;

manualRadioBtn.addEventListener("change", changeCard);
textRadioBtn.addEventListener("change", changeCard);

selectNumTicketInt.addEventListener("change", calcolaPrezzoTot);
selectNumTicketRed.addEventListener("change", calcolaPrezzoTot);


selectNumTicketInt.addEventListener("change", pulisciPostiSelezionati);
selectNumTicketRed.addEventListener("change", pulisciPostiSelezionati);

selectNumTicketInt.addEventListener("change", mostraScelteTestuali);
selectNumTicketRed.addEventListener("change", mostraScelteTestuali);

selectNumTicketInt.addEventListener("change", controllaInput);
selectNumTicketRed.addEventListener("change", controllaInput);


//genera mappa posti occupati
var postiOccupatiString = document.getElementById("textSelectGroup").dataset.postiOccupati;
if (postiOccupatiString != "") {
	arrayPostiOccupati = postiOccupatiString.split(",");
	for (var i = 0; i < arrayPostiOccupati.length; i++) {
		let posto = arrayPostiOccupati[i]
		mappaPostiOccupati[posto.charAt(0)].push(parseInt(posto.substring(1)));
	}
}

document.getElementById("purchaseTicketForm").addEventListener("submit", function(event) {
	var tot = parseInt(selectNumTicketInt.value) + parseInt(selectNumTicketRed.value);
	var aiuto = document.getElementsByClassName("aiutocompilaz");
	var arrPosti;
	if (textRadioBtn.checked) {
		arrPosti = []
		for (var i = 0; i < tot*2; i+=2) {
			arrPosti.push(textSelectList[i].value + textSelectList[i+1].value);
			
		}
		
	} else {
		arrPosti = document.getElementById("seatsString").value.split(",");
	}
	
	var numPostiSelez = arrPosti.length;
	
	if (tot == 0) {
		aiuto[0].setAttribute("class", "aiutocompilaz");
		event.preventDefault(); // non fa submit
		return false; //su qualche browser senza questo non va
	
	} else if (tot > numPostiSelez) {
		aiuto[1].setAttribute("class", "aiutocompilaz");
		event.preventDefault(); 
		return false;
		
	} else if (duplicati(arrPosti)) {
		aiuto[2].setAttribute("class", "aiutocompilaz");
		event.preventDefault(); 
		return false;
	}
	
	if (textRadioBtn.checked) {
		document.getElementById("seatsString").value = arrPosti.join(",");
	}
});


var posti = document.getElementsByClassName("seat");
var inputListaPosti = document.getElementById("seatsString");

for (var i = 0; i < posti.length; i++) {
	var posto = posti[i];
	if (posto.getAttribute("class") != "seat occupato") {
		posto.addEventListener("click", selezionePosto);
		
		posto.addEventListener("touchstart", function(e) { e.target.dataset.touchStart = 1;});
		posto.addEventListener("touchmove", function(e) { e.target.dataset.touchStart = 0;});
		posto.addEventListener("touchend", function(e) { if(e.target.dataset.touchStart == 1) selezionePosto(e);});
	}
		
}

changeCard(null);
calcolaPrezzoTot(null);
controllaInput(null);
mostraScelteTestuali(null);
submitButton.innerHTML = "Acquista 0 biglietti, Totale: 0,00 €";

var panzoomController = panzoom(mappaPosti, {
	maxZoom: 2.0,
	minZoom: 1.0,
	bounds: true,
	boundsPadding: 0.2,
});



function changeCard(event) {
	if (textRadioBtn.checked) {
		manualCard.style.display = "none";
		textCard.style.display = "flex";
	} 
	else {
		manualCard.style.display = "flex";
		textCard.style.display = "none";
	}
}



function calcolaPrezzoTot(event) {
	var pInt = parseFloat(selectNumTicketInt.dataset.prezzoIntero);
	var pRid = parseFloat(selectNumTicketRed.dataset.prezzoRidotto);
	
	var nInt = parseInt(selectNumTicketInt.value);
	var nRid = parseInt(selectNumTicketRed.value);
	
	var result = pInt * nInt + pRid * nRid;
	
	submitButton.innerHTML = "Acquista " + String(nInt + nRid) + " biglietti, Totale: " 
							  + result.toFixed(2).replace('.', ',') + "€";
	
	//tolgo anche gli aiuti se sono visibili
	if (nInt + nRid > 0) {
		var aiuto = document.getElementsByClassName("aiutocompilaz");
		
		aiuto[0].setAttribute("class", "aiutocompilaz hide");
		aiuto[1].setAttribute("class", "aiutocompilaz hide");
		aiuto[2].setAttribute("class", "aiutocompilaz hide");
		aiuto[3].setAttribute("class", "aiutocompilaz hide");
	}
}

function selezionePosto(event) {
	
	var totB = parseInt(selectNumTicketInt.value) + parseInt(selectNumTicketRed.value);

	var g = event.currentTarget;
	
	var codice = g.dataset.codice;
	
	var index = listaPosti.indexOf(codice);
	
	if (index == -1 && listaPosti.length < totB) {
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


function pulisciPostiSelezionati(event) {
	if (!textRadioBtn.checked) {
		listaPosti = [];
		inputListaPosti.setAttribute("value", listaPosti.join());
		var list = document.getElementsByClassName("seat");
	
		for (var i = 0; i < list.length; i++) {
			if (list[i].getAttribute("class") == "seat libero selezionato")
			list[i].setAttribute("class", "seat libero");
		}

	}
}

function mostraScelteTestuali(event) {
	var tot = parseInt(selectNumTicketInt.value) + parseInt(selectNumTicketRed.value);
	qta = textSelectList.length / 2;
	
	if (qta < tot && tot > 0) {
		//ciclo perchè possono anche bypassare l'incremento dell'input e scriverlo a mano
		for (var j = qta; j < tot; j++) {
			generaSelettorePosti(selectContainers[j % 2], j+1);
		}
		
	} else if (qta > tot && tot >= 0) {
		for (var j = tot; j < qta; j++) {
			var elem = textSelectList.pop();
			var elem2 = textSelectList.pop();
			elem.parentElement.remove();
		}
	}
}

function duplicati(arr) {
    var map = {}, i, size;

    for (i = 0, size = arr.length; i < size; i++){
        if (map[arr[i]]){
            return true;
        }

        map[arr[i]] = true;
    }

    return false;
}


function generaSelettorePosti(padre, num) {
	
	var fieldset = document.createElement("fieldset");
	fieldset.setAttribute("class", "selezPostoFieldset");
	var legend = document.createElement("legend");
	legend.innerHTML = "Posto " + num + ":";
	fieldset.appendChild(legend);
	
	var label1 = document.createElement("label");
	label1.setAttribute("for", "f" + num);
	label1.innerHTML = "Fila:";
	fieldset.appendChild(label1);
	
	var sf = document.createElement("select");
	sf.setAttribute("id", "f" + num);
	fieldset.appendChild(sf);
	
	var label2 = document.createElement("label");
	label2.setAttribute("for", "n" + num);
	label2.innerHTML = "Numero:";
	fieldset.appendChild(label2);
	
	var sn = document.createElement("select");
	sn.setAttribute("id", "n" + num);
	fieldset.appendChild(sn);
	
	padre.appendChild(fieldset);
	
	primaLetteraDisponibile = generaOptionFile(sf);
	sf.addEventListener("change", function(event) {
		generaOptionNumeri(sn, sf.value);
	});
	//per generare i primi valori
	generaOptionNumeri(sn, primaLetteraDisponibile);
	
	textSelectList.push(sf, sn);
	
}


function generaOptionFile(sf) {
	var lettere = ["a", "b", "c", "d", "e", "f", "g"];
	primaLettera = null;
	for (i = 0; i < 7; i++) {
		var lettera = lettere[i];
		if (mappaPostiOccupati[lettera].length < 15) {
			if (!primaLettera) {
				primaLettera = lettera
			}
			var fila = document.createElement("option");
			fila.setAttribute("value", lettera);
			fila.innerHTML = lettera.toUpperCase();
			sf.appendChild(fila);
		}
	}
	return primaLettera
	
}

function generaOptionNumeri(sn, lettera) {
	
	//rimuovi tutto
	while (sn.firstChild) {
		sn.removeChild(sn.lastChild);
	}
	
	for (var i = 1; i < 16; i++) {
		if (mappaPostiOccupati[lettera].indexOf(i) == -1) {
			var numero = document.createElement("option");
			numero.setAttribute("value", i);
			numero.innerHTML = i;
			sn.appendChild(numero);
		}
	}
	
}


function controllaInput(event) {
	var rexp = /^\d+$/;
	var aiuto = document.getElementsByClassName("aiutocompilaz");
	if (rexp.test(selectNumTicketRed.value) && rexp.test(selectNumTicketInt.value)) {
		aiuto[3].setAttribute("class", "aiutocompilaz hide");
		nint = parseInt(selectNumTicketInt.value);
		nred = parseInt(selectNumTicketRed.value);
		
		maxPostiPrenotabili = MAX_POSTI_LIBERI - (nint + nred);
		
		selectNumTicketInt.setAttribute("max", nint + maxPostiPrenotabili);
		selectNumTicketRed.setAttribute("max", nred + maxPostiPrenotabili);
	} else {
		
		aiuto[3].setAttribute("class", "aiutocompilaz");
		
	}
}
