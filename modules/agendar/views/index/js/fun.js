$(document).ready(function(){

//show all
    var unavailableDates = ["21-6-2022","22-6-2022","28-6-2022"];//feriados + 1 día hábil

    function unavailable(date) {
        var dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
            if ($.inArray(dmy, unavailableDates) != -1) {
                    return [false,"","Deshabilitado"];
            } else {
            return $.datepicker.noWeekends (date); //weekends no habilitados
            }
    }

    $( "#datepicker" ).datepicker({
           inline: true,
           monthNames:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
           monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
           dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
           dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
           dateFormat: "dd-mm-yy",
           minDate: +1,
           beforeShowDay: unavailable

    });




    //cuando se agrega después al DOM
    $(document).on("change", "select.countprest",function() {
        
        $('#count-cupos').html('');
        var sum = 0;
        $("select.countprest option:selected").each(function(){            
            if($(this).val()){
               sum += Number($(this).val());
            }            
        });
        
        $('#count-cupos').append(sum);
    });



});
        
 

