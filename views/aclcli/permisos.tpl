<div class="container">
<h3>Administracion de permisos</h3>
<h5>Clientes</h5>

<p><a class="btn btn-outline-secondary" href="{$_layoutParams.root}aclcli/nuevo_permiso">Nuevo Permiso</a></p>
{if isset($permisos) && count($permisos)}
    <table class="table table-condensed">
    <tr>
        <th>ID</th>
        <th>Permiso</th>
        <th>Llave</th>
        <th></th>
    </tr>   
    {foreach item=rl from=$permisos}
        <tr>
            <td>{$rl.Id_permcli}</td>
            <td>{$rl.Nom_permcli}</td>
            <td>{$rl.Key_permcli}</td>
            <td><a href="{$_layoutParams.root}aclcli/editar_permiso/{$rl.Id_permcli}">Editar</a></td>
        </tr>     
    {/foreach}   
</table>
{else}
    <p><strong>No hay permisos registrados.</strong></p>
{/if}
<p><a class="btn btn-outline-secondary" href="{$_layoutParams.root}aclcli/nuevo_permiso">Nuevo Permiso</a></p>
</div>