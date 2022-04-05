var visible = {    
    filmform: false,    
    proiezioniform: false
};
function changecontext(context){
    if (context === "films") {
        document.getElementById('inserisci_proiezione').className = 'closed'
        document.getElementById('proiezioni').className = 'closed'
        document.getElementById('films').className = 'open'
        document.getElementById('filmarea').className = 'activeoption'
        document.getElementById('proiezioniarea').className = 'inactiveoption'
        if (visible.filmform) {
            document.getElementById('inserisci_film').className = 'open'
        }
        else {
            document.getElementById('inserisci_film').className = 'closed'
        }
    }
    else {
        document.getElementById('inserisci_film').className = 'closed'
        document.getElementById('films').className = 'closed'
        document.getElementById('proiezioni').className = 'open'
        document.getElementById('proiezioniarea').className = 'activeoption'
        document.getElementById('filmarea').className = 'inactiveoption'
        if (visible.proiezioniform) {
            document.getElementById('inserisci_proiezione').className = 'open'
        }
        else {
            document.getElementById('inserisci_proiezione').className = 'closed'
        }
    }
}

function toggleform(form){
    if (form === "film") {
        visible.filmform = !visible.filmform;
        if (visible.filmform) {
            document.getElementById('inserisci_film').className = 'open'
        }
        else {
            document.getElementById('inserisci_film').className = 'closed'
        }
    }
    else {
        visible.proiezioniform = !visible.proiezioniform;
        if (visible.proiezioniform) {
            document.getElementById('inserisci_proiezione').className = 'open'
        }
        else {
            document.getElementById('inserisci_proiezione').className = 'closed'
        }
    }
}

films = null;
proiezioni = null;

function richiedi_film() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/films.php');
    request.send();
    request.onload = () => {
        var data = JSON.parse(request.response);
        films = data.films;
        updatehtml_film(films);
    }
}
function richiedi_proiezioni() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/proiezioni.php');
    request.send();
    request.onload = () => {
        var data = JSON.parse(request.response);
        proiezioni = data.proiezioni;
        console.log(films);
        updatehtml_proiezioni(proiezioni);
    }
}
function post_film() {
    let url = "./api/films";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {            
            var data = JSON.parse(xhr.responseText);
            var status = data.status;
            if (status === "ok") {
                var films = data.films;
                updatehtml_film(films);
                document.getElementById("result_insert_film").innerText = "inserimento avvenuto con successo"
            }
            else {
                document.getElementById("result_insert_film").innerText = status;
            }
        }
    };

    let data = '{'+
        '"Titolo":"' + document.getElementById("imputtitolo").value+'",'+
        '"Genere":"' + document.getElementById("imputgenere").value + '",' +
        '"DataUscita":"' + document.getElementById("imputdatauscita").value + '",' +
        '"Descrizione":"' + document.getElementById("imputdescizione").value + '",' +
        '"SrcImg":"' + document.getElementById("imputimmagine").value + '",' +
        '"AltImg":"' + document.getElementById("imputdescimmagine").value + '",' +
        '"Durata":"' + document.getElementById("imputdurata").value + '"' +
        '}';
    
    xhr.send(data);
}
function post_proiezione() {
    let url = "./api/proiezioni";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            var data = JSON.parse(xhr.responseText);
            var status = data.status;
            if (status === "ok") {
                var proiezioni = data.proiezioni;
                updatehtml_proiezioni(proiezioni);
                document.getElementById("result_insert_proiezione").innerText = "inserimento avvenuto con successo"
            }
            else {
                document.getElementById("result_insert_proiezione").innerText = status;
            }
        }
    };

    let data = '{' +
        '"film":"' + document.getElementById("filmselector").value + '",' +
        '"sala":"' + document.getElementById("imputsala").value + '",' +
        '"Giorno":"' + document.getElementById("imputgiorno").value + '"'+
        '}';   

    xhr.send(data);
}


function gnereate_entry_film(entry) {
    result = "<tr class=filmentry><td>"
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>" +
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function gnereate_entry_proiezione(entry) {
    result= "<tr class=proiezioneentry><td>"
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        entry.titolofilm + "</td></tr>";
    return result;
}

function updatehtml_proiezioni(proiezioni) {
    var proiezionilist = '<tr><th>giorno</th><th>sala</th><th>film</th></tr>';
    for (entryindex in proiezioni) {
        var entry = proiezioni[entryindex];
        proiezionilist += gnereate_entry_proiezione(entry);
    }
    document.getElementById("proiezionilist").innerHTML = proiezionilist;
}
function updatehtml_film(films) {
    var filmlist = '<tr><th>titolo</th><th>genere</th><th>data uscita</th><th>durata</th></tr>';
    var filmoptions = "";
    for (entryindex in films) {
        var entry = films[entryindex];
        filmlist += gnereate_entry_film(entry);
        filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
    }
    document.getElementById("filmlist").innerHTML = filmlist;
    document.getElementById("filmselector").innerHTML = filmoptions;
}




richiedi_film();
richiedi_proiezioni();