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

    window.history.pushState('', '', '/php/index.php');
    
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
    });

    //Globale Adressliste (ohne Export-Funktion)
    $('#globalAddress').DataTable({
        "scrollY":        "290px",
        "scrollCollapse": true,
        "paging":         false,
        //dom: 'Bfrtip',
        ordering: true,
        fixedHeader: false,
        //responsive: true,
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
    
    $('.btn_details').click(function(){
        var id= $(this).val();
        $.post('../php/ajax.php', {"details":id},function(data){
            $('#detailAddress').html(data);
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
    
    //Ajax Loader für Inhalt in Lightbox auf Storage
    $('.btn_postEdit_storage').click(function(){
        var id= $(this).val();
        $.post('../php/ajax_pv.php', {"postStorage":id},function(data){
            $('#editContainer_storage').html(data);
        }) 
        
        
    });
    
    //Funktion zur Formularüberprüfung
    function formCheck() {
        alert('Test');
      if (document.createProject.ProjectNumber.value == "") {
          document.createProject.ProjectNumber.addClass('control-label');
          return false;
      }  
      if (document.createProject.Title.value == "") {
          document.createProject.Title.className= "control-label";
          return false;
      }
      if (document.createProject.Addressline1.value == "") {
          document.createProject.Addressline1.className= "control-label";
          return false;
      }
    }

   

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



    //Anzeige von Vorschau- Bildern in Timeline
    

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
    

   

    /*
    ***************** GALLERY ************
     */
    
    function myImgToolbarCustDisplay($elements, item, data) {
        //Ermittlung Usertyp und anzeige von Löschfunktion bei Architekt
        $.post('../php/ajax.php', {"getUserTyp":'2'},function(usertyp){
                if(usertyp==2){
                    // Custom Element mit Lösch-Button
                    $elements.html('<i id="delIMG" data-img="'+item.GetID()+'" class="fa fa-trash-o fa-2x"></i>');
                    
                    //Funktion die Bilder löscht (in DB und in Verzeichnis
                    $('#delIMG').click(function(){
                        var id= $(this).attr('data-img');
                        $.post('../php/ajax.php', {"delIMG":id},function(projectID){
                            //Neuladen der Galerie-Seite
                            window.location.replace("../php/index.php?id=7&status=4&project="+projectID);
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


    
    
    /*
     * Dashboard
     */
    function dynamicResizerDashboard(){
        var height = $(window).height();
        full=height-123;
        half= full/2-110;
        quat= height/4-40;
        $('.dash-timeline').css({'height':full+'px'});
        $('.dash-gallery').css({'height':half+'px'});
        $('.dash-deadlines').css({'height':quat+'px'});
        $('.dash-events').css({'height':quat+'px'});
    } 
    
    //Timeline-Mini
    function dynamicResizerDashTimeline(){
            var cw = $('.dash-timeline-img').width();
            cw +=30;
            $('.dash-timeline-img').css({'height':cw+'px'});
        }
        
    

    function dynamicResizerDashGallery(){
            var gw = $('.dash-gallery').width();
            var gh = $('.dash-gallery').height();
            gw=gw-30;
            gh=gh-30;
            $('.dash-slick-img').css({'height':gh+'px'});
            $('.dash-slick-img').css({'width':gw+'px'});

            //Gallery-mini
            $('.dash-slick-gallery').slick({
                                        slidesToShow: 3,
                                        slidesToScroll: 3,
                            dots: false,
                            infinite: false,
                            arrows: false,
                            autoplay: true,
                            autoplaySpeed: 4000,
                            lazyLoad: 'progressive'
                        });
        }










    // Diese Teil löst bei Resize und Onload mehrere Funktionen auf die Quadratische Darstellungen ermöglichen
    window.onresize= resize;
    window.onload= resize;
    
    function resize(){
        
        dynamicResizerEvent();
        dynamicResizer();
        setheightSchedule();
        dynamicResizerDashboard();
        dynamicResizerDashTimeline();
        dynamicResizerDashGallery();
    }

    
})//--> END document Ready


