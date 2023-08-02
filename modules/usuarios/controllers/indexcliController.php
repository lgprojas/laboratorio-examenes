<?php

class indexcliController extends usuariosController {
    
    private $_usucli;
    
    public function __construct() {
        parent::__construct();
        $this->_usucli =  $this->loadModel('indexcli');
    }
    
    public function index($idempresa=false){
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('ver_usucli');
        
        $empresa = $this->decrypt($idempresa);
        
        $this->_view->assign('Idempresa_encrypt', $idempresa);
        
        $this->_view->assign('titulo', 'Usuarios cli');
        $this->_view->assign('color', '#F5FFFA'); 
        
        $row = $this->_usucli->getUsuariosCli();
        for ($i = 0; $i < count($row); $i++) {
            $row[$i]['Id_encrypt'] = $this->encrypt($row[$i]['Id_usucli']);
            $permisosUsuario = $this->_usucli->getPermisosUsuarioCli($row[$i]['Id_usucli']);      
            $permisosRole = $this->_usucli->getPermisosRoleCli($row[$i]['Id_usucli']);
            if(!$permisosUsuario || !$permisosRole){$row[$i]['Sin_perm'] = 1;}else{$row[$i]['Sin_perm'] = 0;}
        }
        $this->getLibrary('paginador');
        $pag1 = new Paginador();        
        $pagina1 = false;        
        if($_POST){
            $this->_view->assign('_datos', $_POST);
            $pagina1 = $this->getInt('pagina1');
        }
        
        $this->_view->setJs(array('example'));        
        $this->_view->assign('color', '#F5FFFA');        
        $this->_view->assign('usuarioscli', $pag1->paginar($row, $pagina1, 20));
        $this->_view->assign('paginacion1', $pag1->getView('example1'));
        
        $this->_view->renderizar('index', 'index');
    }
    
    public function permisos($idusu=false,$idempresa=false){
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('editar_perm_usucli');
        
        $id = $this->decrypt($idusu);
        //$id = $usuarioID;
        
        if(!$id){
            Session::setMensaje("El usuario no existe");
            $this->redireccionar('usuarios');
            exit;
        }
        
        $this->_view->assign('Idempresa_encrypt', $idempresa);
        
        if($this->getInt('guardar') == 1){
            $values = array_keys($_POST);//tomas los key's del POST
            $replace = array();
            $eliminar = array();
            
            for($i = 0; $i < count($values); $i++){
                if(substr($values[$i],0,5) == 'perm_'){// extrae le prefijo perm_ , extrae los primeros 5 caracteres y si es igual a perm_
                    $permiso = (strlen($values[$i]) - 5);//para tomar los 2 últimos valores del prefijo
                    
                    if($_POST[$values[$i]] == 'x'){//el permiso está heredado del role y no ignorado
                        $eliminar[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], - $permiso)//toma los 2 últimos caracteres de la cadena(en este caso el Id_perm que va después del prefijo) quiere decir que si enviamos "perm_12"
                        );
                    }else{
                        if($_POST[$values[$i]] == 1){//permisos_usuario existirá un valor 1 o 0
                            $v = 1;
                        }else{
                            $v = 0;
                        }
                        
                        $replace[] = array(
                            'permiso' => substr($values[$i], - $permiso),//toma los 2 últimos caracteres de la cadena
                            'valor' => $v,
                            'usuario' => $id//role contiene a $id = Id_role                            
                            
                        );
                    }
                }
            }
            
            for($i =0; $i < count($eliminar); $i++){
                $this->_usucli->eliminarPermiso(
                        $eliminar[$i]['usuario'],
                        $eliminar[$i]['permiso']);
            }
            
