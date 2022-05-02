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
        element: "insert_film",
        statuselement: "result_insert_film",
        visible: false
    },
    {
        name: "projection",
        area: "projection",
        element: "insert_projection",
        statuselement: "result_insert_projection",
        visible: false
    },
    {
        name: "people_cast",
        area: "people_cast",
        element: "insert_people_cast",
        statuselement: "result_insert_people_cast",
        visible: false
    },
    {
        name: "film_cast",
        area: "film",
        element: "edit_cast_film",
        statuselement: null,
        visible: false
    },
    {
        name: "projection_times",
        area: "projection",
        element: "projection_times",
        statuselement: null,
        visible: false
    }
];

var active_film = null;

function change_context(context) {
    for (area in areas) {
        if (context === areas[area].name) {
            document.getElementById(areas[area].element).className = 'open';
            document.getElementById(areas[area].areaselector).className = 'activeoption';
            //window.location.href = areas[area].element;
        }
        else {
            document.getElementById(areas[area].element).className = 'closed';
            document.getElementById(areas[area].areaselector).className = 'inactiveoption';
        }
    }
    for (form in forms) {
        if ((context === forms[form].area) && forms[form].visible) {
            //window.location.href = forms[form].element
            document.getElementById(forms[form].element).className = 'open';
        }
        else {
            document.getElementById(forms[form].element).className = 'closed';
        }
    }    
}

function toggle_form(toggledform) {
    //console.log(toggledform);
    for (form in forms) {
        if (toggledform === forms[form].name) {
            forms[form].visible = !forms[form].visible
            if (forms[form].visible) {
                //window.location.href = forms[form].element;
                document.getElementById(forms[form].element).className = 'open';
            }
            else {
                document.getElementById(forms[form].element).className = 'closed';
                if (forms[form].statuselement) {
                    document.getElementById(forms[form].statuselement).innerText = '';
                }
            }
        }
    }
}
function request_film() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/films.php');
    request.send();
    request.onload = () => {        
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
function request_people_cast() {
    var request = new XMLHttpRequest();
    request.open('GET', './api/cast_person.php');
    request.send();
    request.onload = () => {
        //console.log(request.response);
        var data = JSON.parse(request.response);
        cast_people = data.cast_people;
        updatehtml_people_cast(cast_people);
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
            updatehtml_projection(proiezioni);
            document.getElementById("result_insert_projection").innerText = "inserimento avvenuto con successo"
        }
        else {
            document.getElementById("result_insert_projection").innerText = status;
        }        
    };
}
function post_people_cast() {
    let url = "./api/cast_person.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');



    let data = {
        Nome: document.getElementById("imputNome").value,
        Cognome: document.getElementById("imputCognome").value,
        Ruolo: document.getElementById("imputRuolo").value,
        Lingua: document.getElementById("imputLingua").value
    };
    let urlEncodedData = "action=insert", name;
    for (name in data) {
        urlEncodedData += "&" + encodeURIComponent(name) + '=' + encodeURIComponent(data[name]);
    }
    xhr.send(urlEncodedData);

    xhr.onload = function () {
        //console.log(xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var cast_people = data.cast_people;
            updatehtml_people_cast(cast_people);
            document.getElementById("result_insert_people_cast").innerText = "inserimento avvenuto con successo"
        }
        else {
            document.getElementById("result_insert_people_cast").innerText = status;
        }
    };
}
function post_film_cast() {
    let url = "./api/cast_film.php?IDFilm="+active_film;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');



    let data = {
        IDFilm: active_film,
        IDCast: document.getElementById("selector_cast").value
    };
    let urlEncodedData = "action=add", name;
    for (name in data) {
        urlEncodedData += "&" + encodeURIComponent(name) + '=' + encodeURIComponent(data[name]);
    }
    xhr.send(urlEncodedData);

    xhr.onload = function () {
        //console.log(xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var cast = data.cast;
            generate_cast_recap(cast);            
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
            updatehtml_projection(proiezioni);            
        }        
    };
}
function delete_person_cast(id) {
    let url = "./api/cast_person.php";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


    xhr.send('action=delete&IDCast=' + id);
    xhr.onload = function () {
        //console.log(xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var cast_people = data.cast_people;
            updatehtml_people_cast(cast_people);            
        }        
    };
}
function delete_film_cast(person) {
    let url = "./api/cast_film.php?IDFilm=" + active_film;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


    xhr.send('action=remove&IDCast=' + person +'&IDFilm='+active_film);
    xhr.onload = function () {
        //console.log(xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var cast = data.cast;
            generate_cast_recap(cast);
        }
    };
}

