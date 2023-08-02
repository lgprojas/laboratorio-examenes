$(document).ready(function(){ 

    $("#verreservas").click(function(evt){
        //muestra los ya reservados por fch
        showPleaseWait();
        evt.preventDefault();

        var lab = $("#lab").val();
        var fch = $("#datepicker").val();
        
        $.post(_root_ + 'agendar/index/getAllHoursReserved','lab='+lab+'&fch='+fch, function(datos){

            //Siempre vacio, pero muestra cuando se selecciona ciu y fch, y se presiona ver
            //actualizar #cuposagenda dependiendo de fecha
            
            $("#cuposagenda").html('');
            
            var content = '<div class="col-lg-2">';
                content += '<button type="button" id="vercupos" class="btn btn-outline-secondary" style="" data-toggle="modal" data-target="#modal_new1"><i class="bi bi-clipboard2-plus"></i> Nueva reserva</button>';
            content += '</div>';
            
            //mostrar los cupos reservados
            
            for(var i = 0; i < datos.length; i++){
                content +='<div class="cols-lg-12 text-start">';
                    content +='<div class="contenedor-persona row align-items-center">';
                        content +='<div class="d-flex flex-row col-md-3"><i class="bi bi-clock"></i>  '+datos[i].Nom_hora+'</div>';
                        content +='<div class="d-flex flex-row col-md-3">';
                            content +='<i title="Cupo '+i+'" class="bi bi-person-lines-fill"></i><div class="" style="padding-left:12px;">';
                            content +='<select name="ecli" class="ecli form-select col-md-2 form-select-sm" data-id="'+datos[i].Id_reserva+'">';
                                    if(datos[i].Id_cli){    
                                            content += '<option value="'+datos[i].Id_cli+'">'+ datos[i].Nom1_cli +' '+ datos[i].Ape1_cli +' '+ datos[i].Ape2_cli+'</option>';
                                        for(var x = 0; x < datos[i].Trab.length; x++){
                                            if(datos[i].Trab[x].Id_cli !== datos[i].Id_cli){
                                            content +='<option value="'+datos[i].Trab[x].Id_cli+'">'+datos[i].Trab[x].Nom1_cli+' '+datos[i].Trab[x].Ape1_cli+' '+datos[i].Trab[x].Ape2_cli+'</option>';
                                            }
                                        }
                                   }else{
                                            content += '<option value=""></option>';
                                        for(var x = 0; x < datos[i].Trab.length; x++){                        
                                            content +='<option value="'+datos[i].Trab[x].Id_cli+'">'+datos[i].Trab[x].Nom1_cli+' '+datos[i].Trab[x].Ape1_cli+' '+datos[i].Trab[x].Ape2_cli+'</option>';                                       
                                        }
                                   }
                            content +='</select>';
                        content +='</div>';  
                        content +='</div>';           
                        content +='<div class="col-md-auto">';
                            content +='<a class="btn btn-outline-secondary position-relative btn-sm" data-toggle="modal" data-target="#modal_pres'+ datos[i].Id_reserva+'"><i title="Selección de prestaciones" class="bi bi-list-task"></i> Prestaciones <span id="count-pres_'+ datos[i].Id_reserva+'" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">'+datos[i].Total_prest+'<span class="visually-hidden">New alerts</span></span></a>';
                        content +='</div>';
                        content +='<div class="col col-md-4">';
                        content +='';
                        content +='</div>';                       
                    content +='</div>';
                content +='</div>';
                
                //modal prestaciones
                content +='<div class="modal fade" id="modal_pres'+datos[i].Id_reserva+'">';
                content +='<div class="modal-dialog modal-xl">';
                  content +='<div class="modal-content">';
                    content +='<div class="modal-header">';
                      content +='<h5 class="modal-title">Prestaciones '+datos[i].Id_reserva+'</h5>';
                          content +='<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>';
                    content +='</div>';            
                    content +='<div class="modal-body">';
                    content +='<div class="row row-cols-2 row-cols-lg-12 p-2" style="background:#F5F5F5;border-radius: 5px;">';
                    content +='<fieldset class="border rounded-3 p-3 section">';
                        content +='<legend class="float-none w-auto px-3">Baterías</legend>';
                            //- for baterias
                            for(var x = 0; x < datos[i].AllBaterias.length; x++){
                                
                                    content +='<div class="tile">';
                                        content +='<input type="checkbox" name="pres_'+datos[i].Id_reserva+'[]" class="pres_'+datos[i].Id_reserva+'" data-id="'+datos[i].Id_reserva+'" data-pres="'+datos[i].AllBaterias[x].Id_prestacion+'" id="bat_'+ datos[i].Id_reserva+'_'+datos[i].AllBaterias[x].Id_prestacion+'" value="1"';
                                        for(var b = 0; b < datos[i].Baterias.length; b++){                                    
                                            if(datos[i].AllBaterias[x].Id_prestacion === datos[i].Baterias[b].Id_prestacion){
                                                   content +=' checked="checked" ';
                                            }else{
                                                   content +=' unchecked ';
                                            }
                                        }
                                        content +='/>';
                                        content +='<label for="bat_'+ datos[i].Id_reserva+'_'+datos[i].AllBaterias[x].Id_prestacion+'">';
                                            content +='<h6>'+datos[i].AllBaterias[x].Nom_prestacion+'</h6>';
                                        content +='</label>';
                                    content +='</div>';
                                
                            }
                            //-/for baterias
                    content +='</fieldset>';
                    content +='<fieldset class="border rounded-3 p-3 section">';
                        content +='<legend class="float-none w-auto px-3">Exámenes</legend>';
                            //- for examenes
                            for(var y = 0; y < datos[i].AllExamenes.length; y++){
                                
                                    content +='<div class="tile">';
                                        content +='<input type="checkbox" name="pres_'+datos[i].Id_reserva+'[]" class="pres_'+datos[i].Id_reserva+'" data-id="'+datos[i].Id_reserva+'" data-pres="'+datos[i].AllExamenes[y].Id_prestacion+'" id="bat_'+ datos[i].Id_reserva+'_'+datos[i].AllExamenes[y].Id_prestacion+'" value="1"';
                                        for(var a = 0; a < datos[i].Examenes.length; a++){                                    
                                            if(datos[i].AllExamenes[y].Id_prestacion === datos[i].Examenes[a].Id_prestacion){
                                                   content +=' checked="checked" ';
                                            }else{
                                                   content +=' unchecked ';
                                            }
                                        }
                                        content +='/>';
                                        content +='<label for="bat_'+ datos[i].Id_reserva+'_'+datos[i].AllExamenes[y].Id_prestacion+'">';
                                            content +='<h6>'+datos[i].AllExamenes[y].Nom_prestacion+'</h6>';
                                        content +='</label>';
                                    content +='</div>';
                                
                            }
                            //-/for examenes
                        content +='</fieldset>';
                    content +='</div>';
                    content +='</div>';
                    content +='<div class="clearfix"></div>';
                    content +='<div class="modal-footer">';
                        content +='<div id="mssg_'+datos[i].Id_reserva+'"></div>';
                        content +='<button type="button" id="limpiar_modal1" class="btn btn-primary" data-dismiss="modal">Listo</button>';
                    content +='</div>';
                  content +='</div>';
                content +='</div>';
                content +='</div>';
                
                

            //$('#cuposagenda').append(content);                                    
            }
            
            $('#cuposagenda').append(content); 
            hidePleaseWait();
        }, 'json');
        
    });

    $(document).on("click", "#vercupos", function(evt){
        //muestra modal para reservar nuevos cupos
        showPleaseWait();
        evt.preventDefault();
        
        var lab = $("#lab").val();
        var fch = $("#datepicker").val();
        
        $.post(_root_ + 'agendar/index/getAllHours','lab='+lab+'&fch='+fch, function(datos){
            //alert(datos["Asig"][0].Id_asig);
            
            //Siempre vacio, pero muestra cuando se selecciona ciu y fch, y se presiona ver
            //actualizar #cuposagenda dependiendo de fecha
            
            $("#conten").html('');
            var content = '<input type="hidden" id="savecupos" value="1" />';
            
            for(var i = 0; i < datos.length; i++){ 
                content += '<div class="row col-12 p-1 border">';
                content += '<div class="col-2" style="">'+datos[i].Nom_hora+'</div>';
                content += '<div class="col-4" style="">';
                content += '<select id="'+datos[i].Id_hora+'" name="'+datos[i].Id_hora+'" class="countprest form-select form-select-sm" name="'+datos[i].Nom_hora+'"';
                if(datos[i].Cupos == 0){
                    content += 'aria-label="Disabled select example" disabled>';
                    content += '<option value="">Sin cupo</option>'; 
                }else{
                    content +='>';
                    content += '<option value=""></option>'; 
                }
                
                for(var z = 1; z <= datos[i].Cupos; z++){ 
                    //cuando es un valor fijo no es necesario length
                    if(z==1){
                        content +='<option value="'+z+'">'+z+' cupo</option>'; 
                    }else{
                        content +='<option value="'+z+'">'+z+' cupos</option>';
                    }
                }
                content += '</select>';
                content += '</div>';
                content += '<div class="col-4 align-self-center" style="font-size:10px;">Cupos: '+datos[i].Cupos+'</div>';
                content += '</div>';
            }
            
            $("#conten").append(content);
            $("#modal").modal("show");
            hidePleaseWait();
        }, 'json');
    });  
    
    //reserva cupos
    $("#savecupos").click(function(evt){
        //para registrar cupos
        if(confirm("¿Desea registrar estos cupos?")) {
                  
            showPleaseWait();
            evt.preventDefault();
            


            //for jquery para registrar cada cupo
            $("select.countprest option:selected").each(function(){           
                //va recorrer todos los select
                if($(this).val()){//sólo procesa los select con valor
                    var valores = "";
                    var cantcupos = 0;
                    var cant = 0;
                    cantcupos = $(this).val();
                    var lab = 'lab=' + $("#lab").val();
                    var fch = '&fch=' + $("#datepicker").val();
                    cant = '&cant=' + cantcupos;//cantidad cupos
                    var hora = '&hora=' + $(this).parent().attr("id");//id select hora

                    valores = lab+fch+cant+hora;
                    //verifica cupos nuevamente
                    $.post(_root_ + 'agendar/index/itsok',lab+fch+hora, function(datos){   
                      var content = JSON.parse(datos);
                    
                    if(content.Total >= cantcupos){//si hay menos cupos de los que solicita    
                        $.ajax({
                                type: "POST",
                                url: _root_ + 'agendar/index/snc',
                                data: valores,
                                headers: {'X-CSRF-TOKEN': $('meta[name="tkn"]').attr('content')},
                                success: function(data){ 
                                    //anexa los nuevos cupos                      
                                    var addcupos = JSON.parse(data);
                                    
                                    if(addcupos.length > 0){

                                        for(var i = 0; i < addcupos.length; i++){
                                           
                                        var content ='<div class="cols-lg-12 text-start">';
                                            content +='<div class="contenedor-persona row align-items-center">';
                                                content +='<div class="d-flex flex-row col-md-3"><i class="bi bi-clock"></i>   '+addcupos[i].hora+'</div>';
                                                content +='<div class="d-flex flex-row col-md-3">';
                                                    content +='<i title="Persona 1" class="bi bi-person-lines-fill"></i><div class="" style="padding-left:12px;">';
                                                    content +='<select name="ecli" class="ecli form-select col-md-2 form-select-sm" data-id="'+addcupos[i].idr+'">';
                                                                content += '<option value=""></option>';
                                                        for(var j = 0; j < addcupos[i].trab.length; j++){
                                                                content +='<option value="'+addcupos[i].trab[j].Id_cli+'">'+addcupos[i].trab[j].Nom1_cli+' '+addcupos[i].trab[j].Ape1_cli+' '+addcupos[i].trab[j].Ape2_cli+'</option>';
                                                        }           
                                                    content +='</select>';
                                                content +='</div>'; 
                                                content +='</div>'; 
                                                content +='<div class="col-md-auto">';
                                                    content +='<a class="btn btn-outline-secondary position-relative btn-sm" data-toggle="modal" data-target="#modal_pres'+addcupos[i].idr+'"><i title="Selección de prestaciones" class="bi bi-list-task"></i> Prestaciones <span id="count-pres_1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">0<span class="visually-hidden">New alerts</span></span></a>';
                                                content +='</div>';
                                            content +='</div>';
                                        content +='</div>';
                                        
                                        //modal prestaciones
                                        content +='<div class="modal fade" id="modal_pres'+addcupos[i].idr+'">';
                                        content +='<div class="modal-dialog modal-xl">';
                                          content +='<div class="modal-content">';
                                            content +='<div class="modal-header">';
                                              content +='<h5 class="modal-title">Prestaciones '+addcupos[i].idr+'</h5>';
                                                  content +='<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>';
                                            content +='</div>';            
                                            content +='<div class="modal-body">';
                                            content +='<div class="row row-cols-2 row-cols-lg-12 p-2" style="background:#F5F5F5;border-radius: 5px;">';
                                            content +='<fieldset class="border rounded-3 p-3 section">';
                                                content +='<legend class="float-none w-auto px-3">Baterías</legend>';
                                                    //- for baterias
                                                    for(var x = 0; x < addcupos[i].AllBaterias.length; x++){
                                                    content +='<div class="tile">';
                                                        content +='<input type="checkbox" name="pres_'+addcupos[i].idr+'[]" class="pres_'+addcupos[i].idr+'" data-id="'+addcupos[i].idr+'" data-pres="'+addcupos[i].AllBaterias[x].Id_prestacion+'" id="bat_'+addcupos[i].idr+'_'+addcupos[i].AllBaterias[x].Id_prestacion+'" value="1"/>';
                                                        content +='<label for="bat_'+addcupos[i].idr+'_'+addcupos[i].AllBaterias[x].Id_prestacion+'">';
                                                            content +='<h6>'+addcupos[i].AllBaterias[x].Nom_prestacion+'</h6>';
                                                        content +='</label>';
                                                    content +='</div>';
                                                    }
                                                    //-/for baterias
                                            content +='</fieldset>';
                                            content +='<fieldset class="border rounded-3 p-3 section">';
                                                content +='<legend class="float-none w-auto px-3">Exámenes</legend>';
                                                    //- for examenes
                                                    for(var y = 0; y < addcupos[i].AllExamenes.length; y++){
                                                    content +='<div class="tile">';
                                                        content +='<input type="checkbox" name="pres_1[]" class="pres_'+addcupos[i].idr+'" data-id="'+addcupos[i].idr+'" data-pres="'+addcupos[i].AllExamenes[y].Id_prestacion+'" id="exam_'+addcupos[i].idr+'_'+addcupos[i].AllExamenes[y].Id_prestacion+'"  value="4"/>';
                                                        content +='<label for="exam_'+addcupos[i].idr+'_'+addcupos[i].AllExamenes[y].Id_prestacion+'">';
                                                            content +='<h6>'+addcupos[i].AllExamenes[y].Nom_prestacion+'</h6>';
                                                        content +='</label>';
                                                    content +='</div>';
                                                    }
                                                    //-/for examenes
                                                content +='</fieldset>';
                                            content +='</div>';
                                            content +='</div>';
                                            content +='<div class="clearfix"></div>';
                                            content +='<div class="modal-footer">';
                                                content +='<div id="mssg_'+addcupos[i].idr+'"></div>';
                                                content +='<button type="button" id="limpiar_modal1" class="btn btn-primary" data-dismiss="modal">Listo</button>';
                                            content +='</div>';
                                          content +='</div>';
                                        content +='</div>';
                                        content +='</div>';
                                        
                                        $('#cuposagenda').append(content);                                    
                                        }
                                    }
                                }
                        }); 
                   
                    }else{
                        //falta actualizar 
                        if(confirm('En el horario: '+content.Hora+' hrs, sólo hay: '+content.Total+' cupo(s) ¿Desea reservar de todas maneras?')) {
                        $.ajax({
                                type: "POST",
                                url: _root_ + 'agendar/index/snc',
                                data: valores,
                                headers: {'X-CSRF-TOKEN': $('meta[name="tkn"]').attr('content')},
                                success: function(data){ 
                                    if(data["add"] > "0"){
                                        for(var i = 0; i < data["add"]; i++){
                                        var content ='<div class="cols-lg-12 text-start">';
                                            content +='<div class="contenedor-persona row align-items-center">';
                                                content +='<div class="d-flex flex-row col-md-3">';
                                                    content +='<i title="Persona 1" class="bi bi-person-lines-fill"></i>';
                                                    content +='<select name="ecli" class="ecli form-select col-md-2 form-select-sm">';
                                                               content +='<option value="2">Trabajador</option>';
                                                    content +='</select>';
                                                content +='</div>';           
                                                content +='<div class="col-md-auto">';
                                                    content +='<a class="btn btn-outline-secondary position-relative btn-sm" data-toggle="modal" data-target="#modal_new1"><i title="Selección de prestaciones" class="bi bi-person-lines-fill"></i> Prestaciones <span id="count-pres_1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">0<span class="visually-hidden">New alerts</span></span></a>';
                                                content +='</div>';
                                            content +='</div>';
                                        content +='</div>';

                                        $('#cuposagenda').append(content);                                    
                                        }
                                    }
                                }
                        }); 
                        }  
                    }
                    });
                }       
                
            });
            hidePleaseWait();
            $('#modal_new1').modal('toggle');
        }    
    });
    
    //editar trab
    $(document).on("change", ".ecli", function(evt){
        //para definir el trab en el cupo                
        if(confirm('¿Desea guardar esta modificación?')) {
            
            showPleaseWait();
            evt.preventDefault();
            
                var valores = "";
            
                var valor = 'id=' + $(this).attr('data-id');//id reserva
                var opcion = '&opcion=' + $(this).val();//id trab
                
                valores = valor+opcion;
                
                        $.ajax({
                                type: "POST",
                                url: _root_ + 'agendar/index/editTrabCupo',
                                data: valores,
                                headers: {'X-CSRF-TOKEN': $('meta[name="tkn"]').attr('content')},
                                success: function(data){ 
                                  if(data["valor"] == "1"){
                                      $('#mssg').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><div><i class="bi bi-check-circle-fill"></i> ' + data["mssg"] +'</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');  
                                      hidePleaseWait();
                                  }  
                                }
                                
                              }); 
        }  
        

        
        //alert(valor);
    });
    
    //add-edit prest
    $(document).on("change", "input:checkbox", function(evt){
        //agrega atenciones o elimina atenciones
        //sobre una atención que está aún a tiempo de ser modificado

        var valores = "";
                var reserva = 'id=' + $(this).attr('data-id');//id reserva
                var pres =  '&pres=' + $(this).attr('data-pres');//id pres
                
                valores = reserva+pres;
                
                        $.ajax({
                                type: "POST",
                                url: _root_ + 'agendar/index/editPrestRerserva',
                                data: valores,
                                headers: {'X-CSRF-TOKEN': $('meta[name="tkn"]').attr('content')},
                                success: function(data){ 
                                  if(data["valor"] == "1"){
                                      $('#mssg_'+data["cod"]).html('<div style="color:#20b027;"><i title="" class="bi bi-check-circle"></i> '+data["mssg"]+'</div>').fadeIn(1000).fadeOut(2000);
                                      $('#count-pres_'+data["cod"]).html(data["total"]);
                                  } 
                                  if(data["valor"] == "2"){
                                      $('#mssg_'+data["cod"]).html('<div style="color:#fad53f;"><i title="" class="bi bi-exclamation-triangle"></i> '+data["mssg"]+'</div>').fadeIn(1000).fadeOut(2000);  
                                  } 
                                }
                                
                              });
        
    });
   
    
//------------------------------------------------------------------------------
function showPleaseWait() {
     waitingDialog.show('Espere...');
     //waitingDialog.show('Custom message', {dialogSize: 'sm', progressType: 'warning'});

}
function hidePleaseWait() {
        setTimeout(function () {
      waitingDialog.hide();
    }, 1000);
}
//-------------------------------------------------------------------------    


});


