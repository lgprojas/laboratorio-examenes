<?php /* Smarty version Smarty-3.1.11, created on 2022-06-19 00:46:52
         compiled from "C:\xampp_x\htdocs\medlaboral\modules\usuarios\views\login\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135488003062ae55dcf2aea8-43259363%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b9a6d0f58634a4c23f2bb786bae4951c8cb4ea3c' => 
    array (
      0 => 'C:\\xampp_x\\htdocs\\medlaboral\\modules\\usuarios\\views\\login\\index.tpl',
      1 => 1654550559,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135488003062ae55dcf2aea8-43259363',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_62ae55dd59a476_78135544',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_62ae55dd59a476_78135544')) {function content_62ae55dd59a476_78135544($_smarty_tpl) {?><div class="container">
<h2><i class="glyphicon glyphicon-log-in"> </i> Inicio Sesi&oacute;n</h2>
<br/>

<form class="navbar-form pull-left" name="form1" method="post" action="">
    <input type="hidden" value="1" name="enviar" />
    <div class="loginmodal-container">
            <h1>Ingrese a su cuenta</h1><br>

            <input type="text" name="usuario" placeholder="Usuario" autofocus="">
            <input type="password" name="pass" placeholder="Password">                   

            <input type="submit" name="login" class="login loginmodal-submit glyphicon glyphicon-log-in" value="Entrar">
      <div class="login-help">
            <a href="#">Recordar Password</a>
      </div>
    </div>
</form>
</div><?php }} ?>