function generate_entry_film(entry) {
    //rowtype = (index % 2 === 0) ? "even" : "odd";
    result = "<tr class='entry " /*+ rowtype*/ + "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_film(' + entry.id + ');" class="deleteentry nascondiTesto" >Elimina</button >' +
        '<a href="#edit_cast_film" onclick = "cast_edit(' + entry.id + ');" class="castedit" >cast</a>' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function generate_entry_projection(entry) {
    //rowtype = (index%2===0) ? "even" : "odd";
    result = "<tr class='entry "/* + rowtype */+ "' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_projection(' + entry.id + ');" class="deleteentry  nascondiTesto" >Elimina</button >' +
    '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        entry.titolofilm + "</td></tr>";
    return result;
}
var ruoli = {
    A: "attore",
    R: "regista"
}
function generate_entry_people_cast(entry) {
    //rowtype = (index%2===0) ? "even" : "odd";
    result = "<tr class='entry "/* + rowtype */ + "' ><td class='entryfunctions'>" +
        '<button type = "button" onclick = "delete_person_cast(' + entry.ID + ');" class="deleteentry  nascondiTesto" >Elimina</button >' +
        '</td ><td>'
        + entry.Nome + "</td><td>"
        + entry.Cognome + "</td><td>" +
        ruoli[entry.Ruolo] + "</td><td>" +
        ((entry.Lingua !== null) ? entry.Lingua:"--") + "</td></tr>";
    return result;
}

function generate_entry_film_cast(entry) {
    //rowtype = (index%2===0) ? "even" : "odd";
    result = "<tr class='entry "/* + rowtype */ + "' ><td class='entryfunctions'>" +
        '<button type = "button" onclick = "delete_film_cast(' + entry.ID + ');" class="deleteentry nascondiTesto" >Elimina</button >' +
        '</td ><td>'
        + entry.Nome + "</td><td>"
        + entry.Cognome + "</td><td>" +
        ruoli[entry.Ruolo] + "</td><td>" +
        ((entry.Lingua !== null) ? entry.Lingua : "--") + "</td></tr>";
    return result;
}

function updatehtml_projection(proiezioni) {
    var proiezionilist = '';
    for (entryindex in proiezioni) {
        var entry = proiezioni[entryindex];
        proiezionilist += generate_entry_projection(entry);
    }
    document.getElementById("projectionlist").innerHTML = proiezionilist;
}
function updatehtml_film(films) {
    var filmlist = '';
    var filmoptions = "";
    for (entryindex in films) {
        var entry = films[entryindex];
        filmlist += generate_entry_film(entry);
        filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
    }
    document.getElementById("filmlist").innerHTML = filmlist;
    document.getElementById("filmselector").innerHTML = filmoptions;
}
function updatehtml_people_cast(people) {
    var people_cast_list = '';
    cast_selector = "";
    for (entryindex in people) {
        var entry = people[entryindex];
        people_cast_list += generate_entry_people_cast(entry);
        cast_selector += "<option value='" + entry.ID + "'>" + entry.Nome+" "+entry.Cognome+"</option>";
    }
    document.getElementById("people_cast_list").innerHTML = people_cast_list;
    document.getElementById("selector_cast").innerHTML = cast_selector;
}

function cast_edit(idfilm) {
    if (forms[0].active) {
        toggle_form("film");
    }
    if (!active_film) {
        toggle_form("film_cast");        
    }
    active_film = idfilm;
    var request = new XMLHttpRequest();
    request.open('GET', './api/cast_film.php?action=list&IDFilm='+idfilm);
    request.send();
    request.onload = () => {
        //console.log(request.response);
        var data = JSON.parse(request.response);
        var cast = data.cast;
        generate_cast_recap(cast);
    }
}
function projection_edit_times(idprojection) {
    if (forms[1].active) {
        toggle_form("projection");
    }
    if (!active_projection) {
        toggle_form("projection_times");
    }
    active_projection = idprojection;
    var request = new XMLHttpRequest();
    request.open('GET', './api/cast_film.php?action=list&IDFilm=' + idfilm);
    request.send();
    request.onload = () => {
        //console.log(request.response);
        var data = JSON.parse(request.response);
        var times = data.times;
        generate_projection_times_recap(times);
    }
}
function generate_cast_recap(cast) {
    var film_cast_recap = '';
    for (entryindex in cast) {
        var entry = cast[entryindex];
        film_cast_recap += generate_entry_film_cast(entry);
    }
    document.getElementById("cast_film_recap").innerHTML = film_cast_recap;
}
function generate_projection_times_recap(times) {
    var projection_times_recap = '';
    for (entryindex in times) {
        var entry = times[entryindex];
        projection_times_recap += '<li><button type="button" onclick="delete_film(8); " class="deleteentry nascondiTesto">Elimina</button>+'
    }
    document.getElementById("cast_film_recap").innerHTML = film_cast_recap;
}

document.getElementById("filmarea").onclick = function () { change_context('film'); }
document.getElementById("projectionarea").onclick = function () { change_context('projection'); }
document.getElementById("people_cast_area").onclick = function () { change_context('people_cast'); }
document.getElementById("filmarea").firstChild.onclick = function () { change_context('film'); }
document.getElementById("projectionarea").firstChild.onclick = function () { change_context('projection'); }
document.getElementById("people_cast_area").firstChild.onclick = function () { change_context('people_cast'); }








request_film();
request_projection();
request_people_cast();

setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
    request_film();
    request_projection();}
    , 30000);