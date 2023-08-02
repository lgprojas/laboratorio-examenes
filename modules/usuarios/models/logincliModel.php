<?php

class logincliModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuarioCli($usuario, $password, $empresa){
        
        $datos = $this->_db->query(
                "SELECT Id_usucli,
                        Id_cli,
                        CAST(AES_DECRYPT(Rut_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Rut_usucli,
                        CAST(AES_DECRYPT(Nom_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Nom_usucli,
                        CAST(AES_DECRYPT(Usu_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Usu_usucli,
                        Pass_usucli,
                        CAST(AES_DECRYPT(Email_usucli,'".ENCRYPT_KEY."')AS char(100)) AS Email_usucli,
                        Id_rolecli,
                        Id_estusucli,
                        Id_activarcli,
                        Id_empresa                       
                 FROM usuariocli
                 WHERE Usu_usucli = AES_ENCRYPT('$usuario', '".ENCRYPT_KEY."') 
                 AND Pass_usucli = '" . Hash::getHash('sha1', $password, HASH_KEY) ."' 
                 AND Id_empresa = $empresa
                ");
        
        return $datos->fetch();
    }
    
    public function getAllEmpresas() {
        
        $sql = $this->_db->query("
                                    SELECT *
                                    FROM empresa
                                   ");        
        
        return $sql->fetchAll();
    }

    public function getNomEmpresa($idempresa=false) {
        
        $sql = $this->_db->query("
                                    SELECT Nom_empresa
                                    FROM empresa
                                    WHERE Id_empresa = $idempresa
                                   ");        
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Nom_empresa'];
    }
    
    //recuperar pass
    
    public function verExisteCliRecuperar($run = false){

        $id = $this->_db->query("
                        SELECT Id_cli
                        FROM cliente 
                        WHERE Rut_cli = AES_ENCRYPT('$run', '".ENCRYPT_KEY."')
                        ");
        return $id->fetch();
    }
    
    public function getIdCliRecuperar($run=false) {
        
        $sql = $this->_db->query("
                        SELECT Id_cli AS Id
                        FROM cliente 
                        WHERE Rut_cli = AES_ENCRYPT('$run', '".ENCRYPT_KEY."')
                                   ");        
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Id'];
    }
    
    public function verExisteUsuCliRecuperar($idcli = false){

        $id = (int) $idcli;
        $sql = $this->_db->query("
                        SELECT Id_usucli
                        FROM usuariocli
                        WHERE Id_cli = $id
                        ");
        return $sql->fetch();
    }
    
    //emailing
    public function getEmailCliRecuperar($idcli=false){
        
        $id = (int) $idcli;
        $sql = $this->_db->query("
        SELECT 
        CAST(AES_DECRYPT(Email_cli,'".ENCRYPT_KEY."')AS char(150)) AS Email
        FROM cliente
        WHERE Id_cli = $id
                                ");
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Email'];
    }    
    
    public function getDatosCliToEmailRecuperar($idcli=false){
        
        $id = (int) $idcli;
        $sql = $this->_db->query("
        SELECT 
        CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
        CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
        CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli
        FROM cliente
        WHERE Id_cli = $id
                                ");
        return $sql->fetch();
        
    }
    
    public function getUsuCliRecuperar($idcli=false) {
        
        $id = (int) $idcli;
        $sql = $this->_db->query("
                        SELECT Id_usucli AS Id
                        FROM usuariocli
                        WHERE Id_cli = $id
                                   ");        
        $sq = $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Id'];
    }
            
    public function saveHisRecuperarPassCli($idusu=false,$code=false){    

        $id = (int) $idusu;
        $cod = (int) $code;
        $sql = $this->_db->prepare(
                "INSERT INTO his_restaurar_pass_cli VALUES" . 
                "(NULL, NOW(), 2, :Cod_hrpcli, :Id_usucli)"               
                )
                ->execute(array(
                    ':Cod_hrpcli' => $cod,
                    ':Id_usucli' => $id
                ));
        
        if ($sql) { 
            return true;
        }
    }
    
    //Renovar password 
    public function verificarEstRestauracionPassCli($idusu=false,$cod=false) {
     
        $id = (int) $idusu;
        $sql = $this->_db->query("
                        SELECT Est_hrpcli AS Est
                        FROM his_restaurar_pass_cli
                        WHERE Id_usucli = $id
                            AND Cod_hrpcli = '$cod'
                                   ");        
        $sq = $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Est'];
    }
    
    public function editPassUsuarioCliRestaurar(
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
    
    public function updateHisRecuperarPassCli($idusu=false,$cod=false){    
                
        $sql = $this->_db->prepare("
                UPDATE his_restaurar_pass_cli 
                SET                  
                    Est_hrpcli = 1 
                WHERE Id_usucli = :id
                    AND Cod_hrpcli = :cod
                ")->execute(array(
                            ':id' => $idusu,
                            ':cod' => $cod
                        ));
        
        if ($sql) { 
            return true;
        }
    }
    
    //---- Notificaciones--------------------------------------------
    
    public function getNotifCli($idcli=false,$empresa=false) {
        
        $sql = $this->_db->query("
            SELECT COUNT(Id_usermov) AS Total
            FROM user_mov 
            WHERE Id_user = $idcli
                AND Id_tuser = 2
                AND Id_empresa = $empresa
                ");
        $t = $sql->fetch(PDO::FETCH_ASSOC);
        return $t['Total'];
    }
    
    public function getUltimasNotifCli($idcli=false,$empresa=false) {
        //donde coord o resp hayan accionado en causas donde incumbe al cliente
        $sql = $this->_db->query("
        SELECT m.*,
               tm.*,
               CAST(AES_DECRYPT(Nom1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Nom1_emp,
               CAST(AES_DECRYPT(Ape1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape1_emp,
               CAST(AES_DECRYPT(Ape2_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape2_emp
        FROM movimiento m
        LEFT JOIN tipo_mov tm ON(tm.Id_tmov=m.Id_tmov)
        LEFT JOIN cliente c ON(c.Id_cli=m.Id_cli)
        LEFT JOIN empleado e ON(e.Id_emp=m.Id_emp)
        WHERE (Id_codrol = 1
                AND Id_autor IN (
                        SELECT Id_resp
                        FROM causa
                        WHERE Id_cli = $idcli
                        GROUP BY Id_resp
                ))
               OR
               (Id_codrol = 1
                AND Id_autor IN (
                        SELECT Id_coord
                        FROM causa
                        WHERE Id_cli = $idcli
                        GROUP BY Id_coord
                ))
               OR
                (Id_codrol = 1
                AND Id_autor IN (
                        SELECT Id_resp
                        FROM consulta
                        WHERE Id_cli = $idcli
                        GROUP BY Id_resp
                ))
               OR
               (Id_codrol = 1
                AND Id_autor IN (
                        SELECT Id_coord
                        FROM consulta
                        WHERE Id_cli = $idcli
                        GROUP BY Id_coord
                ))
                AND e.Id_empresa = $empresa
        ORDER BY Fch_mov DESC
        LIMIT 3
                ");
        return $sql->fetchAll();
    }
}
?>
