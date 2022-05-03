var areas = [
    {
        name: "film",
        element: "films",
        area_selector: "filmarea"
    },
    {
        name: "projection",
        element: "projections",
        area_selector: "projectionarea"
    }
];
var forms = [
    {
        name:"film",
        area: "film",
        element: "insert_film",
        status_element: "result_insert_film",
        visible: false
    },
    {
        name: "projection",
        area: "projection",
        element: "insert_projection",
        status_element: "result_insert_projection",
        visible: false
    }
];
function change_context(context) {
    for (area in areas) {
        if (context === areas[area].name) {
            document.getElementById(areas[area].element).className = 'open';
            document.getElementById(areas[area].area_selector).className = 'activeoption';
            //window.location.href = areas[area].element;
        }
        else {
            document.getElementById(areas[area].element).className = 'closed';
            document.getElementById(areas[area].area_selector).className = 'inactiveoption';
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
                    document.getElementById(forms[form].status_element).innerText = '';
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
        console.log(request.response);
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
        CarouselImg: document.getElementById("imputcarousel").value,
        Attori: document.getElementById("imputattori").value,
        Regista: document.getElementById("imputregista").value
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
        Giorno: document.getElementById("imputgiorno").value,
        Orario: document.getElementById("imputorario").value
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


function generate_entry_film(entry) {
    
    result = "<tr class='entry' ><td class='entryfunctions'>"+
        '<button type = "button" onclick = "delete_film(' + entry.id + ');" class="deleteentry nascondiTesto" >Elimina</button >' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function generate_entry_projection(entry) {
    result = "<tr class='entry' ><td class='entryfunctions'>" +
        '<button type = "button" onclick = "delete_projection(' + entry.id + ');" class="deleteentry  nascondiTesto" >Elimina</button >' +
        '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>" +
        + entry.titolofilm + "</td><td>" +
        entry.orario + "</td></tr>";
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



//for (index in areas) {
//    area = areas[index];
//    console.log(area);
//    var handler = (function (area_name) {
//        return function () { console.log(area_name); change_context(area_name); } }(area.name));
//    document.getElementById(area.area_selector).onclic = handler();
//}
    document.getElementById("filmarea").onclick = function () { change_context('film'); }
    document.getElementById("projectionarea").onclick = function () { change_context('projection'); }
    document.getElementById("filmarea").firstChild.onclick = function () { change_context('film'); }
    document.getElementById("projectionarea").firstChild.onclick = function () { change_context('projection'); }

    request_film();
    request_projection();

    setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
        request_film();
        request_projection();
    }
        , 30000);