<?php

class ACL{
    
    private $_db;//guardamos un objeto de la BD
    private $_id;//guardamos la id del usuario para lista de acceso
    private $_role;//guardamos el role con el cual estamos trabajando
    private $_permisos;//permisos ya procesados
    private $_cli;//tabla del usuario según rol
    
    //si se desea trabajar con usuario en particular
    public function __construct($id=false,$key=false) {
        //2 opciones, 1. Del usuario en sesión o 2. De un usuario en particular
        if ($id){
            //Cuando se solicita los permisos de un usu en particular
            $this->_id = (int) $id;
           
            //$level = 5;//debe llegar por sesión
//            $level = $this->getRoleId($this->_id);
//            
            if($key === 1){           
//                echo "es colaborador";exit;
                    $this->_cli = '';
                }else{
                    $this->_cli = 'cli';
//                    echo "es cliente";exit;
                }
            
        }else{
            
            if(Session::get('id_usuario')){//si hay inicio de sesión de usuario
                //Cuando se solicita los permisos del usuario en sesión
                $this->_id = Session::get('id_usuario');//el id se va establecer en el valor
                
                //Según rol (emp o cli)
                if(Session::get('level') == 5){ 
                    $this->_cli = 'cli';
                }else{
                    $this->_cli = '';
                }
            }else{

                $this->_id = 0;//acceso restringido = 0 
            }
        } 
        
        $this->_db = new Database();
        $this->_role = $this->getRole();//lo llenamos con el construct
        $this->_permisos = $this->getPermisosRole();
        $this->compilarAcl();
    }
    
    public function compilarAcl(){
        //convina los arreglos de los permisos del role con los permisos de usuario
        
        //array_merge: toma el $this->_permiso el cual fue llenado en el constructor con los permisos del role
        //va sustituir los valores del arreglo del role con valores del arreglo del usuario
        //todas las claves en el arreglo role van a ser sustituida por los permisos del usuario
     $this->_permisos = array_merge($this->_permisos, $this->getPermisosUsuario());
     //echo '<pre>';print_r($this->_permisos);exit;
        
    }

//    public function getRoleId($idusu){
//        //devuelve el role del id que se solicita los permisos
//        $sql = $this->_db->query(
//                "select Id_role AS Id
//                 from usuario
//                 where Id_usu = {$idusu}
//                     AND Id_role IN (1,2,3,4)
//                 ");
//        if ($sql->rowCount() > 0) {
//            return 1;
//        } else {
//            return 0;
//        }
//    }
    
    public function getRole(){
        //devuelve el role del id que inicia sesión
        $sql = $this->_db->query(
                "select Id_role{$this->_cli} AS Id_role
                 from usuario{$this->_cli} 
                 where Id_usu{$this->_cli} = {$this->_id}"
                );
        $role = $sql->fetch();
        return $role['Id_role'];
    }
    
    public function getPermisosRoleId(){
        //devuelve los ids permisos relacionados con el role
        $sql =  $this->_db->query(
                "select Id_perm{$this->_cli} AS Id_perm 
                 from role{$this->_cli}_permiso 
                 where Id_role{$this->_cli} = '{$this->_role}'"
                );
       $ids = $sql->fetchAll(PDO::FETCH_ASSOC);
       
       $id = array();
       //creando un arreglo indexado con todos los ids permisos 
       //de la tabla role_permiso con relacionados al Id_role
       for($i =0; $i < count($ids); $i++){
           $id[] = $ids[$i]['Id_perm'];
       }
       return $id;
    }
    
