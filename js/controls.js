function registerControls(){

    let username = document.getElementById("username").value;
    let name = document.getElementById("name").value;
    let surname = document.getElementById("surname").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let password_confirm = document.getElementById("password_confirm").value;

    let usernameRegex = /^[a-zA-Z0-9]+$/;

    if(!username.match(usernameRegex)){

        document.getElementById("errorMessage").innerHTML = "L'username deve contenere solo lettere e numeri.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    let nameRegrex = /^[a-zA-Z]+$/;

    if(!name.match(nameRegrex)){

        document.getElementById("errorMessage").innerHTML = "Il nome può essere composto da sole lettere.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    if(!surname.match(nameRegrex)){

        document.getElementById("errorMessage").innerHTML = "Il cognome può essere composto da sole lettere.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    if(!email.includes("@")){ //non provateci neanche con espressioni regolari, l'unico modo sensato di validare una mail è mandare una mail con un codice e aspettare che l'utente lo inserisca

        document.getElementById("errorMessage").innerHTML = "Chiocciola (@) mancante nell'email.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    if(password.length < 8 || password.includes(" ")){ //tutti i caratteri vanno bene nella password tranne lo spazio, basta che ce ne siano 8. il resto della sicurezza è affidata all'utente, non ha senso imporre vincoli assurdi per un account di un cinema

        document.getElementById("errorMessage").innerHTML = "La password deve essere di almeno 8 caratteri e non può contenere spazi.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    if(password_confirm != password){

        document.getElementById("errorMessage").innerHTML = "La conferma password è diversa dalla password inserita.";
        document.getElementById("errorMessage").hidden = false;
        return false;

    }

    return true;

}