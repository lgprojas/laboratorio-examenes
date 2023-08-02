<?php

class loginModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuario($usuario, $password){
        
        $datos = $this->_db->query(
                "SELECT Id_usu,
                        CAST(AES_DECRYPT(Rut_usu,'".ENCRYPT_KEY."')AS char(100)) AS Rut_usu,
                        CAST(AES_DECRYPT(Nom_usu,'".ENCRYPT_KEY."')AS char(100)) AS Nom_usu,
                        CAST(AES_DECRYPT(Usu_usu,'".ENCRYPT_KEY."')AS char(100)) AS Usu_usu,
                        Pass_usu,
                        CAST(AES_DECRYPT(Email_usu,'".ENCRYPT_KEY."')AS char(100)) AS Email_usu,
                        Id_role,
                        Id_estusu,
                        Id_activar,
                        Id_emp,
                        Id_empresa
                 FROM usuario
                 WHERE Usu_usu = AES_ENCRYPT('$usuario', '".ENCRYPT_KEY."') 
                 AND Pass_usu = '" . Hash::getHash('sha1', $password, HASH_KEY) ."'               
                ");
        
        return $datos->fetch();
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
    
    //notificaciones    
    public function getNotifEmp($idemp=false,$empresa=false) {
        
        $sql = $this->_db->query("
            SELECT COUNT(Id_usermov) AS Total
            FROM user_mov 
            WHERE Id_user = $idemp
                AND Id_tuser = 1
                AND Id_empresa = $empresa
                ");
        $t = $sql->fetch(PDO::FETCH_ASSOC);
        return $t['Total'];
    }
    
    public function getUltimasNotifSup($idemp=false,$empresa=false) {
        //donde cliente o responsable hayan accionado en causas donde incumbe al sup
        $sql = $this->_db->query("
            SELECT m.*,
                   tm.*,
                   CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
                   CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
                   CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli,
                   CAST(AES_DECRYPT(Nom1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Nom1_emp,
                   CAST(AES_DECRYPT(Ape1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape1_emp,
                   CAST(AES_DECRYPT(Ape2_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape2_emp
            FROM movimiento m
            LEFT JOIN tipo_mov tm ON(tm.Id_tmov=m.Id_tmov)
            LEFT JOIN cliente c ON(c.Id_cli=m.Id_cli)
            LEFT JOIN empleado e ON(e.Id_emp=m.Id_emp)
            WHERE (Id_codrol = 2
                    AND Id_autor IN (
                            SELECT Id_cli
                            FROM causa
                            WHERE Id_coord = $idemp
                            GROUP BY Id_cli
                    ))
                   OR
                   (Id_codrol = 1
                    AND Id_autor IN (
                            SELECT Id_resp
                            FROM causa
                            WHERE Id_coord = $idemp
                            GROUP BY Id_resp
                    ))
                   OR
                    (Id_codrol = 2
                    AND Id_autor IN (
                            SELECT Id_cli
                            FROM consulta
                            WHERE Id_coord = $idemp
                            GROUP BY Id_cli
                    ))
                   OR
                   (Id_codrol = 1
                    AND Id_autor IN (
                            SELECT Id_resp
                            FROM consulta
                            WHERE Id_coord = $idemp
                            GROUP BY Id_resp
                    ))
                    AND e.Id_empresa = $empresa
            ORDER BY Fch_mov DESC
            LIMIT 3
                ");
        return $sql->fetchAll();
    }
    
    public function getUltimasNotifAbo($idemp=false,$empresa=false) {
        
        $sql = $this->_db->query("
            SELECT m.*,
                   tm.*,
                   CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
                   CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
                   CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli,
                   CAST(AES_DECRYPT(Nom1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Nom1_emp,
                   CAST(AES_DECRYPT(Ape1_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape1_emp,
                   CAST(AES_DECRYPT(Ape2_emp,'".ENCRYPT_KEY."')AS char(100)) AS Ape2_emp
            FROM movimiento m
            LEFT JOIN tipo_mov tm ON(tm.Id_tmov=m.Id_tmov)
            LEFT JOIN cliente c ON(c.Id_cli=m.Id_cli)
            LEFT JOIN empleado e ON(e.Id_emp=m.Id_emp)
            WHERE (Id_codrol = 2
                    AND Id_autor IN (
                            SELECT Id_cli
                            FROM causa
                            WHERE Id_resp = $idemp
                            GROUP BY Id_cli
                    ))
                   OR
                   (Id_codrol = 1
                    AND Id_autor IN (
                            SELECT Id_coord
                            FROM causa
                            WHERE Id_resp = $idemp
                            GROUP BY Id_coord
                    ))
                   OR
                    (Id_codrol = 2
                    AND Id_autor IN (
                            SELECT Id_cli
                            FROM consulta
                            WHERE Id_resp = $idemp
                            GROUP BY Id_cli
                    ))
                   OR
                   (Id_codrol = 1
                    AND Id_autor IN (
                            SELECT Id_coord
                            FROM consulta
                            WHERE Id_resp = $idemp
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
