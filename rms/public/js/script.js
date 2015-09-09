// DOCUMENT READY
$(document).ready(function(){

    $('#table-user-list').DataTable({
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        language: {
            search: "Suche",
            zeroRecords: "Keine User gefunden",
            info: "Anzahl User: _TOTAL_",
            infoFiltered: "(User gesamt: _MAX_)"
        },
    });


    //Adressliste (mit Export-Funktion)
    $('#addresslist-rms').DataTable({
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        fixedHeader: false,
        dom: 'Bfrtip',
        ordering: true,
        language: {
            search: "Suche",
            zeroRecords: "Keine Adressen gefunden",
            info: "Anzahl Adressen: _TOTAL_",
            infoFiltered: "(Adressen gesamt: _MAX_)"
        },
        buttons: [
            'excelHtml5',
            'csvHtml5',
        ]
    });


    //Ajax Loader für Inhalt in Lightbox bei bearbeiten
    $('.btn-user-details').click(function(){
        var id= $(this).val();

        $.post('../php/ajax-rms.php', {"showUserDetails":id},function(data){
            $('#userDetails').html(data);
        }) 
    });
    
    //Ajax Loader für Inhalt in Lightbox bei bearbeiten
    $('.btn_add').click(function(){
        var id= $(this).val();

        $.post('../php/ajax-rms.php', {"showAddressDetails":id},function(data){
            $('#address-ajax').html(data);
        }) 
    });





})