             for($i =0; $i < count($replace); $i++){
                $this->_usucli->editarPermiso(
                        $replace[$i]['usuario'],
                        $replace[$i]['permiso'],
                        $replace[$i]['valor']);
            }
        }
        
        $permisosUsuario = $this->_usucli->getPermisosUsuarioCli($id);      
        $permisosRole = $this->_usucli->getPermisosRoleCli($id);

        if(!$permisosUsuario || !$permisosRole){
            Session::setMensaje("Posee un error la información del usuario");
            $this->redireccionar('usuarios');
            exit;
        }
        
        $this->_view->assign('titulo', 'Permisos de usuario');
        $this->_view->assign('permisos', array_keys($permisosUsuario));//enviamos los usuarios
        $this->_view->assign('usuario', $permisosUsuario);
        $this->_view->assign('role', $permisosRole);
        $this->_view->assign('info', $this->_usucli->getDatosUsuarioCli($id));//devuelve el Nom_usu y Nom_role asociado a ese usuario
        
        $this->_view->renderizar('permisos', 'indexcli');
    }
    
    public function editUsuCli($idusu=false,$idempresa=false){
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('editar_usucli');
        
        $id = $this->decrypt($idusu);
        //$id = $this->filtrarInt($idusu);
        
        if(!$id){
            $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
        }
        $row = $this->_usucli->getDatosUsuarioCli($id);
        
        $this->_view->assign('titulo', 'Edición de usuario clientes');
        
        $this->_view->assign('Idempresa_encrypt', $idempresa);
        
//        switch (Session::get('level')) {
//            case 1:
//                $this->_view->setJs(array('ajax')); 
//                $this->_view->assign('cond', $this->_usucli->getAllCond());
//                break;
//            case 2:
//                $sql = $this->_usucli->getCondsUsu(Session::get('id_usuario'));
//                for ($i = 0; $i < count($sql); $i++) {
//                    $conds[] = $sql[$i]['Id_cond'];
//                }
//                $allconds = implode(",",$conds);
//                $condicion = ' Id_cond IN ('.$allconds.')';
//                $this->_view->assign('cond', $this->_usucli->getCondGestor($condicion));
//                break;
//            default:
//                break;
//            }
        
        
        $this->_view->assign('est', $this->_usucli->getEstUsuCli());
        $this->_view->assign('datos', $row);
        
        if($this->getPostParam('guardar') == 1){
            $this->_view->assign('datos1', $_POST);
            
            if(!$this->getPostParam('rut')){
                $this->_view->assign('_error', 'Debe introducir Rut del usuario');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }
            if(!$this->getSql('nom')){
                $this->_view->assign('_error', 'Debe introducir nombre personal del usuario');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }
            if(!$this->getSql('usu')){
                $this->_view->assign('_error', 'Debe introducir un nombre de usuario');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }
            if(!$this->getPostParam('email')){
                $this->_view->assign('_error', 'Si desea editar debe introducir un email');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }  
            if(!$this->getSql('role')){
                $this->_view->assign('_error', 'Debe seleccionar el rol del usuario');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }
            if(!$this->getSql('est')){
                $this->_view->assign('_error', 'Debe seleccionar un estado (activado/desactivado)');
                $this->_view->renderizar('edit', 'indexcli');
                exit;
            }
            $this->_usucli->editUsuarioCli(
                    $this->getPostParam('id'),
                    $this->getPostParam('rut'),
                    $this->getSql('nom'),
                    $this->getSql('usu'),
                    $this->getPostParam('email'),
                    $this->getSql('role'),
                    $this->getSql('est')
                    );
            Session::setMensaje("Se ha editado correctamente el usuario cliente");
            $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
            exit;
        }
        
        $this->_view->renderizar('edit', 'indexcli');
    }
    
    public function editPassUsuCli($idusu=false,$idempresa=false){
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('editar_pass_usucli');
        
        $id = $this->decrypt($idusu);
        //$id = $this->filtrarInt($idusu);
        
        if(!$id){
            $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
        }
        
        $this->_view->assign('titulo', 'Edición Password Cli');
        
        $this->_view->setJs(array('pass'));
        $this->_view->assign('Idempresa_encrypt', $idempresa);
       
        
        if($this->getPostParam('guardar') == 1){
            $this->_view->assign('datos1', $_POST);
            
            if(!$this->getPostParam('pass1')){
                $this->_view->assign('_error', 'Debe introducir el nuevo password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(strlen($this->getPostParam('pass1')) < 8){
                $this->_view->assign('_error', 'Debe introducir mínimo 8 caracteres como password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(!$this->getPostParam('pass2')){
                $this->_view->assign('_error', 'Debe introducir nuevamente el nuevo password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(strlen($this->getPostParam('pass2')) < 8){
                $this->_view->assign('_error', 'Debe introducir mínimo 8 caracteres como password en ambas casillas');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if($this->getPostParam('pass1') !== $this->getPostParam('pass2')){
                $this->_view->assign('_error', 'Los valores introducidos de ambas casillas deben coincidir');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }

            $this->_usucli->editPassUsuarioCli(
                    $id,
                    $this->getPostParam('pass2')
                    );
            Session::setMensaje("Se ha editado correctamente el password del usuario cliente");
            $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
            exit;
        }
        
        $this->_view->renderizar('editpasscli', 'indexcli');
    }
    
    
    public function pau()
   {
       $pagina1 = $this->getInt('pagina');
       $cond = $this->getInt('co');
       $cb = $this->getInt('cb');
       $condicion = "";
       
       switch (Session::get('level')) {
            case 1:
                $this->_view->assign('sadmin', 1);
                $this->_view->assign('cond', $this->_usucli->getCondfUsu());
                if($cond){
                   $condicion .= " AND u.Id_cond = $cond ";
                }
                if($cb){
                   $condicion .= " AND Id_cb = $cb ";
                }
                break;
            case 2:
                $this->_view->assign('sadmin', 1);
                $sql = $this->_usucli->getCondsUsu(Session::get('id_usuario'));
                for ($i = 0; $i < count($sql); $i++) {
                    $conds[] = $sql[$i]['Id_cond'];
                }
                $allconds = implode(",",$conds);
                $condi = ' Id_cond IN ('.$allconds.')';
                $this->_view->assign('cond', $this->_usucli->getCondGestor($condi));
                if($cond){
                   $condicion .= " AND u.Id_cond = $cond ";
                }
                if($cb){
                   $condicion .= " AND Id_cb = $cb ";
                }
                break;
            default:
                $id = $this->_usucli->getIdCondUsu(Session::get('id_usuario'));
                if($cb){
                   $condicion .= " AND Id_cb = $cb ";
                }
                   $condicion .= " AND u.Id_cond = $id";    
                break;
       }
       
        if(Session::get('level') > 2){
        
        }else{
                
        } 
        //echo $condicion;exit;
        
        $this->getLibrary('paginador');
        $pag1 = new Paginador();
       
        $row = $this->_usucli->getUsuarios($condicion);
        for ($i = 0; $i < count($row); $i++) {
            $row[$i]['Id_encrypt'] = $this->encrypt($row[$i]['Id_usu']);
        }
        
        $this->_view->assign('root', BASE_URL);
        $this->_view->assign('_acl', $this->_acl);//para permiso
        $this->_view->setJs(array('ajax'));        
        $this->_view->assign('color', '#F5FFFA');        
        $this->_view->assign('usuarios', $pag1->paginar($row, $pagina1, 10));
        $this->_view->assign('paginacion1', $pag1->getView('paginacion_ajax'));
        $this->_view->renderizar('ajax/pagAjax', false, true);
   }
   
    public function gcbu(){
        
        if($this->getInt('co'))
        echo json_encode($this->_usucli->getCBLsUsu($this->getInt('co')));
    }
    
    public function elimUsuCli($idusu=false,$idempresa=false) {
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('elim_usucli');
        
        $id = $this->decrypt($idusu);
        
        if(!$id){
            Session::setMensaje("Usuario cliente no existe");
            $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
            exit;
        }
        
        $this->_usucli->elimUsuarioCli($id);
        
        Session::setMensaje("Se ha eliminado correctamente el usuario del cliente");
        $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
        exit;
    }

}
?>
