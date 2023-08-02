<?php

class NOTIFICACIONES{
    
    
    private $_db;//guardamos un objeto de la BD
    private $_cat;//guardamos la categoría de la notificación
    private $_usu;//guardamos la id del usuario
    private $_tusu;//Si es cli o emp
    private $_emp;//el id del emp
    private $_cli;//el id del cli
    private $_accion;//el id de la acción
    private $_rol;//el id de la acción
    private $_empresa;//el id de la empresa
    
    //si se desea trabajar con usuario en particular
    public function __construct($cat=false,$tusu=false,$idaccion=false) {
        
        if ($cat){
            //Cuando se solicita los permisos de un usu en particular
            $this->_cat = (int) $cat;
            $this->_usu = Session::get('id_usuario');
            $this->_rol = Session::get('level');
            $this->_empresa = Session::get('id_empresa');
            $this->_accion = $idaccion;
            
            $this->_db = new Database();
            
            if($tusu === 1){           
                    //emp
                    $this->_tusu = 1;
                    $this->_emp = $this->getIdEmp();
                }else{
                    //cli
                    $this->_tusu = 2;
                    $this->_cli = $this->getIdCli();
                }
        } 
    }

    
    public function getIdEmp() {
        
        $sql = $this->_db->query(
                "SELECT Id_emp AS Id
                 FROM usuario
                 WHERE Id_usu = {$this->_usu}
                     AND Id_empresa = {$this->_empresa}
                 ");
        $role = $sql->fetch();
        return $role['Id'];
    }
    
    public function getIdCli() {
        
        $sql = $this->_db->query(
                "SELECT Id_cli AS Id
                 FROM usuariocli
                 WHERE Id_usucli = {$this->_usu}
                     AND Id_empresa = {$this->_empresa}
                 ");
        $role = $sql->fetch();
        return $role['Id'];
    }
    
    public function usuNotifica(){

        switch ($this->_cat) {
            case 1:
                //Creó causa
                //debe obtener el id del cliente y el del supervisor
                //para agregarlos a la tabla user_mov
                $u = $this->getUsuNotificaCausa();
                for ($i = 0; $i < count($u); $i++) {
                    $this->insertUserMov($u[$i]['Id_tuser'],
                                         $u[$i]['Id_user'],
                                         $this->_empresa
                                         );
                }
                
                break;
            case 2:
                //Creó hito
                //debe obtener el id del cliente, del abogado y del supervisor
                //para agregarlos a la tabla user_mov
                $u = $this->getUsuNotificaHito();
                for ($i = 0; $i < count($u); $i++) {
                    $this->insertUserMov($u[$i]['Id_tuser'],
                                         $u[$i]['Id_user'],
                                         $this->_empresa
                                         );
                }
                
                break;
            case 3:
                //Creó consulta
                //debe obtener el id del cliente, del abogado y del supervisor
                //para agregarlos a la tabla user_mov
                $u = $this->getUsuNotificaConsulta();
                for ($i = 0; $i < count($u); $i++) {
                    $this->insertUserMov($u[$i]['Id_tuser'],
                                         $u[$i]['Id_user'],
                                         $this->_empresa
                                         );
                }
                
                
                break;
            case 4:
                //Creó hito consulta
                //debe obtener el id del cliente, del abogado y del supervisor
                //para agregarlos a la tabla user_mov
                $u = $this->getUsuNotificaHitoConsulta();
                for ($i = 0; $i < count($u); $i++) {
                    $this->insertUserMov($u[$i]['Id_tuser'],
                                         $u[$i]['Id_user'],
                                         $this->_empresa
                                         );
                }

                break;
            default:
                break;
        }
    }
    
    public function insertUserMov($tuser,$user,$empresa) {
        
        $this->_db->prepare(
                "INSERT INTO user_mov VALUES" . 
                "(NULL, :Id_tuser, :Id_user, :Id_empresa)"               
                )
                ->execute(array(
                    ':Id_tuser' => $tuser,
                    ':Id_user' => $user,
                    ':Id_empresa' => $empresa
                ));
    }
    
    public function getUsuNotificaCausa() {
        //toma el id del usuario con sesion que se supone genera la acción
        //Se debe identificar si es abo o sup
        //se debe insertar a los otros 2 actores de la causa
        //nueva actualización: no necesariamente quien crea es asociado a la consulta
        //se debe notificar a todos no más
        
//        if($this->_rol == 3){
//            $otro = 'Id_resp';
//        }elseif($this->_rol == 4){
//            $otro = 'Id_coord';
//        }
        
        $roles = $this->_db->query("
            SELECT 1 AS Id_tuser, Id_resp AS Id_user
            FROM causa 
            WHERE Id_causa = {$this->_accion}
                AND Id_empresa = {$this->_empresa}
             UNION
            SELECT 1 AS Id_tuser, Id_coord AS Id_user
            FROM causa 
            WHERE Id_causa = {$this->_accion}
                AND Id_empresa = {$this->_empresa}
             UNION
            SELECT 2 AS Id_tuser, Id_cli AS Id_user
            FROM causa
            WHERE Id_causa = {$this->_accion}
                AND Id_empresa = {$this->_empresa}
            ");
            return $roles->fetchAll();
        
    }
    
    public function getUsuNotificaHito() {
        //toma el id del usuario con sesion que se supone genera la acción
        //Se debe identificar si es abo o sup
        //se debe insertar a los otros 2 actores del hito (asociados a la causa)
        //nueva actualización: no necesariamente quien crea es asociado a la consulta
        //se debe notificar a todos no más
        
        if($this->_rol == 3){
            $opcion = 1;
            //$otro = 'Id_resp';
        }elseif($this->_rol == 4){
            $opcion = 1;
            //$otro = 'Id_coord';
        }elseif($this->_rol == 5) {
            $opcion = 2;
        }
        
        //selecciona a quiénes notificar
        if($opcion == 1){
            
            $roles = $this->_db->query("
                SELECT 1 AS Id_tuser, Id_resp AS Id_user
                FROM causa 
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 1 AS Id_tuser, Id_coord AS Id_user
                FROM causa 
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 2 AS Id_tuser, Id_cli AS Id_user
                FROM causa
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                ");
            return $roles->fetchAll();
            
        }elseif($opcion == 2){
            
            $roles = $this->_db->query("
                SELECT 1 AS Id_tuser, Id_coord AS Id_user
                FROM causa 
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 1 AS Id_tuser, Id_resp AS Id_user
                FROM causa
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                ");
            return $roles->fetchAll();
            
        }
        
    }
    
    public function getUsuNotificaConsulta() {
        //toma el id del usuario con sesion que se supone genera la acción
        //Se debe identificar si es abo o sup
        //se debe insertar a los otros 2 actores del hito (asociados a la consulta)
        //Si es coordinador quien crea, se toma el id del responsable sino viceversa
        //Si es cliente quien crea, se toma los id de los 2 empleados
        //nueva actualización: no necesariamente quien crea es asociado a la consulta
        //se debe notificar a todos no más
        
        if($this->_rol == 3){
            $opcion = 1;
            //$otro = 'Id_resp';
        }elseif($this->_rol == 4){
            $opcion = 1;
            //$otro = 'Id_coord';
        }elseif($this->_rol == 5){
            $opcion = 2;
        }
        
        //selecciona a quiénes notificar
        if($opcion == 1){
            //selecciona al otro emp y al cliente
            $roles = $this->_db->query("
                SELECT 1 AS Id_tuser, Id_resp AS Id_user
                FROM consulta 
                WHERE Id_consulta = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 1 AS Id_tuser, Id_coord AS Id_user
                FROM causa 
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 2 AS Id_tuser, Id_cli AS Id_user
                FROM consulta
                WHERE Id_consulta = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                ");
            return $roles->fetchAll();
        
        }elseif($opcion == 2){
            //verificamos si la consulta posee coordinador
            //evidenteme no existe pero igual se comprueba
            $poseeCoord = $this->_db->query("
                            SELECT Id_coord AS Id
                            FROM consulta
                            WHERE Id_consulta = {$this->_accion}
                                AND Id_empresa = {$this->_empresa}
                            ");
            $sq = $poseeCoord->fetch(PDO::FETCH_ASSOC);
        
            if($sq['Id'] != NULL){ 
                
                $roles = $this->_db->query("
                    SELECT 1 AS Id_tuser, Id_coord AS Id_user
                    FROM consulta 
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                     UNION
                    SELECT 1 AS Id_tuser, Id_resp AS Id_user
                    FROM consulta
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                    ");
                return $roles->fetchAll();
                
            }else{
                //selecciona sólo al resp
                $roles = $this->_db->query("
                    SELECT 1 AS Id_tuser, Id_resp AS Id_user
                    FROM consulta
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                    ");
                return $roles->fetchAll();
            }
            
        }
        
    }
    
    public function getUsuNotificaHitoConsulta() {
        //toma el id del usuario con sesion que se supone genera la acción
        //Se debe identificar si es abo o sup
        //se debe insertar a los otros 2 actores del hito (asociados a la consulta)
        //Si es coordinador quien crea, se toma el id del responsable sino viceversa
        //Si es cliente quien crea, se toma los id de los 2 empleados
        //nueva actualización: no necesariamente quien crea es asociado a la consulta
        //se debe notificar a todos no más
        
        if($this->_rol == 3){
            $opcion = 1;
            //$otro = 'Id_resp';
        }elseif($this->_rol == 4){
            $opcion = 1;
            //$otro = 'Id_coord';
        }elseif($this->_rol == 5){
            $opcion = 2;
        }
        
        //selecciona a quiénes notificar
        if($opcion == 1){
            //selecciona al otro emp y al cliente
            $roles = $this->_db->query("
                SELECT 1 AS Id_tuser, Id_resp AS Id_user
                FROM consulta 
                WHERE Id_consulta = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 1 AS Id_tuser, Id_coord AS Id_user
                FROM causa 
                WHERE Id_causa = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                 UNION
                SELECT 2 AS Id_tuser, Id_cli AS Id_user
                FROM consulta
                WHERE Id_consulta = {$this->_accion}
                    AND Id_empresa = {$this->_empresa}
                ");
            return $roles->fetchAll();
        
        }elseif($opcion == 2){
            //verificamos si la consulta posee coordinador
            //ya en este punto la consulta puede ya poseer un coord
            //recordemos que si la consulta la crea el cli no asigna coord
            $poseeCoord = $this->_db->query("
                            SELECT Id_coord AS Id
                            FROM consulta
                            WHERE Id_consulta = {$this->_accion}
                                AND Id_empresa = {$this->_empresa}
                            ");
            $sq = $poseeCoord->fetch(PDO::FETCH_ASSOC);
        
            if($sq['Id'] != NULL){ 
                
                $roles = $this->_db->query("
                    SELECT 1 AS Id_tuser, Id_coord AS Id_user
                    FROM consulta 
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                     UNION
                    SELECT 1 AS Id_tuser, Id_resp AS Id_user
                    FROM consulta
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                    ");
                return $roles->fetchAll();
                
            }else{
                //selecciona sólo al resp
                $roles = $this->_db->query("
                    SELECT 1 AS Id_tuser, Id_resp AS Id_user
                    FROM consulta
                    WHERE Id_consulta = {$this->_accion}
                        AND Id_empresa = {$this->_empresa}
                    ");
                return $roles->fetchAll();
            }
            
        }
        
    }
}
?>
    