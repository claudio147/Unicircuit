/*
 ******************** Projektverwaltung *******
*/


$(document).ready(function(){
    
    //Darstellung Teaserbild
    $(".imgLiquidFill").imgLiquid();
   
   function resizerTeaserIMG(){
            var cw = $('.pv-container').width();
            cw = (cw/4)*3;
            $('.project-img-cont').css({'height':cw+'px'});
        }
    window.onresize= resizerTeaserIMG;
    window.onload= resizerTeaserIMG;
        
        
        
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
})

