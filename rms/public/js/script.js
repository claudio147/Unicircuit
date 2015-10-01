/*
*   Redaktionssystem
*   «script.js / Custom JS für Redaktionssystem»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

// DOCUMENT READY
$(document).ready(function(){

    $('#table-user-list').DataTable({
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        fixedHeader: true,
        responsive: {
        details: false
        },
        language: {
            search: "Suche",
            zeroRecords: "Keine User gefunden",
            info: "Anzahl User: _TOTAL_",
            infoFiltered: "(User gesamt: _MAX_)",
            infoEmpty: "Keine Einträge gefunden."
        }
    });

    //Adressliste (mit Export-Funktion)
    $('#addresslist-rms').DataTable({
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        fixedHeader: true,
        dom: 'Bfrtip',
        ordering: true,
        responsive: {
        details: false
        },
        language: {
            search: "Suche",
            zeroRecords: "Keine Adressen gefunden",
            info: "Anzahl Adressen: _TOTAL_",
            infoFiltered: "(Adressen gesamt: _MAX_)",
            infoEmpty: "Keine Einträge gefunden."
        },
        buttons: [
            'excelHtml5',
            'csvHtml5',
        ]
    });
    
    //Scrollbar in Tabellen (Custom)
    $('.dataTables_scrollBody').niceScroll({
            cursorcolor:"#373d42",
        });

    //Styling Tabellen Buttons (PDF,Excel, CSV)
    $('.dt-button').addClass('btn btn-default btn-xs');
    
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
    
    tinymce.init({
        selector:'textarea#2',
        plugins: 'code link image lists preview table',
        skin: 'custom',
        width: 600,
        min_height: 300,
    });
})