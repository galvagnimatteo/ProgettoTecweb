function registerControls(){

    let username = document.getElementById("username").value;
    let name = document.getElementById("name").value;
    let surname = document.getElementById("surname").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let password_confirm = document.getElementById("password_confirm").value;

    let usernameRegex = /^[a-zA-Z0-9]+$/;

    if(!username.match(usernameRegex)){

        //display error
        return false;

    }

    let nameRegrex = /^[a-zA-Z]+$/;

    if(!name.match(nameRegrex)){

        //display error
        return false;

    }

    if(!surname.match(nameRegrex)){

        //display err;
        return false;

    }

    if(!email.includes("@")){ //non provateci neanche con espressioni regolari, l'unico modo sensato di validare una mail è mandare una mail con un codice e aspettare che l'utente lo inserisca

        //err
        return false;

    }

    if(password.length < 8 || password.includes(" ")){ //tutti i caratteri vanno bene nella password tranne lo spazio, basta che ce ne siano 8. il resto della sicurezza è affidata all'utente, non ha senso imporre vincoli assurdi per un account di un cinema

        //err
        return false;

    }

    if(password_confirm != password){
        //err
        return false;
    }

    return true;

}

function loginControls(){

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let usernameRegex = /^[a-zA-Z0-9]+$/;

    if(!username.match(usernameRegex)){

        //display error
        return false;

    }

    if(password.length < 8 || password.includes(" ")){

        //err
        return false;

    }

    return true;

}