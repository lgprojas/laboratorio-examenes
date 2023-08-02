<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}home">Home</a></li>
        <li class="breadcrumb-item"><a href="{$_layoutParams.root}usuarios/logincli/">Iniciar Sesión</a></li>
        <li class="breadcrumb-item active">Recuperar Password</li>
    </ol>
    </nav>
        
<h3>Recuperar Password</h3>
<h5>Cliente</h5>
{literal}
    <script type="text/javascript">
    function cb(formObj) {
                if(confirm("¿Está correcto su RUN?")) {
                    return true;                     
                } else {
                    return false;
                }
    }
    </script>
{/literal}

<div class="col-lg-10">
<form name="form1" method="post" action="">
    <input type="hidden" name="re" value="1" />    
    <div class="form-group col-lg-5 row">
        <div class="form-group col-lg-8">
        <label class="control-label">Ingrese RUN</label>  
        <input class="form-control" type="text" id="r" name="r" value="{$datos1.run|default:""}" placeholder="12345678k" onKeypress="return vdr(event);" maxlength="9"/>       
        </div>
        <div style="display: flex;align-items: center;" class="col-lg-1" id="valida1"></div>
    </div>              
     <br/>
     <div class="form-group">
     <a class="btn btn-outline-info" href="{$_layoutParams.root}usuarios/logincli/"><i title="Volver" class="bi bi-arrow-return-left"></i> Volver</a>
         <input type="submit" class="btn btn-small btn-primary" value="Recuperar" onclick='return cb(this);'/></p>
     </div>
</form>
</div>
<div class="form-group">
<label class="control-label">Nota *</label>
<div style="font-size: 11px;">Ingrese su RUN sin puntos y sin guión.</div>
</div>
</div>
             