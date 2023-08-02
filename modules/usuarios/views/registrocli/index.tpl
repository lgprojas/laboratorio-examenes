<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}empresa/seccion/index/{$Idempresa_encrypt}">Panel</a></li>
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}usuarios/indexcli/index/{$Idempresa_encrypt}">Lista usuarios clientes</a></li>
        <li class="breadcrumb-item active">Registro usuario cliente</li>
    </ol>
    </nav>
<h2>Registro usuario cliente</h2>
{literal}
    <script type="text/javascript">
    function cb(formObj) {
                if(confirm("¿Está seguro que desea crear este usuario al cliente?")) {
                    return true;                     
                } else {
                    return false;
                }
    }
    </script>
{/literal}
<br/>
<form name="form1" class="" method="post" action="">
    <input type="hidden" value="1" name="enviar" />
    <input type="hidden" id='empresa' value="{$Idempresa_encrypt}"/>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Cliente:</label>
            <select name="cli" id="cli" class="form-control">   
                <option value="">-Seleccione-</option>
                {foreach from=$cli item=c}
                    <option value="{$c.Id_cli}">{$c.Nom1_cli} {$c.Nom2_cli} {$c.Ape1_cli} {$c.Ape2_cli}</option>
                {/foreach}
            </select>
        </div>   
        <div class="form-group">
            <label class="control-label">RUN: </label><input type="text" id="run" name="run" value="{$datos.run|default:""}" placeholder="Ingrese RUN" class="form-control"/>        
        </div>
        <div class="form-group">
            <label class="control-label">Nombre: </label><input type="text" id="nombre" name="nombre" value="{$datos.nombre|default:""}" placeholder="Ingrese su nombre" class="form-control"/>      
        </div>    
        <div class="form-group">
            <label class="control-label">Usuario: </label><input type="text" id="usuario" name="usuario" value="{$datos.usuario|default:""}" placeholder="Ingrese nombre usuario (RUN)" class="form-control"/>       
        </div>   
        <div class="form-group">
            <label class="control-label">Email: </label><input type="text" id="email" name="email" value="{$datos.email|default:""}" placeholder="Ingrese email" class="form-control"/>       
        </div> 
        <div class="form-group">
            <label class="control-label">Contraseña: </label><input type="password" name="pass" placeholder="Ingrese constraseña" class="form-control"/>    
        </div>   
        <div class="form-group">
            <label class="control-label">Confirmar: </label><input type="password" name="confirmar" placeholder="Reingrese contraseña" class="form-control"/>       
        </div>  
        <br/>
        <div class="form-group p-2">            
            <p>
               <a class="btn btn-outline-info" href="{$_layoutParams.root}usuarios/indexcli/index/{$Idempresa_encrypt}"><i class="bi bi-arrow-return-left"></i> Volver</a>
               <input class="btn btn-small btn-primary" type="submit" value="Crear" onclick='return cb(this);' />
            </p>
        </div>
    </div>
</form>
</div>

 