var mappaPosti = document.getElementById("scene");
var textCard = document.getElementById("textSelectGroup");
var manualCard = document.getElementById("manualContainer");
var textRadioBtn = document.getElementById("selezTestuale");
var manualRadioBtn = document.getElementById("selezManual");
var selectNumTicketInt = document.getElementById("selectNumTicketInt");
var selectNumTicketRed = document.getElementById("selectNumTicketRed");
var submitButton = document.getElementById("acquistaBtn");
var textSelectList = [
	document.getElementById("p1fila"),
	document.getElementById("p1numero"),
	document.getElementById("p2fila"),
	document.getElementById("p2numero"),
	document.getElementById("p3fila"),
	document.getElementById("p3numero"),
	document.getElementById("p4fila"),
	document.getElementById("p4numero")
]

var listaPosti = [];
var mappaPostiOccupati = {"a":[], "b":[], "c":[], "d":[], "e":[], "f":[], "g":[]};
const MAX_POSTI_SELEZIONABILI = 4;
const MAX_POSTI = 7*15; //tutte le sale sono uguali


const messaggi = {
	"libero": "Ci sono ancora posti liberi",
	"soldout": "Non ci sono più posti liberi"
};

manualRadioBtn.addEventListener("change", changeCard);
textRadioBtn.addEventListener("change", changeCard);

selectNumTicketInt.addEventListener("change", calcolaPrezzoTot);
selectNumTicketRed.addEventListener("change", calcolaPrezzoTot);

selectNumTicketInt.addEventListener("change", dynamicOption);
selectNumTicketRed.addEventListener("change", dynamicOption);

selectNumTicketInt.addEventListener("change", pulisciPostiSelezionati);
selectNumTicketRed.addEventListener("change", pulisciPostiSelezionati);

selectNumTicketInt.addEventListener("change", mostraScelteTestuali);
selectNumTicketRed.addEventListener("change", mostraScelteTestuali);

//selectNumTicketInt.addEventListener("change", controllaInput);
//selectNumTicketRed.addEventListener("change", controllaInput);


//genera mappa posti occupati
arrayPostiOccupati = document.getElementById("textSelectGroup").dataset.postiOccupati.split(",");
for (var i = 0; i < arrayPostiOccupati.length; i++) {
	let posto = arrayPostiOccupati[i]
	mappaPostiOccupati[posto.charAt(0)].push(posto.substring(1));
}


document.getElementById("purchaseTicketForm").addEventListener("submit", function(event) {
	event.preventDefault();
	var tot = parseInt(selectNumTicketInt.value) + parseInt(selectNumTicketRed.value);
	var aiuto = document.getElementsByClassName("aiutocompilaz");
	var arrPosti;
	if (textRadioBtn.checked) {
		arrPosti = []
		for (var i = 0; i < tot*2; i+=2) {
			arrPosti.push(textSelectList[i].value + textSelectList[i+1].value);
			
		}
		arrPosti = arrPosti.join(",");
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
	return false;
	
	
});


var posti = document.getElementsByClassName("seat");
var inputListaPosti = document.getElementById("seatsString");

for (var i = 0; i < posti.length; i++) {
	var posto = posti[i];
	if (posto.getAttribute("class") != "seat occupato")
		posto.addEventListener("click", selezionePosto);
}

changeCard(null);
calcolaPrezzoTot(null);
//controllaInput(null);
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
		for (var i = numChildren; i > max; i--) 
			target.removeChild(target.options[i-1]);
		
	}
	
}



function dynamicOption(event) {
	
	var val = parseInt(event.target.value);
	var target2 = selectNumTicketInt.getAttribute("id") == event.target.getAttribute("id") ?
				  selectNumTicketRed : selectNumTicketInt;
	
	if (val == 0 && parseInt(target2.value)==0) {
		maxOption(MAX_POSTI_SELEZIONABILI, target2);
		maxOption(MAX_POSTI_SELEZIONABILI, event.target);
	} else {
		maxOption(MAX_POSTI_SELEZIONABILI - val, target2);
		maxOption(val, event.target);
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
/*
function controllaInput(event) {
	var totPosti = parseInt(selectNumTicketInt.value) + parseInt(selectNumTicketRed.value);

	
	var selezDavanti = document.getElementById("selezDavanti");
	var selezCentro = document.getElementById("selezCentro");
	var selezDietro = document.getElementById("selezDietro");
	
	var warningPosti = document.getElementById("warnPosti");
	
	var pDv = document.getElementById("pDv");
	var pCe = document.getElementById("pCe");
	var pDt = document.getElementById("pDt");
	
	
	if (pDv.dataset.postiLib / MAX_POSTI >= 0.5) 
		warningPosti.setAttribute("class", "warning hide"); //nascondi
	 else 
		warningPosti.setAttribute("class", "warning"); //mostra
	
	if(postiViciniFlag.checked) {
		
		if (pDv.dataset.maxSeq >= totPosti) {
			pDv.innerHTML = messaggi["libero"];
			selezDavanti.disabled = false;
		} else {
			pDv.innerHTML = messaggi["soldout"];
			selezDavanti.disabled = true;
		}
		
		if (pCe.dataset.maxSeq >= totPosti) {
			pCe.innerHTML = messaggi["libero"];
			selezCentro.disabled = false;
		} else {
			pCe.innerHTML = messaggi["soldout"];
			selezCentro.disabled = true;
			
		}
		
		if (pDt.dataset.maxSeq >= totPosti) {
			pDt.innerHTML = messaggi["libero"];
			selezDietro.disabled = false;
		} else {
			pDt.innerHTML = messaggi["soldout"];
			selezDietro.disabled = true;			
			
		}
	} else {
		
		if (pDv.dataset.postiLib >= totPosti) {
			pDv.innerHTML = messaggi["libero"];
			selezDavanti.disabled = false;
		} else {
			pDv.innerHTML = messaggi["soldout"];
			selezDavanti.disabled = true;
		}
		
		if (pCe.dataset.postiLib >= totPosti) {
			pCe.innerHTML = messaggi["libero"];
			selezCentro.disabled = false;
		} else {
			pCe.innerHTML = messaggi["soldout"];
			selezCentro.disabled = true;
		}
		
		if (pDt.dataset.postiLib >= totPosti) {
			pDt.innerHTML = messaggi["libero"];
			selezDietro.disabled = false;
		} else {
			pDt.innerHTML = messaggi["soldout"];
			selezDietro.disabled = true;			
		}
	}
	
}
*/

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
	var selezPostiList = document.getElementsByClassName("selezPostoFieldset");
	
	
	for (var i = 0; i < MAX_POSTI_SELEZIONABILI; i++) {
		if (i < tot) {
			selezPostiList[i].setAttribute("class", "selezPostoFieldset");
		} else {
			selezPostiList[i].setAttribute("class", "selezPostoFieldset hide");
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