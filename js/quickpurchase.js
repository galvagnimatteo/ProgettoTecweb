function populateQuickpurchaseDate(selectObject){

    let idfilm = selectObject.value;

    if(idfilm != "scelta"){

        $.ajax({
            url: "utils/returnDates.php",
            type: "post",
            dataType: 'json',
            data: {IDfilm: idfilm},
            success: function(result){

                document.getElementById("selectDate").innerHTML = '<option value="scelta">Scegli una data</option>' + result.datesHTML;
                document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>';

            }
            
        });

    }

}


function populateQuickpurchaseHour(selectObject){

    let date = selectObject.value;
    let idfilm = document.getElementById("selectFilm").value;

    if(date != "scelta"){

        $.ajax({
            url: "utils/returnHours.php",
            type: "post",
            dataType: 'json',
            data: {IDfilm: idfilm, Date: date},
            success: function(result){

                document.getElementById("selectHour").innerHTML = '<option value="scelta">Scegli un orario</option>' + result.hoursHTML;

            }

        });

    }

}
