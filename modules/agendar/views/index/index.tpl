<div class="container">
<h3>Agendar</h3>

{literal}
    <style>
        div#modal_new1.modal.fade.show{
            /*padding-right: 0 !important;*/
        }
    </style>
{/literal}

<form method="post" action="">
    <input type="hidden" name="ver" value="1" />

<div class="col-lg-10 row">
    <div class="col-lg-3" style="">
        <label class="control-label">Laboratorio:</label>
        <select name="lab" id="lab" class="form-select">
            <option value="">--Seleccione clínica--</option>
            <option value="1">Principal - La Serena</option>
        </select>
    </div>

    <div class="col-lg-2">
        <label class="control-label">Día:</label>
        <input type="text" class="form-control" id="datepicker" name="date"  readonly="readonly" style="width: 110px;" placeholder="00-00-0000"/>
    </div>
    <div class="col-lg-2">
        <button type="button" id="verreservas" class="btn btn-primary" style="margin: 15px;"><i class="bi bi-search"></i> Ver</button>
    </div>
</div>

</form>
<div id="cuposagenda">
    
</div>

<div class="modal fade" id="modal_new1" style="overflow-y: scroll !important;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header" style="padding-top: 5px;padding-bottom: 5px;">
          <h5 class="modal-title">Cupos disponibles</h5>
              <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>         
        <form name="form2" method="post">
        <div class="modal-body">
            <div id="conten">
            
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <button type="submit" id="savecupos" class="btn btn-outline-primary m-3 position-relative" style="">Reservar <span id="count-cupos" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info text-white">0<span class="visually-hidden">New alerts</span></span></button>
            <button type="button" id="limpiar_modal1" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>

</div>
