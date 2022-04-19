var visible = {    
    filmform: false,    
    proiezioniform: false
};
function changecontext(context){
    if (context === "films") {
        document.getElementById('insert_projection').className = 'closed'
        document.getElementById('projections').className = 'closed'
        document.getElementById('films').className = 'open'
        document.getElementById('filmarea').className = 'activeoption'
        document.getElementById('projectionarea').className = 'inactiveoption'
        if (visible.filmform) {
            document.getElementById('insert_film').className = 'open'
        }
        else {
            document.getElementById('insert_film').className = 'closed'
        }
    }
    else {
        document.getElementById('insert_film').className = 'closed'
        document.getElementById('films').className = 'closed'
        document.getElementById('projections').className = 'open'
        document.getElementById('projectionarea').className = 'activeoption'
        document.getElementById('filmarea').className = 'inactiveoption'
        if (visible.proiezioniform) {
            document.getElementById('insert_projection').className = 'open'
        }
        else {
            document.getElementById('insert_projection').className = 'closed'
        }
    }
}

function toggleform(form){
    if (form === "film") {
        visible.filmform = !visible.filmform;
        if (visible.filmform) {
            document.getElementById('insert_film').className = 'open'
        }
        else {
            document.getElementById('insert_film').className = 'closed'
        }
    }
    else {
        visible.proiezioniform = !visible.proiezioniform;
        if (visible.proiezioniform) {
            document.getElementById('insert_projection').className = 'open'
        }
        else {
            document.getElementById('insert_projection').className = 'closed'
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
        console.log(request.response);
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
        updatehtml_proiezioni(proiezioni);
    }
}
function post_film() {
    let url = "./api/films.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    
    let data = {
        //action:'insert',
        Titolo : document.getElementById("imputtitolo").value,
        Genere: document.getElementById("imputgenere").value,
        Descrizione: document.getElementById("imputdescizione").value,
        DataUscita : document.getElementById("imputdatauscita").value,
        SrcImg : document.getElementById("imputimmagine").value,
        Durata: document.getElementById("imputdurata").value,
        CarouselImg: document.getElementById("imputcarousel").value
    };
    let urlEncodedData = "action=insert", name;
    for (name in data) {
        urlEncodedData += "&" + encodeURIComponent(name) + '=' + encodeURIComponent(data[name]);
    }    
    xhr.send(urlEncodedData);
    xhr.onload = function () {        
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
    };
}
function post_proiezione() {
    let url = "./api/proiezioni.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    

    let data = {
        film: document.getElementById("filmselector").value,
        sala:document.getElementById("imputsala").value,
        Giorno:document.getElementById("imputgiorno").value
    };
    let urlEncodedData = "action=insert", name;
    for (name in data) {
        urlEncodedData += "&" + encodeURIComponent(name) + '=' + encodeURIComponent(data[name]);
    }    
    xhr.send(urlEncodedData);

    xhr.onload = function () {                
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
    };
}
function delete_film(id) {
    let url = "./api/films.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send('action=delete&idfilm=' + id);
    xhr.onload = function () {
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var films = data.films;
            updatehtml_film(films);
            //document.getElementById("result_insert_film").innerText = "inserimento avvenuto con successo"
        }
        else {
            //document.getElementById("result_insert_film").innerText = status;
        }        
    };
}
function delete_projection(id) {
    let url = "./api/proiezioni.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');   

    
    xhr.send('action=delete&idproiezione='+id);
    xhr.onload  = function () {
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var proiezioni = data.proiezioni;
            updatehtml_proiezioni(proiezioni);
            //document.getElementById("result_insert_proiezione").innerText = "inserimento avvenuto con successo"
        }
        else {
            //document.getElementById("result_insert_proiezione").innerText = status;
        }        
    };
}

function gnereate_entry_film(entry, index) {
    rowtype = (index % 2 === 0) ? "even" : "odd";
    result = "<tr class='filmentry " + rowtype + "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_film(' + entry.id + ');" class="deleteentry" >	&#128465;</button >' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function gnereate_entry_proiezione(entry, index) {
    rowtype = (index%2===0) ? "even" : "odd";
    result = "<tr class='projectionentry " + rowtype + "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_projection(' + entry.id + ');" class="deleteentry" >	&#128465;</button >'+
    '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        entry.titolofilm + "</td></tr>";
    return result;
}

function updatehtml_proiezioni(proiezioni) {
    var proiezionilist = '<tr class=odd><th></th><th>giorno</th><th>sala</th><th>film</th></tr>';
    for (entryindex in proiezioni) {
        var entry = proiezioni[entryindex];
        proiezionilist += gnereate_entry_proiezione(entry, entryindex);
    }
    document.getElementById("projectionlist").innerHTML = proiezionilist;
}
function updatehtml_film(films) {
    var filmlist = '<tr class=odd><th></th><th>titolo</th><th>genere</th><th>data uscita</th><th>durata</th></tr>';
    var filmoptions = "";
    for (entryindex in films) {
        var entry = films[entryindex];
        filmlist += gnereate_entry_film(entry, entryindex);
        filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
    }
    document.getElementById("filmlist").innerHTML = filmlist;
    document.getElementById("filmselector").innerHTML = filmoptions;
}




richiedi_film();
richiedi_proiezioni();

setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
    richiedi_film();
    richiedi_proiezioni(); }
    , 30000);