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
        name: "film",
        area: "film",
        element: "insert_film",
        status_element: "result_insert_film",
        visible: false,
        fields: [
            {
                name:"Titolo",
                element:"imputtitolo",
                condition: function (value) {return value.match("/^[a-zA-Z0-9]/");
                },
                error_message:"titolo puo contenere solo caratteri alfanumerici e spazi"
            },
            {
                name: "Genere",
                element: "imputgenere",
                condition: function (value) { return value.match("/^[a-zA-Z]/"); },
                error_message:"genere puo contenere solo caratteri alfanumerici"
            },
            {
                name: "Descrizione",
                element: "imputdescizione",
                condition: function (value) { return true; },
                error_message: ""
            },
            {
                name: "DataUscita",
                element: "imputdatauscita",
                condition: function (value) { return true; },
                error_message:""
            },
            {
                name: "SrcImg",
                element: "imputimmagine",
                condition: function (value) { return value.match("/^https?:\/\/(?:[a-z0-9\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png|bmp)$/"); },
                error_message:"l'immagine deve essere un url valido"
            },
            {
                name: "CarouselImg",
                element: "imputcarousel",
                condition: function (value) { return value.match("/^https?:\/\/(?:[a-z0-9\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png|bmp)$/"); },
                error_message: "l'immagine deve essere un url valido"
            },
            {
                name: "Durata",
                element: "imputdurata",
                condition: function (value) { return value > 0; },
                error_message: "la durata deve essere un numero maggiore di 0"
            },
            {
                name: "Attori",
                element: "imputattori",
                condition: function (value) { return true; },
                error_message:""
            },
            {
                name: "Regista",
                element: "imputregista",
                condition: function (value) { return true; },
                error_message:""
            }
        ]
    },
    {
        name: "projection",
        area: "projection",
        element: "insert_projection",
        status_element: "result_insert_projection",
        visible: false,
        fields: [
            {
                name: "film",
                element: "filmselector",
                condition: function (value) { return true },
                error_message: ""
            },
            {
                name: "sala",
                element: "imputsala",
                condition: function (value) { return true },
                error_message: ""
            },
            {
                name: "Giorno",
                element: "imputgiorno",
                condition: function (value) { return true },
                error_message: ""
            },
            {
                name: "Orario",
                element: "imputorario",
                condition: function (value) { return true },
                error_message: ""
            }
        ]
    }
];

var api = {
    film:{
        imputform:forms[0],
        url: "./api/films.php",
        post: function () { api_post(this); return false; },
        updatehtml: function (data) { updatehtml_film(data.films); },
    },
    proiezioni:{
        imputform: forms[1],
        url: "./api/proiezioni.php",
        post: function () { api_post(this); return false;},
        updatehtml: function (data) { updatehtml_projection(data.proiezioni); },
    }
}
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

function check_fields(form) {
    var fields = form.fields;
    var data = [];
    for (index in fields) {
        var field = fields[index];
        value = document.getElementById(field.element).value;
        if (!field.condition(value)) {
            form.status_element.innerText = field.error_message;
            //return false;
        }
        data[index] = {
            name: field.name,
            value: value
        }

    }
    return data;
}
function api_post(api) {
    try {
        let form = api.imputform;
        let data = check_fields(form);
        if (data) {
            let url = api.url;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", url);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            let urlEncodedData = "action=insert";
            for (index in data) {
                var field = data[index];
                urlEncodedData += "&" + encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value);
            }
            xhr.send(urlEncodedData);
            xhr.onload = function () {
                console.log(xhr.responseText);
                let data = JSON.parse(xhr.responseText);
                let status = data.status;
                if (status === "ok") {
                    api.updatehtml(data);
                    document.getElementById(form.status_element).innerText = "inserimento avvenuto con successo"
                }
                else {
                    document.getElementById(form.status_element).innerText = status;
                }
            };
        }
    }
    catch (e) {
        console.log(e);
    }
}

function api_request(api) {
    var request = new XMLHttpRequest();
    request.open('GET', api.url);
    request.send();
    request.onload = () => {
        console.log(request.response);
        var data = JSON.parse(request.response);
        api.updatehtml(data);
    }
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
        console.log(xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var films = data.films;
            updatehtml_film(films);
            document.getElementById("deletefilmstatus").firstChild.textContent = "eliminazione avvenuta con successo";
        }
        else {
            document.getElementById("deletefilmstatus").firstChild.textContent = status;
        }
        document.getElementById("deletefilmstatus").className = "open";
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
            document.getElementById("deleteprojectionstatus").firstChild.textContent = "eliminazione avvenuta con successo";
        }
        else {
            document.getElementById("deleteprojectionstatus").firstChild.textContent = status;
        }
        document.getElementById("deleteprojectionstatus").className = "open";
        setTimeout(function () {
            document.getElementById("deleteprojectionstatus").className = "closed";
            document.getElementById("deleteprojectionstatus").firstChild.textContent = "";
        },5000)
    };
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
        if (!forms[1].visible) {//evita che venga modificata la selezione dell' utente mentre la form è aperta
            filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
        }        
    }
    document.getElementById("filmlist").innerHTML = filmlist;
    document.getElementById("filmselector").innerHTML = filmoptions;
}

function generate_entry_film(entry) {
    
    result = "<tr class='entry' ><td class='entryfunctions'>"+
        '<a href="#deletefilmstatus" onclick = "delete_film(' + entry.id + ');" class="deleteentry nascondiTesto" >Elimina</a >' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata+"</td></tr>";
    return result;
}
function generate_entry_projection(entry) {
    result = "<tr class='entry' ><td class='entryfunctions'>" +
        '<a href="#deleteprojectionstatus" onclick = "delete_projection(' + entry.id + ');" class="deleteentry  nascondiTesto" >Elimina</a >' +
        '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>"
        + entry.titolofilm + "</td><td>" +
        + entry.orario + "</td><td>" +
        entry.durata + "</td></tr>";
    return result;
}



document.getElementById(api.film.imputform.element).onsubmit = function () {
    api.film.post();
    return false;//blocca caricamento pagina
}
document.getElementById(api.proiezioni.imputform.element).onsubmit = function () {
    api.proiezioni.post();
    return false;//blocca caricamento pagina
}
  

    document.getElementById("filmarea").onclick = function () { change_context('film'); }
    document.getElementById("projectionarea").onclick = function () { change_context('projection'); }
    document.getElementById("filmarea").firstChild.onclick = function () { change_context('film'); }
    document.getElementById("projectionarea").firstChild.onclick = function () { change_context('projection'); }

    api_request(api.film);
    api_request(api.proiezioni);

    setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
        api_request(api.film);
        api_request(api.proiezioni);
    }
        , 30000);