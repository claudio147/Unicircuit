$(document).ready(function(){

    $('.table').DataTable( {
        "scrollY":        "300px",
        "scrollCollapse": true,
        "paging":         false,
    });
    
    
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
        
        




})

