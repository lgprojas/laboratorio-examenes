<?php

class registrocliModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function verificarUsuarioCli($usuario = false){
        //verifica si ya existe el usuario en la base de datos
        $id = $this->_db->query(
                        "SELECT Id_usucli, 
                                Cod_usucli 
                        FROM usuariocli 
                        WHERE Usu_usucli = AES_ENCRYPT('$usuario', '".ENCRYPT_KEY."')"
                );
        return $id->fetch();
    }
    
    public function verificarEmailCli($email = false){
        //verifica si ya existe el usuario en la base de datos
        $id = $this->_db->query(
                        "SELECT Id_usucli 
                        FROM usuariocli
                        WHERE Email_usucli = AES_ENCRYPT('$email', '".ENCRYPT_KEY."')"
                );
        return $id->fetch();
    }
    
    public function getDatosCliRegistro($cli=false,$empresa=false){
       
        $u = $this->_db->query("
            SELECT 
                CAST(AES_DECRYPT(Rut_cli,'".ENCRYPT_KEY."')AS char(100)) AS a,
                CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(100)) AS b,
                CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(100)) AS c,
                CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(100)) AS d,
                CAST(AES_DECRYPT(Email_cli,'".ENCRYPT_KEY."')AS char(100)) AS e
            FROM cliente 
            WHERE Id_cli = $cli
                AND Id_empresa = $empresa
                ");
        
        $u->setFetchMode(PDO::FETCH_ASSOC);
        return $u->fetch();
    }
    
    public function registrarUsuarioCli($run, 
                                         $nombre, 
                                         $usuario, 
                                         $password, 
                                         $email, 
                                         $role, 
                                         $estusu, 
                                         $cli, 
                                         $empresa){
        
        $random = rand(111111111, 999999999);
        
        $this->_db->prepare("SET @urut = :rut, @rol = :rol, @empresa = :empresa")
        ->execute(array(
            ':rut' => Session::get('rut_usu'),
            ':rol' => Session::get('level'),
            ':empresa' => Session::get('id_empresa')
            ));
        
        $this->_db->prepare(
                "INSERT INTO usuariocli VALUES" . 
                "(null, 
                   AES_ENCRYPT(:Rut_usucli, '".ENCRYPT_KEY."'), 
                   AES_ENCRYPT(:Nom_usucli, '".ENCRYPT_KEY."'),
                   AES_ENCRYPT(:Usu_usucli, '".ENCRYPT_KEY."'),                   
                   :Pass_usucli, 
                   AES_ENCRYPT(:Email_usucli, '".ENCRYPT_KEY."'),
                    NOW(),
                   :Cod_usucli,                     
                   :Id_rolecli, 
                   :Id_estusucli,
                   :Id_activarcli,
                   :Id_cli,
                   :Id_empresa                   
                   )")
                ->execute(array(
                    ':Rut_usucli' => $run,                    
                    ':Nom_usucli' => $nombre,
                    ':Usu_usucli' => $usuario,
                    ':Pass_usucli' => Hash::getHash('sha1', $password, HASH_KEY),
                    ':Email_usucli' => $email,
                    ':Cod_usucli' => $random,                    
                    ':Id_rolecli' => $role,
                    ':Id_estusucli' => 2,
                    ':Id_activarcli' => 1,
                    ':Id_cli' => $cli,
                    ':Id_empresa' => $empresa
                ));
    }
    
    //Emailing Activar cuenta
    public function getEmailCliActivar($id=false){
        
        $sql = $this->_db->query("
        SELECT 
        CAST(AES_DECRYPT(Email_cli,'".ENCRYPT_KEY."')AS char(150)) AS Email
        FROM usuariocli uc
        LEFT JOIN cliente c ON (c.Id_cli=uc.Id_cli)
        WHERE Id_usucli = $id
                                ");
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Email'];
    }
    
    public function getDatosCliToEmailActivar($idcli=false){
        
        $id = (int) $idcli;
        $sql = $this->_db->query("
        SELECT 
        CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
        CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
        CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli
        FROM usuariocli uc
        LEFT JOIN cliente c ON (c.Id_cli=uc.Id_cli)
        WHERE Id_usucli = $id
                                ");
        return $sql->fetch();
        
    }
    
    public function getUsuarioCliRegistrado($id = false, $codigo = false){
        
        $usuario = $this->_db->query(
                                "SELECT * 
                                 FROM usuariocli
                                 WHERE Id_usucli = $id 
                                   AND Cod_usucli = '$codigo'"
                );
        return $usuario->fetch();// devuelve los datos en un array
    }
    
    public function activarUsuarioCli($id, $codigo){
        
        $this->_db->query(
                "UPDATE usuariocli SET Id_estusucli = 1, Id_activarcli = 1 " .
                "WHERE Id_usucli = $id AND Cod_usucli = '$codigo'"
                );
    }
    
    public function getAllCli($empresa=false){

        $per = $this->_db->query(
                "SELECT Id_cli,
                       CAST(AES_DECRYPT(Rut_cli,'".ENCRYPT_KEY."')AS char(100)) AS Rut_cli,
                       CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(100)) AS Nom1_cli,
                       CAST(AES_DECRYPT(Nom2_cli,'".ENCRYPT_KEY."')AS char(100)) AS Nom2_cli,
                       CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(100)) AS Ape1_cli,
                       CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(100)) AS Ape2_cli                    
                FROM cliente
                WHERE Id_empresa = $empresa
                    AND Id_cli NOT IN (SELECT Id_cli FROM usuariocli)
                ");
                return $per->fetchAll();
    }
    
    public function getAllPerJson($cond = false, $relusu=0){

        $per = $this->_db->query(
                "SELECT Id_emp AS a,
                       CAST(AES_DECRYPT(Nom1_emp,'".ENCRYPT_KEY."')AS char(100)) AS d,
                       CAST(AES_DECRYPT(Nom2_emp,'".ENCRYPT_KEY."')AS char(100)) AS e,
                       CAST(AES_DECRYPT(Ape1_emp,'".ENCRYPT_KEY."')AS char(100)) AS b,
                       CAST(AES_DECRYPT(Ape2_emp,'".ENCRYPT_KEY."')AS char(100)) AS c                    
                FROM empleado
                WHERE Id_emp NOT IN (SELECT Id_emp FROM usuario)
                ");
                return $per->fetchAll();
    }
    
    public function getAllRJson($confcond=false){

        $per = $this->_db->query(
                "SELECT Id_role AS a,
                        Nom_role AS b               
                FROM role
                WHERE Id_role NOT IN ($confcond)                   
                ");
                return $per->fetchAll();
    }
    
    public function getAllRoles(){
        
        $roles = $this->_db->query(
                "SELECT * FROM role"
                );
                return $roles->fetchAll();
    }
    
    public function getAllRolesG(){
        
        $roles = $this->_db->query(
                "SELECT * FROM role WHERE Id_role NOT IN (1,2)"
                );
                return $roles->fetchAll();
    }
    
    public function getAllEstUsu(){
        
        $estusu = $this->_db->query(
                "SELECT * FROM est_usu"
                );
                return $estusu->fetchAll();
    }
}

?>
