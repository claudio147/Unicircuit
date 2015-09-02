/*
*** Timeline
*/
window.onresize= dynamicResizer;
window.onload= dynamicResizer;

function dynamicResizer(){
    var cw = $('.col-sm-2').width();
    cw +=30;
    $('.imgLiquid').css({'height':cw+'px'});
    console.log(cw);
} 


// DOCUMENT READY
        
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
        "scrollY":        "450px",
        "scrollCollapse": true,
        "paging":         false,
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
    });

    //Globale Adressliste (ohne Export-Funktion)
    $('#globalAddress').DataTable({
        "scrollY":        "290px",
        "scrollCollapse": true,
        "paging":         false,
        ordering: true,
        fixedHeader: true,
        responsive: true,
        language: {
            search: "Suche",
            zeroRecords: "Keine Adressen gefunden",
            info: "Anzahl Adressen: _TOTAL_",
            infoFiltered: "(Adressen gesamt: _MAX_)"
        },
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
    ******************* Projektverwaltung *******
    */
   
    //Ajax Loader für Inhalt in Lightbox bei bearbeiten
    $('.btn_postEdit_pv').click(function(){
        var id= $(this).val();
        $.post('../php/ajax_pv.php', {"postEdit":id},function(data){
            $('#editContainer_pv').html(data);
        }) 
        
        
    });

   

    /*
    ******************* Timeline *******
    */

    //Ajax Loader für Inhalt in Lightbox bei bearbeiten
    $('.btn_postEdit').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"postEdit":id},function(data){
            $('#editContainer').html(data);
        }) 
    });   



    //Anzeige von Vorschau- Bildern in Timneline
    

        function dynamicResizer(){
            var cw = $('.col-sm-2').width();
            cw +=30;
            $('.imgLiquid').css({'height':cw+'px'});
        } 

        //window.onresize= dynamicResizer, dynamicResizerEvent;
        //window.onload= dynamicResizer, dynamicResizerEvent;


    //Anzeige der Vorschaubilder mittig ohne Verzerrung
    $(".imgLiquidFill").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "top"
    });

  
    /*
     ******************** MAINPAGE ******
     */ 
    var el = document.getElementById('home');
    el.onclick = showFoo;


    function showFoo() {
        document.getElementById("homeli").className= "active";
        document.getElementById("timeline_li").className = "";
    }
    
    $('#termingroup').click(function(){
        document.getElementById("home_li").className = "";
        document.getElementById("timeline_li").className = "";
        document.getElementById("addresslist_li").className = "";
        document.getElementById("gallery_li").className = "";
        document.getElementById("contact_li").className = "";
        document.getElementById("sia_li").className = "";
    });
    
    $('#timeline').click(function(){
        document.getElementById("home_li").className = "";
        document.getElementById("timeline_li").classList.add("active");
    });
 

   

    /*
    ***************** GALLERY ************
     */
    
    function myImgToolbarCustDisplay($elements, item, data) {
        //Ermittlung Usertyp und anzeige von Löschfunktion bei Architekt
        $.post('../php/ajax.php', {"getUserTyp":'1'},function(usertyp){
                if(usertyp==1){
                    // Custom Element mit Lösch-Button
                    $elements.html('<i id="delIMG" data-img="'+item.GetID()+'" class="fa fa-trash-o fa-2x"></i>');
                    
                    //Funktion die Bilder löscht (in DB und in Verzeichnis
                    $('#delIMG').click(function(){
                        var id= $(this).attr('data-img');

                        $.post('../php/ajax.php', {"delIMG":id},function(status){
                            //Neuladen der Galerie-Seite
                            window.location.replace("../php/index.php?id=7&status=4");
                        }) 
                    })
                }
            }) 
        
        
        
    }
    
    
    
    //Darstellung Bildergalerie (Nanogallery- Plugin)
    $("#nanoGallery3").nanoGallery({
        colorScheme: 'none',
        thumbnailHoverEffect: [{ name: 'labelAppear75', duration: 300 }],
        theme: 'light',
        thumbnailWidth: 'auto',
        thumbnailHeight: 200,
        i18n: { thumbnailImageDescription: '<i class="fa fa-search-plus fa-5x"></i>'},
        thumbnailLabel: { display: true, position: 'overImageOnMiddle', align: 'center', hideIcons: true, },
        viewerToolbar: {
            autoMinimize: 0,
            standard: 'closeButton,playPauseButton,previousButton,pageCounter,nextButton,fullscreenButton, label, custom'
        },
        fnImgToolbarCustDisplay: myImgToolbarCustDisplay
    });
    
    
    /*
     * ****** Events
     */
    function dynamicResizerEvent(){
        var cw = $('.col-xs-4').width();
        $('.event-container').css({'height':cw+'px'});
    } 
    
    //Ajax Loader für Inhalt in Lightbox bei bearbeiten
    $('.btn_event_edit').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"eventEdit":id},function(data){
            $('#eventEditContainer').html(data);
        }) 
    }); 



    // Diese Teil löst bei Resize und Onload mehrere Funktionen auf die Quadratische Darstellungen ermöglichen
    window.onresize= resize;
    window.onload= resize;
    
    function resize(){
        dynamicResizerEvent();
        dynamicResizer();
        setheightSchedule();
    }
    
    
    
    
    /*
     * ******** Deadlines
     */
    
    //Ajax Loader für Inhalt in Lightbox bei bearbeiten (Deadlines)
    $('.deadline-btn-edit').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"deadlineEdit":id},function(data){
            $('#deadlineEditContainer').html(data);
        }) 
    }); 
    
    //Ajax Loader für Inhalt in Lightbox bei anzeigen (Bauherr) (Deadlines)
    $('.deadline-btn-show').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"deadlineShow":id},function(data){
            $('#deadlineShowContainer').html(data);
        }) 
    }); 
    
    $('.datepicker').datepicker({
        dateFormat: "dd.mm.yy",
        monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa']
    });
    
    $('.clockpicker').clockpicker();



    //Terminplan
    function setheightSchedule(){
        var height = $( window ).height();
        height= height-200;
        $('#schedule-pdf').css({'height':height+'px'});
    } 



})//--> END document Ready


