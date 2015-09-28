/*
 ******************** Projektverwaltung *******
*/

    function formCheck() {
        var error = true;
        //überprüft ob das Feld leer ist oder nicht, dementsprechend ändert es die class des eltern div.
      if (document.createProject.ProjectNumber.value === "") {
           var element = document.getElementById("ProNumb");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      }  else {
          var element = document.getElementById("ProNumb");
          element.className = "";
      }
      if (document.createProject.Title.value === "") {
           var element = document.getElementById("Title");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      }  else {
          var element = document.getElementById("Title");
          element.className = "";
      }
      if (document.createProject.ZIP.value === "") {
          var element = document.getElementById("ZIP");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("ZIP");
          element.className = "";
      }
      if (document.createProject.City.value === "") {
          var element = document.getElementById("City");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("City");
          element.className = "";
      }
      if (document.createProject.BhFirstname.value === "") {
          var element = document.getElementById("BhFn");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhFn");
          element.className = "";
      }
      if (document.createProject.BhLastname.value === "") {
          var element = document.getElementById("BhLn");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhLn");
          element.className = "";
      }
      if (document.createProject.BhAddressline1.value === "") {
          var element = document.getElementById("BhAddress1");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhAddress1");
          element.className = "";
      }
      if (document.createProject.BhZIP.value === "") {
          var element = document.getElementById("BhZIP");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhZIP");
          element.className = "";
      }
      if (document.createProject.BhCity.value === "") {
          var element = document.getElementById("BhCity");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhCity");
          element.className = "";
      }
      if (document.createProject.BhEmail.value === "") {
          var element = document.getElementById("BhEmail");
            element.classList.add("form-group");
            element.classList.add("has-error");
          var error = false;
      } else {
          var element = document.getElementById("BhEmail");
          element.className = "";
      }
      return error;
    }


$(document).ready(function(){
    
    //Darstellung Teaserbild
    $(".imgLiquidFill").imgLiquid();
   
   function resizerTeaserIMG(){
            var cw = $('.pv-container').width();
            cw = (cw/4)*3;
            $('.project-img-cont').css({'height':cw+'px'});
        }
        
    function marginTitlePV(){
        var cw = $('.wrapper-pv').width();
        var cont= $('.container-pv').width();
        var logo= $('.logo-pv').width();
        var w= (cw-cont)/2-230;
        if(((cw-cont)/2) > (logo+30)){
            $('.navbar-text').css({'margin-left':w+'px'});
        }else{
            $('.navbar-text').css({'margin-left':15+'px'});
        }
        
        //alert(w);
    }
    window.onresize= resize;
    window.onload= resize;
    
    function resize(){
        resizerTeaserIMG();
        marginTitlePV(); 
    }
   
        
        
        
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
    
    //Ajax Loader für User Settings in Lightbox bei bearbeiten
    $('.btn_userSettings').click(function(){
        var id= $(this).data('value');
        $.post('../php/ajax_pv.php', {"userSettings":id},function(data){
            $('#editContainer_User').html(data);
        })      
    });
    
    
    //Warnmeldung beim Löschen
    $(function(){       
        // jQuery UI Dialog   
                 
        $('#dialog').dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            resizable: false,
            buttons: {
                "Löschen": function() {
                    document.deleteProject.submit();

                },
                "Abbrechen": function() {
                    $(this).dialog("close");
                }
            }
        });
        
         
        $('form#deleteProject').submit(function(e){
            e.preventDefault();
 
            //$("p#dialog-email").html($("input#emailJQ").val());
            $('#dialog').dialog('moveToTop');
            $('#dialog').dialog('open');
            
        });
    });
    

    
    

});


