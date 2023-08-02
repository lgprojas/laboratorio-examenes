<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}empresa/seccion/index/{$Idempresa_encrypt}">Panel</a></li>
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}usuarios/indexcli/index/{$Idempresa_encrypt}">Lista usuarios cliente</a></li>
        <li class="breadcrumb-item active">Registro usuario cliente</li>
    </ol>
    </nav>
    
<h3>Editar usuario</h3>
<h5>Cliente</h5>
{literal}
    <script type="text/javascript">
    function cb(formObj) {
                if(confirm("¿Está seguro que desea modificar este usuario")) {
                    return true;                     
                } else {
                    return false;
                }
    }
    </script>
{/literal}

<div class="col-lg-4">
<form name="form1" method="post" action="">
    <input type="hidden" name="guardar" value="1" />
    <input type="hidden" name="id" value="{$datos.Id_usucli}" />
    <div class="form-group">
        <label class="control-label">Rut *</label> 
        <input class="form-control" type="text" name="rut" value="{$datos1.rut|default:$datos.Rut_usucli}" placeholder="Ingrese Rut usuario" readonly="true"/>       
    </div>    
    <div class="form-group">
        <label class="control-label">Nombre *</label>  
        <input class="form-control" type="text" name="nom" value="{$datos1.nom|default:$datos.Nom_usucli}" placeholder="Ingrese nombre personal del usuario"/>       
    </div>
    <div class="form-group">
        <label class="control-label">Nombre usuario *</label> 
        <input class="form-control" type="text" name="usu" value="{$datos1.usu|default:$datos.Usu_usucli}" placeholder="Ingrese nombre de usuario" readonly="true"/>       
    </div>         
    <div class="form-group">
        <label class="control-label">Email *</label>  
        <input class="form-control" type="text" name="email" value="{$datos1.email|default:$datos.Email_usucli}" placeholder="Ingrese email usuario"/>       
    </div>      
   <div class="form-group">
        <label class="control-label">Rol: </label>
            <select class="form-control" name="role" id="role">

                {if $datos.Id_rolecli != 0}
                    <option value="{$datos.Id_rolecli}">{$datos.Nom_rolecli}</option>
                    {foreach from=$rol item=r}
                        {if $r.Id_rolecli != $datos.Id_rolecli}
                            <option value="{$r.Id_rolecli}">{$r.Nom_rolecli}</option>
                        {/if}
                    {/foreach}
                {else}
                    <option value="">-Seleccione-</option>
                                 {foreach from=$rol item=r}
                                    <option value="{$r.Id_rolecli}">{$r.Nom_rolecli}</option>
                                 {/foreach}
                {/if}
             </select>            
    </div>
    <div class="form-group">
        <label class="control-label">Estado: </label>
            <select class="form-control" name="est" id="est">

                {if $datos.Id_estusucli != 0}
                    <option value="{$datos.Id_estusucli}">{$datos.Nom_estusucli}</option>
                    {foreach from=$est item=e}
                        {if $e.Id_estusucli != $datos.Id_estusucli}
                            <option value="{$e.Id_estusucli}">{$e.Nom_estusucli}</option>
                        {/if}
                    {/foreach}
                {else}
                    <option value="">-Seleccione-</option>
                                 {foreach from=$est item=e}
                                    <option value="{$e.Id_estusucli}">{$e.Nom_estusucli}</option>
                                 {/foreach}
                {/if}
             </select>            
    </div>
 <br/>
 <p><a class="btn btn-outline-info" href="{$_layoutParams.root}usuarios/indexcli/index/{$Idempresa_encrypt}"><i title="Volver" class="bi bi-arrow-return-left"></i> Volver</a>
     <input type="submit" class="btn btn-small btn-primary" value="Editar" onclick='return cb(this);'/></p>

</form>
</div>
</div>
             