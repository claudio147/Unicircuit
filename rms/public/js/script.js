// DOCUMENT READY      
$(document).ready(function(){
    
    $('#table-user-list').DataTable({
        "scrollY":        "450px",
        "scrollCollapse": true,
        "paging":         false,
        language: {
            search: "Suche",
            zeroRecords: "Keine User gefunden",
            info: "Anzahl User: _TOTAL_",
            infoFiltered: "(User gesamt: _MAX_)"
        },
    });
    
  //Lokale Adressliste (mit Export-Funktion)
    /*$('#userverwaltung').DataTable({
        "scrollY":        "450px",
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
        {
            extend: 'pdfHtml5', //PDF Funktion
            message: today,
            title: 'Projektadressliste',
        }
        ]
    });*/ 
    
    
    
    
})