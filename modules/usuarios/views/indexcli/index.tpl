<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}empresa/seccion/index/{$Idempresa_encrypt}">Panel</a></li>
        <li class="breadcrumb-item active">Lista usuarios clientes</li>
    </ol>
    </nav>
<h4>Usuarios Clientes</h4>
{literal}
    <script type="text/javascript">
    function euc(formObj) {
                if(confirm("¿Está seguro que desea eliminar este usuario cliente?")) {
                    return true;                     
                } else {
                    return false;
                }
    }
    </script>
{/literal}
<br/>
<!--{*if Session::accesoViewEstricto(array('usuario'))}
            
        <p><a href="{$_layoutParams.root}post/nuevo">Agregar Posts..</a></p>

{/if*}-->
{*if $_acl->permiso('crear_usu')*}
<p><br/><a class="btn btn-outline-secondary" href="{$_layoutParams.root}usuarios/registrocli/index/{$Idempresa_encrypt}"><i title="Agregar nuevo usuario" class="bi bi-plus"></i> Nuevo usuario</a></p>
{*/if*}
<div id="lista_registros">
<form name="form1" method="post">
<input type="hidden" value="{$_datos.pagina1|default:""}" name="pagina1">
{if isset($usuarioscli) && count($usuarioscli)}
<table class="table table-bordered">
    <thead style="background: #E6E6FA;">
        <th style="text-align: center;">ID</th>
        <th style="text-align: center;">Usuario</th>
        <th style="text-align: center;">Role</th>
        <th style="text-align: center;">Est.</th>
        {*if $_acl->permiso('editar_usu')*}<th style="text-align: center;">Editar</th>{*/if*}
        {*if $_acl->permiso('editar_usu')*}<th style="text-align: center;"><i class="bi bi-key"></i></th>{*/if*}
        {*if $_acl->permiso('editar_perm_usu')*}<th style="text-align: center;">Permisos</th>{*/if*}
        {*if $_acl->permiso('elim_usu')*}<th style="text-align: center;">Elim</th>{*/if*}
    </thead>
    </tr>
    
    {foreach item=us from=$usuarioscli}
        {if $color == "#F5FFFA"}{assign var="color" value="none"}{else}{assign var="color" value="#F5FFFA"}{/if}
        <tr id="list" style="background:{$color}" onmouseover=style.background="#F0F8FF" onmouseout=style.background="{$color}">
            <td>{$us.Id_usucli}</td>
            <td>{$us.Nom_usucli}</td>
            <td>{$us.Nom_rolecli}</td>
            <td style="text-align: center;">{if $us.Id_estusucli == 1}<span style="color:#1fb40d;"><i title="Activado" class="bi bi-person-check-fill"></i></span>{else}<span style="color:red;"><i title="Desactivado" class="bi bi-person-dash-fill"></i></span>{/if}</td>
            {*if $_acl->permiso('editar_usu')*}<td style="text-align: center;"><a href="{$_layoutParams.root}usuarios/indexcli/editUsuCli/{$us.Id_encrypt}/{$Idempresa_encrypt}"><i title="Editar datos usuario" class="bi bi-pencil-square"></i></a></td>{*/if*}
            {*if $_acl->permiso('editar_passusucli')*}<td style="text-align: center;"><a href="{$_layoutParams.root}usuarios/indexcli/editPassUsuCli/{$us.Id_encrypt}/{$Idempresa_encrypt}"><i title="Editar password" style="color: #dcbd20;" class="bi bi-key-fill"></i></a></td>{*/if*}
            {*if $_acl->permiso('editar_perm_usu')*}<td style="text-align: center;">{if $us.Sin_perm == "0"}<a href="{$_layoutParams.root}usuarios/indexcli/permisos/{$us.Id_encrypt}/{$Idempresa_encrypt}"><i title="Ver permisos" class="bi bi-ui-checks"></i></a>{else}<i title="Rol sin permisos" class="bi bi-file-lock2"></i>{/if}</td>{*/if*}
            {*if $_acl->permiso('elim_usu')*}<td style="text-align: center;"><a href="{$_layoutParams.root}usuarios/indexcli/elimUsuCli/{$us.Id_encrypt}/{$Idempresa_encrypt}" onclick='return euc(this);'><i title="Elimininar usuario" class="bi bi-trash"></i></a></td>{*/if*}
        </tr>        
    {/foreach}
    
</table>
{else}
            <p><strong>No hay usuarios clientes registrados!</strong></p>
{/if} 
</form>
{if isset($paginacion1)}{$paginacion1}   {/if}  
</div>
{if $_acl->permiso('crear_usu')}
<p><br/><a class="btn btn-outline-secondary" href="{$_layoutParams.root}usuarios/registrocli/index/{$Idempresa_encrypt}"><i title="Agregar nuevo usuario" class="bi bi-plus"></i> Nuevo usuario</a></p>
{/if}
</div>