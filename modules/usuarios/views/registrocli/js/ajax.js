$(document).ready(function(){
    
    var gde = function(){
        $.post(_root_ + 'usuarios/registrocli/gdc','cli=' + $("#cli").val() + '&empresa=' + $("#empresa").val(), function(datos){
            $("#run").val('');
            $("#nombre").val('');
            $("#usuario").val('');
            $("#email").val('');
            
            $("#run").val(datos['a']);
            $("#nombre").val(datos['b'] + ' ' + datos['c'] + ' ' + datos['d']);
            $("#usuario").val(datos['a']);
            $("#email").val(datos['e']);
        }, 'json');
    } 
    
        $("#cli").change(function(){
        if(!$("#cli").val()){// si el select tiene value cero
            $("#run").val('');
            $("#nombre").val('');
            $("#usuario").val('');
            $("#email").val('');
        }else{
            gde();
        }
    }); 
    
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
});

