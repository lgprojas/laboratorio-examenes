$(document).ready(function(){
    
    $("input:checkbox").change(function(e) {
        //alert("Cambio");
        var classCheck = $(this).attr('class');
        $('#count-'+classCheck).html('');
        var count = $('.'+classCheck).filter(':checked').length;
        $('#count-'+classCheck).append(count);
    });

});
