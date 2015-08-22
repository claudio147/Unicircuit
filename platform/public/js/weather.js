$(document).ready(function(){
    
    
    $('.btn_pdf').click(function(){


        //Variablen die aus Session und DB kommen
        var plz = $('#zip').val();
        var country= $('#country').val();
        var date= $('#date').val();

        //Variablen für Koordinaten
        var longitude;
        var latitude;
        var lon;//Longitude als String
        var lat;//Latitude als String
        var cord;

        //Variablen für Wetterdaten
        var maxTemp;
        var minTemp;
        var humidity;
        var icon;
        

        //Request der Koordinate der PLZ und Land zurückliefert
        $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json',
                data:{
                        address: plz+country,
                        key: 'AIzaSyA7MONAVCxf543QLoLLcTGRNcSorGOi0zc',
                        language: 'de'
                }}).done(function(data){
                                longitude= data.results[0].geometry.location.lng;
                                latitude= data.results[0].geometry.location.lat;

                                lon=longitude.toString();
                                lat=latitude.toString();
                                cord= lat+', '+lon;
                        });


        /*
        ******* Wetterabfrage von Datum X
        */

        $.ajax({
                url:'https://api.worldweatheronline.com/free/v2/past-weather.ashx',
                data:{
                        key: '72e5fd218af8b7f1122673e5aa0ca',
                        q: +cord,
                        date: date,
                        tp: 24,
                        format: 'json'
                }
        }).done(function(data){
                maxTemp= data.data.weather[0].maxtempC;
                minTemp= data.data.weather[0].mintempC;
                humidity= data.data.weather[0].hourly[0].humidity;
                icon= data.data.weather[0].hourly[0].weatherDesc[0].value;

                location.href="sia.php?maxTemp="+escape(maxTemp)+".php&minTemp="+escape(minTemp);
                console.log(data);
                console.log(maxTemp);
                console.log(minTemp);
                console.log(humidity);
                console.log(icon);
               


        })
        
    });


})