    public function getPermisosRole(){
        //devuelve los permisos del role ya procesados
        //creamos un arreglo con información de permisos habilitados, si es heredado, etc.
        $sql = $this->_db->query(
                "SELECT p.Id_perm{$this->_cli} AS Id_perm,
                        Valor_perm_role{$this->_cli} AS Valor_perm_role,
                        Id_role{$this->_cli} AS Id_role,
                        Nom_perm{$this->_cli} AS Nom_perm
                 FROM role{$this->_cli}_permiso rp
                 LEFT JOIN permiso{$this->_cli} p ON (p.Id_perm{$this->_cli}=rp.Id_perm{$this->_cli})
                WHERE Id_role{$this->_cli} = '{$this->_role}' 
                ORDER BY Nom_perm{$this->_cli} ASC 
                ");
                
        $permisos = $sql->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        for($i = 0; $i < count($permisos); $i++){
          
            $key = $this->getPermisoKey($permisos[$i]['Id_perm']);
            if($key == ''){continue;}//en el caso que campo este vacio y no vaya hacer un hoyo en la seguridad
            
            if($permisos[$i]['Valor_perm_role'] == 1){
                $v = true;
            }else{
                $v = false;
            }
            
            $data[$key] = array( // arreglo asociativo con los mismos nombre de las llaves de los permisos
            'key' => $key,
            'permiso' => $this->getPermisoNombre($permisos[$i]['Id_perm']),
            'valor' => $v,
            'heredado' => true, //usado para saber si el usuario esta utilizando un permiso heredado del role o directamente desde la tabla permisos_usuario
            'id' => $permisos[$i]['Id_perm']
                );
        }
        
        return $data;//retornamos $data, contiene los permisos del ROLE****
    }
    
    public function getPermisoKey($permisoID){//devuelve el campo Id_key aa_aa
        $permisoID = (int) $permisoID;
        
        $sql = $this->_db->query(
                "select Id_perm{$this->_cli} AS Id_perm,
                        Key_perm{$this->_cli} AS Key_perm
                 from permiso{$this->_cli} 
                 where Id_perm{$this->_cli} = {$permisoID}"
                );
        $key = $sql->fetch();
        return $key['Key_perm']; //retorna la llave(campo Id_key) del permiso que pedimos
    }
    
    public function getPermisoNombre($permisoID){//devuelve el campo Id_key aa_aa
        $permisoID = (int) $permisoID;
        
        $sql = $this->_db->query(
                "select Id_perm{$this->_cli} AS Id_perm,
                        Nom_perm{$this->_cli} AS Nom_perm
                 from permiso{$this->_cli} 
                 where Id_perm{$this->_cli} = {$permisoID}"
                );
        $key = $sql->fetch();
        return $key['Nom_perm']; //retorna la llave(campo Id_key) del permiso que pedimos
    }
    
    public function getPermisosUsuario(){
        
        $ids = $this->getPermisosRoleId();//guardamos el array que contiene todos los id de permisos para el Role del usuario
        
        if(count($ids)){//verificamos si contiene valor para que no de error en la query
        // buscamos los permisos que tiene asignado el usuario
        $sql = $this->_db->query(
                "select Id_perm{$this->_cli} AS Id_perm,
                        Valor_perm_usu{$this->_cli} AS Valor_perm_usu 
                 from usuario{$this->_cli}_permiso 
                 where Id_usu{$this->_cli} = {$this->_id} 
                 and Id_perm{$this->_cli} in (" . implode(",", $ids) . ")"//implode pondrá los ids separados por coma entre las ""
                );//traera todos los permisos disponibles para ese usuario
        
        $permisos = $sql->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $permisos = array();
        }
        //hará lo mismo que $data de getPermisosRole()
        //diferencia que traera el valor del usuario
        $data = array();
        
        for($i=0;$i<count($permisos);$i++){
          
            $key = $this->getPermisoKey($permisos[$i]['Id_perm'{$this->_cli}]);
            if($key == ''){continue;}//en el caso que campo este vacio y no vaya hacer un hoyo en la seguridad
            
            if($permisos[$i]['Valor_perm_usu'] == 1){
                $v = true;
            }else{
                $v = false;
            }
            
            $data[$key] = array( // arreglo asociativo con los mismos nombre de las llaves de los permisos
            'key' => $key,
            'permiso' => $this->getPermisoNombre($permisos[$i]['Id_perm']),
            'valor' => $v,
            'heredado' => false, //usado para saber si el usuario esta utilizando un permiso heredado del role o directamente desde la tabla permisos_usuario
            'id' => $permisos[$i]['Id_perm']
                );
        }
        
        return $data;
    }
    
    public function getPermisos(){
        
        if(isset($this->_permisos) && count($this->_permisos))
            return $this->_permisos;
    }
    
    public function permiso($key){
        //utilizarlo en las vistas para tomar decisiones de acuerdo a si un usuario
        //si tiene cierto permiso o no lo tiene
        if(array_key_exists($key, $this->_permisos)){
            if($this->_permisos[$key]['valor'] == true || $this->_permisos[$key]['valor'] == 1){
                return true;
            }
        }
        
        return false;
    }
    
    public function acceso($key){
        // acceso($key, $codigoParaErrores)
        //utilizado en los controladores
        if($this->permiso($key)){
            Session::tiempo();//comienza a correr tiempo sesion
            return;
        }
        
        header("location:" . BASE_URL . "errores/index/access/5050");
        exit;//Corta el run para no ejecutar el método
    }
            
}
?>
