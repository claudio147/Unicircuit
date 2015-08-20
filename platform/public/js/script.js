$(document).ready(function(){

    //Aktuelles Datum
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 
    today = dd+'/'+mm+'/'+yyyy;



    /*
    ******************* Adressliste
    */

    //Lokale Adressliste (mit Export-Funktion)
    $('#localAddress').DataTable({
        "scrollY":        "500px",
        "scrollCollapse": true,
        "paging":         false,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
        {
            extend: 'pdfHtml5', //PDF Funktion
            message: today,
            title: 'Projektadressliste',
        }
        ]
    });

    //Globale Adressliste (ohne Export-Funktion)
    $('#globalAddress').DataTable({
        "scrollY":        "300px",
        "scrollCollapse": true,
        "paging":         false,
    });

    //Styling Tabellen Buttons (PDF,Excel, CSV)
    $('.dt-button').addClass('btn btn-default');

    // Content Loader mit Ajax (Modals / Lightboxen)
    $('.btn_add').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"id":id},function(data){
            $('#newAddress').html(data);
        })
    });
    
    $('.btn_new').click(function(){
        var id= 0;
        $.post('../php/ajax.php', {"new":id},function(data){
            $('#newAddress').html(data);
        })
    });
    
    $('.btn_edit').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"edit":id},function(data){
            $('#editAddress').html(data);
        })
    });



    /*
    ******************* Timeline
    */

    //Ajax Loader für INhalt in Lightbox bei bearbeiten
    $('.btn_postEdit').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"postEdit":id},function(data){
            $('#editContainer').html(data);
        }) 
    });   

    //Anzeige von Vorschau- Bildern in Timneline
    window.onresize= dynamicResizer;
    window.onload= dynamicResizer;

        function dynamicResizer(){
            var cw = $('.col-sm-2').width();
            cw +=30;
            $('.imgLiquid').css({'height':cw+'px'});
        } 

    //Anzeige der Vorschaubilder mittig ohne Verzerrung
    $(".imgLiquidFill").imgLiquid({
    fill: true,
    horizontalAlign: "center",
    verticalAlign: "top"
    });

  
        




})

