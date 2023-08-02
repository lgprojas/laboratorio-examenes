<div class="container">
<h2>Administración de roles clientes</h2>
<p><a href="{$_layoutParams.root}aclcli/nuevo_role" class="btn btn-outline-secondary">Nuevo Rol</a></p>
{if isset($roles) && count($roles)}
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Role</th>
            <th></th>
            <th></th>
        </tr>
        {foreach item=rl from=$roles}
        <tr>
            <td>{$rl.Id_rolecli}</td>
            <td>{$rl.Nom_rolecli}</td>
            <td style="text-align: center;"><a href="{$_layoutParams.root}aclcli/permisos_role/{$rl.Id_rolecli}">Permisos</a></td>
            <td style="text-align: center;"><a href="{$_layoutParams.root}aclcli/editar_role/{$rl.Id_rolecli}">Editar</a></td>
        </tr>
        {/foreach}
    </table>
    {/if}
    
    <p><a href="{$_layoutParams.root}aclcli/nuevo_role" class="btn btn-outline-secondary">Nuevo Rol</a></p>
</div>