<div class="container">
<h2><i class="glyphicon glyphicon-log-in"> </i> Inicio Sesi&oacute;n cliente</h2>
<br/>

<form class="navbar-form pull-left" name="form1" method="post" action="">
    <input type="hidden" value="1" name="enviar" />
    <div class="loginmodal-container">
            <h1>Ingrese a su cuenta</h1><br>

            <input type="text" name="usuario" placeholder="Usuario" autofocus="">
            <input type="password" name="pass" placeholder="Password">           

                <select name="empresa">   
                    <option value="">-Seleccione-</option>
                    {foreach from=$empresas item=e}
                        <option value="{$e.Id_empresa}">{$e.Nom_empresa}</option>
                    {/foreach}
                </select>            

            <input type="submit" name="login" class="login loginmodal-submit glyphicon glyphicon-log-in" value="Entrar">
      <div class="login-help">
            <a href="{$_layoutParams.root}usuarios/logincli/recuperarPassword">¿Olvidó su password?</a>
      </div>
    </div>
</form>
</div>