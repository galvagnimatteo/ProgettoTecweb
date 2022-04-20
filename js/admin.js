//var visible = {    
//    filmform: false,    
//    proiezioniform: false,
//    people_cast:false
//};

var areas = [
    {
        name: "film",
        element: "films",
        areaselector: "filmarea"
    },
    {
        name: "projection",
        element: "projections",
        areaselector: "projectionarea"
    },
    {
        name: "people_cast",
        element: "people_cast",
        areaselector: "people_cast_area"
    }
];
var forms = [
    {
        name:"film",
        area: "film",
        element:"insert_film",
        visible: true
    },
    {
        name: "projection",
        area: "projection",
        element: "insert_projection",
        visible: false
    },
    {
        name: "people_cast",
        area: "people_cast",
        element: "insert_people_cast",
        visible: false
    },
    //{
    //    name: "film_cast",
    //    area: "film",
    //    element: "insert_film_cast",
    //    visible: false
    //}
];

function change_context(context) {
    for (area in areas) {
        if (context === areas[area].name) {
            document.getElementById(areas[area].element).className = 'open';
            document.getElementById(areas[area].areaselector).className = 'activeoption';
        }
        else {
            document.getElementById(areas[area].element).className = 'closed';
            document.getElementById(areas[area].areaselector).className = 'inactiveoption';
        }
    }
    for (form in forms) {
        if (context === forms[form].area && forms[form].visible) {
            document.getElementById(forms[form].element).className = 'open';
        }
        else {
            document.getElementById(forms[form].element).className = 'closed';
        }
    }    
}

function toggle_form(toggledform) {
    for (form in forms) {
        if (toggledform === forms[form].name) {
            forms[form].visible = !forms[form].visible
            if (forms[form].visible) {

                document.getElementById(forms[form].element).className = 'open';
            }
            else {
                document.getElementById(forms[form].element).className = 'closed';
            }
        }
    }    
}
function request_film() {
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
function request_projection() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/proiezioni.php');
    request.send();
    request.onload = () => {        
        var data = JSON.parse(request.response);
        proiezioni = data.proiezioni;
        updatehtml_projection(proiezioni);
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
function post_projection() {
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
    result = "<tr class='entry " + rowtype + "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_film(' + entry.id + ');" class="deleteentry" >	&#128465;</button >' +
        '<button type = "button" onclick = "cast_edit(' + entry.id + ');" class="castedit" >cast</button >' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function gnereate_entry_projection(entry, index) {
    //rowtype = (index%2===0) ? "even" : "odd";
    result = "<tr class='entry "/* + rowtype */+ "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_projection(' + entry.id + ');" class="deleteentry" >	&#128465;</button >' +
    '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        entry.titolofilm + "</td></tr>";
    return result;
}

function updatehtml_projection(proiezioni) {
    var proiezionilist = '<tr class=odd><th></th><th>giorno</th><th>sala</th><th>film</th></tr>';
    for (entryindex in proiezioni) {
        var entry = proiezioni[entryindex];
        proiezionilist += gnereate_entry_projection(entry, entryindex);
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

function cast_edit(idfilm){
    var request = new XMLHttpRequest();
    request.open('GET', './api/cast_film.php?action=list&id='+idfilm);
    request.send();
    request.onload = () => {
        console.log(request.response);
        var data = JSON.parse(request.response);
        
    }
}



request_film();
request_projection();

setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
    request_film();
    request_projection(); }
    , 30000);