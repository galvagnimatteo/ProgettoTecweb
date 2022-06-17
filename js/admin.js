var areas = [
    {
        nome: "film",
        elemento: "films",
        selettore_area: "filmarea"
    },
    {
        nome: "projection",
        elemento: "projections",
        selettore_area: "projectionarea"
    }
];
var forms = [
    {
        nome: "film",
        area: "film",
        elemento: "insert_film",
        output_stato: "result_insert_film",
        visible: false,
        fields: [
            {
                nome: "Titolo",
                elemento: "imputtitolo",
                condizione: function (value) {
                    return value.match(/^(([A-Za-z0-9\s]*)|({[A-Za-z0-9\s]*}))*$/);
                },
                messaggio_errore: "titolo non valido"
            },
            {
                nome: "Genere",
                elemento: "imputgenere",
                condizione: function (value) { return value.match(/^[a-zA-Z]*$/); },
                messaggio_errore: "genere puo contenere solo caratteri alfanumerici"
            },
            {
                nome: "Descrizione",
                elemento: "imputdescizione",
                condizione: function (value) { return value.match(/^([^{}]|{[^{}]*})*$/); },
                messaggio_errore: "errore sintassi graffe descrizione"
            },
            {
                nome: "DataUscita",
                elemento: "imputdatauscita",
                condizione: function (value) { return true; },
                messaggio_errore: ""
            },
            {
                nome: "SrcImg",
                elemento: "imputimmagine",
                condizione: function (value) { return value.match(/[^?#]*\.(gif|jpe?g|tiff?|png|webp|bmp)$/); },
                messaggio_errore: "l'immagine deve essere un file valido"

            },
            {
                nome: "CarouselImg",
                elemento: "imputcarousel",
                condizione: function (value) { return value.match(/[^?#]*\.(gif|jpe?g|tiff?|png|webp|bmp)$/); },
                messaggio_errore: "l'immagine deve essere un file valido"
            },
            {
                nome: "Durata",
                elemento: "imputdurata",
                condizione: function (value) { return value > 0; },
                messaggio_errore: "la durata deve essere un numero maggiore di 0"
            },
            {
                nome: "Attori",
                elemento: "imputattori",
                condizione: function (value) { return true; },
                messaggio_errore: ""
            },
            {
                nome: "Regista",
                elemento: "imputregista",
                condizione: function (value) { return true; },
                messaggio_errore: ""
            }
        ]
    },
    {
        nome: "projection",
        area: "projection",
        elemento: "insert_projection",
        output_stato: "result_insert_projection",
        visible: false,
        fields: [
            {
                nome: "film",
                elemento: "filmselector",
                condizione: function (value) { return true },
                messaggio_errore: ""
            },
            {
                nome: "sala",
                elemento: "imputsala",
                condizione: function (value) { return true },
                messaggio_errore: ""
            },
            {
                nome: "Giorno",
                elemento: "imputgiorno",
                condizione: function (value) { return true },
                messaggio_errore: ""
            },
            {
                nome: "Orario",
                elemento: "imputorario",
                condizione: function (value) { return true },
                messaggio_errore: ""
            }
        ]
    }
];

var api = {
    film: {
        imputform: forms[0],
        url: "./api/films.php",
        post: function () { api_post(this); },
        aggiorna_html: function (data) { aggiorna_html_film(data.films); },
    },
    proiezioni: {
        imputform: forms[1],
        url: "./api/proiezioni.php",
        post: function () { api_post(this); },
        aggiorna_html: function (data) { aggiorna_html_projection(data.proiezioni); },
    }
}

//
// Interfaccia
//

function cambia_contesto(contesto) {
    for (area in areas) {
        if (contesto === areas[area].nome) {
            document.getElementById(areas[area].elemento).className = 'open';
            //document.getElementById(areas[area].elemento).setAttribute('aria-hidden', 'false');
            document.getElementById(areas[area].selettore_area).className = 'activeoption';
            //window.location.href = areas[area].elemento;
        }
        else {
            document.getElementById(areas[area].elemento).className = 'closed';
            //document.getElementById(areas[area].elemento).setAttribute('aria-hidden', 'true');
            document.getElementById(areas[area].selettore_area).className = 'inactiveoption';
        }
    }
    for (form in forms) {
        if ((contesto === forms[form].area) && forms[form].visible) {
            //window.location.href = forms[form].elemento
            document.getElementById(forms[form].elemento).className = 'open';
            //document.getElementById(forms[form].elemento).setAttribute('aria-hidden', 'false');
        }
        else {
            document.getElementById(forms[form].elemento).className = 'closed';
            //document.getElementById(forms[form].elemento).setAttribute('aria-hidden', 'true');
        }
    }
}

function toggle_form(toggledform) {
    //console.log(toggledform);
    for (form in forms) {
        if (toggledform === forms[form].nome) {
            forms[form].visible = !forms[form].visible
            if (forms[form].visible) {
                document.getElementById(forms[form].elemento).className = 'open';
                //document.getElementById(forms[form].elemento).setAttribute('aria-hidden', 'false');
            }
            else {
                document.getElementById(forms[form].elemento).className = 'closed';
                //document.getElementById(forms[form].elemento).setAttribute('aria-hidden', 'true');
                if (forms[form].output_stato) {
                    document.getElementById(forms[form].output_stato).innerText = '';
                }
            }
        }
    }
}


//
// interfaccia API
//
function controlla_campi(form) {
    var fields = form.fields;
    var data = [];
    for (index in fields) {
        var field = fields[index];
        valore = document.getElementById(field.elemento).value;
        if (!field.condizione(valore)) {
            document.getElementById(form.output_stato).innerText = field.messaggio_errore;
            return false;
        }
        data[index] = {
            nome: field.nome,
            valore: valore
        }
    }
    return data;
}

function api_post(api) {
    try {
        let form = api.imputform;
        let data = controlla_campi(form);
        if (data) {
            let url = api.url;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", url);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            let urlEncodedData = "action=insert";
            for (index in data) {
                var campo = data[index];
                urlEncodedData += "&" + encodeURIComponent(campo.nome) + '=' + encodeURIComponent(campo.valore);
            }
            xhr.send(urlEncodedData);
            xhr.onload = function () {
                let data = JSON.parse(xhr.responseText);
                let status = data.status;
                if (status === "ok") {
                    api.aggiorna_html(data);
                    document.getElementById(form.output_stato).innerText = "inserimento avvenuto con successo"
                }
                else {
                    document.getElementById(form.output_stato).innerText = status;
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
        var data = JSON.parse(request.response);
        api.aggiorna_html(data);
    }
}
//
//Funzionalità aggiuntivie
//

function delete_film(id) {
    if (forms[0].visible) {
        return; //blocco cancellazione a form aperta
    }
    let url = api.film.url;

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
            aggiorna_html_film(films);
            document.getElementById("deletefilmstatus").firstChild.textContent = "eliminazione avvenuta con successo";

        }
        else {
            document.getElementById("deletefilmstatus").firstChild.textContent = status;
        }
        document.getElementById("deletefilmstatus").className = "open";
        //setTimeout(function () {
        //    document.getElementById("deletefilmstatus").className = "closed";
        //    document.getElementById("deletefilmstatus").firstChild.textContent = "";
        //}, 10000)
    };
}
function delete_projection(id) {
    if (forms[1].visible) {
        return; //blocco cancellazione a form aperta
    }
    let url = api.proiezioni.url;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);

    //xhr.setRequestHeader("Accept", "application/json");
    //xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send('action=delete&idproiezione=' + id);
    xhr.onload = function () {
        var data = JSON.parse(xhr.responseText);
        var status = data.status;
        if (status === "ok") {
            var proiezioni = data.proiezioni;
            aggiorna_html_projection(proiezioni);
            document.getElementById("deleteprojectionstatus").firstChild.textContent = "eliminazione avvenuta con successo";
        }
        else {
            document.getElementById("deleteprojectionstatus").firstChild.textContent = status;
        }
        document.getElementById("deleteprojectionstatus").className = "open";
        //setTimeout(function () {
        //    document.getElementById("deleteprojectionstatus").className = "closed";
        //    document.getElementById("deleteprojectionstatus").firstChild.textContent = "";
        //}, 10000)

    };
}

//
// Funzioni html
//
function aggiorna_html_projection(proiezioni) {
    document.getElementById("projectionlist").innerHTML = "";
    for (entryindex in proiezioni) {
        var entry = proiezioni[entryindex];
        document.getElementById("projectionlist").append(genera_entry_projection(entry));
        (function (id) {
            document.getElementById('delete_projection_'+id).onclick = function () {
                delete_projection(id);
            };
        })(entry.id);
    }
}
function aggiorna_html_film(films) {
    document.getElementById("filmlist").innerHTML = "";
    var filmoptions = "";
    for (entryindex in films) {
        var entry = films[entryindex];
        document.getElementById("filmlist").append(genera_entry_film(entry));
        (function (id) {
            document.getElementById("delete_film_"+id).onclick= function () {
                delete_film(id);
            };
        })(entry.id);        
        filmoptions = filmoptions + "<option value=" + entry.id + ">" + entry.titolo + "</option>";
    }    
    if (!forms[1].visible) {//evita che venga modificata la selezione dell' utente mentre la form è aperta
        document.getElementById("filmselector").innerHTML = filmoptions;
    }
}
function genera_entry_film(entry) {

    result = "<td class='entryfunctions'>" +

        '<a href="#deletefilmstatus" id="delete_film_' + entry.id +'" class="deleteentry nascondiTesto" role="button">Elimina</a >' +
        '</td ><td>'
        + entry.titolo + "</td><td>"
        + entry.genere + "</td><td>"
        + entry.datauscita + "</td><td>" +
        + entry.durata + "</td>";
    tr = document.createElement('tr');
    tr.className = 'entry';
    tr.innerHTML = genera_span_lingua(result);
    return tr;
}
function genera_entry_projection(entry) {
    result = "<td class='entryfunctions'>" +
        '<a href="#deleteprojectionstatus" id="delete_projection_'+entry.id+'" class="deleteentry  nascondiTesto" role="button" >Elimina</a >' +
        '</td ><td>'
        + entry.data + "</td><td>"
        + entry.numeroSala + "</td><td>"
        + entry.titolofilm + "</td><td>"
        + entry.orario + "</td><td>"
        + entry.durata + "</td>";

    tr=document.createElement('tr');
    tr.className = 'entry';
    tr.innerHTML = genera_span_lingua(result);
    return tr;
}

function genera_span_lingua(stringa){
    return stringa.replace("{", "<span lang='en'>").replace("}", "</span");
}


//
//Film
//

var film_area = areas[0];

//selettore
var selettore = document.getElementById(film_area.selettore_area);
selettore.onclick = function () { cambia_contesto('film'); };
selettore.firstChild.onclick = function () { cambia_contesto('film'); }

//Form
document.getElementById("apri_film").onclick = function () {
    toggle_form('film');
};
document.getElementById("chiudi_film").onclick = function () {
    toggle_form('film');
};
document.getElementById(api.film.imputform.elemento).onsubmit = function () {
    api.film.post();
    return false;//blocca caricamento pagina
};

//
//Proiezioni
//

var proiezioni_area = areas[1];

//selettore proiezioni

selettore = document.getElementById(proiezioni_area.selettore_area);
selettore.onclick = function () { cambia_contesto('projection'); }
selettore.firstChild.onclick = function () { cambia_contesto('projection'); }


//form proiezioni
document.getElementById("apri_proiezioni").onclick = function () { toggle_form('projection'); }

var form_proiezioni = document.getElementById(api.proiezioni.imputform.elemento);

document.getElementById("chiudi_proiezioni").onclick = function () {
    toggle_form('projection');
}
form_proiezioni.onsubmit = function () {
    api.proiezioni.post();
    return false;//blocca caricamento pagina
}








api_request(api.film);
api_request(api.proiezioni);

setInterval(() => {//aggiorna i dati e mantiene attiva la sessione
    api_request(api.film);
    api_request(api.proiezioni);
}
    , 30000);