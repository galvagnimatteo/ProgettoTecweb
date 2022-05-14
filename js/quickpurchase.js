document.getElementById("selectDate").className = "disabled";
document.getElementById("selectHour").className = "disabled";

document.getElementById("selectFilm").addEventListener("change", populateQuickpurchaseDate);
document.getElementById("selectDate").addEventListener("change", populateQuickpurchaseHour);

document.getElementById("selectDate").addEventListener("mousedown", checkIfDateDisabled);
document.getElementById("selectHour").addEventListener("mousedown", checkIfHourDisabled);

function checkIfDateDisabled(){

    let isDisabled = document.getElementById("selectDate").className == "disabled";

    if(isDisabled){
        document.getElementById("quickpurchaseError").className = "error";
        document.getElementById("quickpurchaseError").innerHTML = "Seleziona prima il film che desideri.";
    }

}

function checkIfHourDisabled(){

    let isDisabled = document.getElementById("selectHour").className == "disabled";
    let isDisabledDate = document.getElementById("selectDate").className == "disabled";

    if(isDisabled && isDisabledDate){
        document.getElementById("quickpurchaseError").className = "error";
        document.getElementById("quickpurchaseError").innerHTML = "Seleziona prima il film e poi la data che desideri.";
    }else if (isDisabled) {
        document.getElementById("quickpurchaseError").className = "error";
        document.getElementById("quickpurchaseError").innerHTML = "Seleziona prima la data che desideri.";
    }

}

function populateQuickpurchaseDate(){

    let idfilm = document.getElementById("selectFilm").value;

    if(idfilm != "scelta"){

        $.ajax({
            url: "api/dateJSON.php",
            type: "post",
            dataType: 'json',
            data: {IDfilm: idfilm},
            success: function(result){

                document.getElementById("selectDate").innerHTML = '<option value="scelta">Scegli una data</option>' + result.datesHTML;
                document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>';

                document.getElementById("selectDate").className = "selectable";
                document.getElementById("selectHour").className = "disabled";


            }

        });

    }else{
        document.getElementById("selectDate").innerHTML = '<option value="scelta">Scegli una data</option>';
        document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>';

        document.getElementById("selectDate").className = "disabled";
        document.getElementById("selectHour").className = "disabled";

    }

}


function populateQuickpurchaseHour(){

    let date = document.getElementById("selectDate").value;
    let idfilm = document.getElementById("selectFilm").value;

    if(date != "scelta"){

        $.ajax({
            url: "api/oreJSON.php",
            type: "post",
            dataType: 'json',
            data: {IDfilm: idfilm, Date: date},
            success: function(result){

                document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>' + result.hoursHTML;

                document.getElementById("selectHour").className = "disabled";
                document.getElementById("selectHour").className = "selectable";

            }

        });

    }else{
        document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>';
        document.getElementById("selectHour").className = "disabled";

    }

}
