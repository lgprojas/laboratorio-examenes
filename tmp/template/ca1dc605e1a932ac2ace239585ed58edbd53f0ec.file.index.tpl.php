<?php /* Smarty version Smarty-3.1.11, created on 2023-06-29 18:10:21
         compiled from "C:\xampp\htdocs\medlaboral\modules\agendar\views\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:467455135649daced0d6a50-81629899%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca1dc605e1a932ac2ace239585ed58edbd53f0ec' => 
    array (
      0 => 'C:\\xampp\\htdocs\\medlaboral\\modules\\agendar\\views\\index\\index.tpl',
      1 => 1656622841,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '467455135649daced0d6a50-81629899',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_649daced14aff9_77581556',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_649daced14aff9_77581556')) {function content_649daced14aff9_77581556($_smarty_tpl) {?><div class="container">
<h3>Agendar</h3>


    <style>
        div#modal_new1.modal.fade.show{
            /*padding-right: 0 !important;*/
        }
    </style>


<form method="post" action="">
    <input type="hidden" name="ver" value="1" />

<div class="col-lg-10 row">
    <div class="col-lg-3" style="">
        <label class="control-label">Laboratorio:</label>
        <select name="lab" id="lab" class="form-select">
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
<?php }} ?>