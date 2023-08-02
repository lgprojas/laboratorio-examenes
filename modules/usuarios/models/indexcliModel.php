<?php
//listar los usuario y poner un enlace hacia los permisos de usuario

class indexcliModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuariosCli($cond =''){
        
        $usuario = $this->_db->query(
                "SELECT u.Id_usucli,
                    CAST(AES_DECRYPT(Nom_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Nom_usucli,
                    u.Id_rolecli,                    
                    Id_estusucli,
                    Nom_rolecli
                 FROM usuariocli u
                 LEFT JOIN rolecli r ON (r.Id_rolecli=u.Id_rolecli)
                 LEFT JOIN cliente c ON(c.Id_cli=u.Id_cli)
                 WHERE c.Id_cli = u.Id_cli
                 $cond 
                 GROUP BY u.Id_usucli ASC , Nom_usucli ASC 
                 ");
                return $usuario->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUsuariosG(){
        
        $usuario = $this->_db->query(
                "SELECT u.*,Nom_role
                 FROM usuario u
                 LEFT JOIN role r ON (r.Id_role=u.Id_role)
                 WHERE u.Id_role NOT IN (1,2,3)
                 ");
                return $usuario->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getDatosUsuarioCli($usuarioID){
        
        $usuario = $this->_db->query(
                "SELECT c.Id_cli,
                       uc.Id_usucli,
                       CAST(AES_DECRYPT(Rut_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Rut_usucli,
                       CAST(AES_DECRYPT(Nom_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Nom_usucli,
                       CAST(AES_DECRYPT(Usu_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Usu_usucli,
                       CAST(AES_DECRYPT(Email_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Email_usucli,
                       uc.Id_rolecli,
                       uc.Id_estusucli,
                       r.*,
                       e.*
                FROM usuariocli uc 
                LEFT JOIN cliente c ON (c.Id_cli=uc.Id_cli) 
                LEFT JOIN rolecli r ON (uc.Id_rolecli=r.Id_rolecli) 
                LEFT JOIN est_usucli e ON (uc.Id_estusucli=e.Id_estusucli)
                WHERE uc.Id_usucli= $usuarioID"
                );
                return $usuario->fetch();
    }
    
    public function getPers($cond = false, $relusu = false){
        
        $pers = $this->_db->query(
                "SELECT Id_emp,
                       CAST(AES_DECRYPT(Rut_emp,'".ENCRYPT_KEY."')AS char(100)) AS Rut_emp,
                       CAST(AES_DECRYPT(Nom1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Nom1_emp,
                       CAST(AES_DECRYPT(Nom2_emp,'".ENCRYPT_KEY."')AS char(100)) AS Nom2_emp,
                       CAST(AES_DECRYPT(Ape1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape1_emp,
                       CAST(AES_DECRYPT(Ape2_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape2_emp 
                FROM empleado
                WHERE Id_emp NOT IN (SELECT Id_emp FROM usuario)
                ");
                return $pers->fetchAll();        
    }
    
    public function getAllRole($confcond=false){

        $per = $this->_db->query(
                "SELECT Id_role,
                        Nom_role               
                FROM role
                WHERE Id_role NOT IN ($confcond)                   
                ");
                return $per->fetchAll();
    }
    
    public function getRolesG(){
        
        $roles = $this->_db->query(
                "SELECT * FROM role WHERE Id_role NOT IN (1,2,3)"
                );
                return $roles->fetchAll();
    }
    
    public function getEstUsuCli(){
        
        $est = $this->_db->query(
                "select * from est_usucli"
                );
                return $est->fetchAll();        
    }
    
    public function editUsuarioCli(
                                    $idu=false,
                                    $rut=false,
                                    $nom=false,
                                    $usu=false,
                                    $email=false,
                                    $rol=false,
                                    $est=false
                                    ){
//        
//        $this->_db->prepare("SET @urut = :rut")
//        ->execute(array(
//            ':rut' => Session::get('rut_usu')
//            ));
//        
         $id = (int) $idu;
        $this->_db->prepare(
                "UPDATE usuario 
                SET Rut_usu = AES_ENCRYPT(:rut, '".ENCRYPT_KEY."'),
                    Nom_usu = AES_ENCRYPT(:nom, '".ENCRYPT_KEY."'),
                    Usu_usu = AES_ENCRYPT(:usu, '".ENCRYPT_KEY."'),
                    Email_usu = AES_ENCRYPT(:email, '".ENCRYPT_KEY."'),
                    Id_role = :rol, 
                    Id_estusu = :est
                WHERE Id_usu = :id
                ")->execute(array(
            ':id' => $id,
            ':rut' => $rut,
            ':nom' => $nom,
            ':usu' => $usu,
            ':email' => $email,
            ':rol' => $rol,
            ':est' => $est                    
        ));
    }   
    
    public function editPassUsuarioCli(
                                $idu=false,
                                $pass=false
                                ){
//        
//        $this->_db->prepare("SET @urut = :rut")
//        ->execute(array(
//            ':rut' => Session::get('rut_usu')
//            ));
//        
         $id = (int) $idu;
        $this->_db->prepare(
                "UPDATE usuariocli 
                SET                  
                    Pass_usucli = :pass 
                WHERE Id_usucli = :id
                ")->execute(array(
            ':id' => $id,
            ':pass' => Hash::getHash('sha1', $pass, HASH_KEY)
        ));
    }    
    
    //permisos usuario cliente

    public function getPermisosUsuarioCli($usuarioID=false){
        
        $acl = new ACL($usuarioID);
        return $acl->getPermisos();//devolverá los permisos de este usuario que enviemos($usuarioID)
    }
    
    public function getPermisosRoleCli($usuarioID=false){
        
        $acl = new ACL($usuarioID);
        return $acl->getPermisosRole();
    }
    
    public function eliminarPermiso($usuarioID=false, $permisoID=false){
        
        $this->_db->query(
                "delete from usuario_empmiso where " .
                "Id_usu = $usuarioID and Id_empm = $permisoID"
                );
    }
    
    public function editarPermiso($usuarioID=false, $permisoID=false, $valor=false){
        
         $this->_db->query(
                "replace into usuario_empmiso set " .
                "Id_usu = $usuarioID , Id_empm = $permisoID , Valor_empm_usu = '$valor'"
                );
    }
    
    public function elimUsuarioCli($usuarioID=false){
        
        $this->_db->prepare("SET @urut = :rut, @rol = :rol, @empresa = :empresa")
        ->execute(array(
            ':rut' => Session::get('rut_usu'),
            ':rol' => Session::get('level'),
            ':empresa' => Session::get('id_empresa')
            ));
        
        $this->_db->query(
                "DELETE FROM usuariocli WHERE " .
                "Id_usucli = $usuarioID"
                );
    }
}
    
?>
