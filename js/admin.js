
films = null;
proiezioni = null;

function richiedi_film() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/films.php');
    request.send();
    request.onload = () => {
        var data = JSON.parse(request.response);
        var films = data.films;
        updatehtml_film(films);
    }
}
function richiedi_proiezioni() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/proiezioni.php');
    request.send();
    request.onload = () => {
        var data = JSON.parse(request.response);
        var proiezioni = data.proiezioni;
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
    return "<li class=filmentry>" + entry.titolo + "</li>";
}
function gnereate_entry_proiezione(entry) {
    result= "<tr class=proiezioneentry><td>"
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        films.find(function (value, index) { return value.id === entry.film }).titolo + "</td></tr>";
    return result;
}

function updatehtml_proiezioni(proiezioni) {
    var proiezionilist = '<tr><th>giorno</th><th>sala</th><th>film</th></tr>';
    for (entry in proiezioni) {
        proiezionilist += gnereate_entry_proiezione(entry);
    }
    document.getElementById("proiezionilist").innerHTML = proiezionilist;
}
function updatehtml_film(films) {
    var filmlist = "";
    var filmoptions = "";
    for (entry in films) {
        filmlist += gnereate_entry_film(entry);
        filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
    }
    document.getElementById("filmlist").innerHTML = filmlist;
    document.getElementById("filmselector").innerHTML = filmoptions;
}




richiedi_film();
richiedi_proiezioni();