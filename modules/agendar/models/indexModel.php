<?php

class indexModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllHoursReserved($ide=false,$lab=false,$fch=false){

        $sql = $this->_db->query("SELECT r.*,
                                         CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
                                         CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
                                         CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli,
                                         Nom_hora
                                 FROM reserva r
                                 LEFT JOIN cliente c ON(c.Id_cli=r.Id_cli)
                                 LEFT JOIN hora h ON(h.Id_hora=r.Id_hora)
                                 WHERE r.Id_empresa = $ide
                                     AND Id_lab = $lab
                                     AND Fch_reserva = '$fch'
                                 ORDER BY r.Id_hora ASC
                                ");
        return $sql->fetchAll();
    }
    
    public function getBateriasCupo($reserva=false){

        $sql = $this->_db->query("SELECT a.Id_prestacion,
                                         Nom_prestacion
                                 FROM atencion a
                                 LEFT JOIN prestacion p ON(p.Id_prestacion=a.Id_prestacion)
                                 WHERE Id_reserva = $reserva
                                     AND Id_tprestacion = 1
                                ");
        return $sql->fetchAll();
    }
    
    public function getExamenesCupo($reserva=false){

        $sql = $this->_db->query("SELECT a.Id_prestacion,
                                         Nom_prestacion 
                                 FROM atencion a
                                 LEFT JOIN prestacion p ON(p.Id_prestacion=a.Id_prestacion)
                                 WHERE Id_reserva = $reserva
                                     AND Id_tprestacion = 2
                                ");
        return $sql->fetchAll();
    }
    
    public function getTotalPrest($reserva=false){

        $sql = $this->_db->query("SELECT COUNT(Id_prestacion) AS Total
                                 FROM atencion 
                                 WHERE Id_reserva = $reserva
                                ");
        $idc = $sql->fetch(PDO::FETCH_ASSOC);
        return $idc['Total'];
    }
    
    //modal para reservar cupo
    public function getAllHours(){

        $sql = $this->_db->query("SELECT * 
                                 FROM hora
                                ");
        return $sql->fetchAll();
    }
    
    public function getNomHora($idhora=false){

        $sql = $this->_db->query("SELECT Nom_hora AS Nom
                                  FROM hora
                                  WHERE Id_hora = $idhora
                ");
        $idc = $sql->fetch(PDO::FETCH_ASSOC);
        return $idc['Nom'];
    }
    
    public function getAllCuposHour($idhora=false,$lab=false,$fch=false){

        $sql = $this->_db->query("SELECT COUNT(Id_reserva) AS Total
                                  FROM reserva 
                                  WHERE Id_hora = $idhora
                                        AND Id_lab = $lab
                                        AND Fch_reserva = '$fch'
                ");
        $idc = $sql->fetch(PDO::FETCH_ASSOC);
        return $idc['Total'];
    }
    
    public function verCantCuposHora($lab=false,$fch=false,$hora=false){

        $sql = $this->_db->query("SELECT COUNT(Id_reserva) AS Reservadas                                        
                                  FROM reserva r
                                  LEFT JOIN hora h ON(h.Id_hora=r.Id_hora)
                                  WHERE Id_lab = $lab
                                      AND Fch_reserva = '$fch'
                                      AND r.Id_hora = $hora
                                ");
        $idc = $sql->fetch(PDO::FETCH_ASSOC);
        return $idc['Reservadas'];
    }
    
    public function getCodEmpresa($empresa=false){

        $sql = $this->_db->query("SELECT Cod_empresa AS Cod
                                 FROM empresa 
                                 WHERE Id_empresa = $empresa
                                ");
        $idc = $sql->fetch(PDO::FETCH_ASSOC);
        return $idc['Cod'];
    }
    
    public function getLastIdReserva(){
        
        $sql = $this->_db->query("SELECT Id_reserva AS Id
                                    FROM reserva
                                    ORDER BY Id_reserva DESC
                                    LIMIT 1
                                ");
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Id'];
    }
    
    public function addNewReserva(
                                 $cod=false,
                                 $fch=false,                                            
                                 $hora=false,
                                 $lab=false,
                                 $empresa=false,
                                 $usu=false
                               ){    
                
        $this->_db->prepare(
                "INSERT INTO reserva VALUES(
                    NULL, 
                    :Cod_reserva,
                    NOW(),
                    :Fch_reserva,
                    NULL,
                    :Id_hora,
                    :Id_lab,
                    :Id_empresa,
                    :Id_usu
                )")
                ->execute(array(
                    ':Cod_reserva' => $cod,
                    ':Fch_reserva' => $fch,
                    ':Id_hora' => $hora,
                    ':Id_lab' => $lab,
                    ':Id_empresa' => $empresa,
                    ':Id_usu' => $usu
                ));
        
    }
    
    public function getIdReserva($cod=false){
        
        $sql = $this->_db->query("SELECT Id_reserva AS Id
                                    FROM reserva
                                    WHERE Cod_reserva = '$cod'
                                ");
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Id'];
    }
    
    public function getNomHoraReserva($idhora=false){
        
        $id = (int) $idhora;
        $sql = $this->_db->query("SELECT Nom_hora AS Nom
                                    FROM hora
                                    WHERE Id_hora = $id
                                ");
        $sq= $sql->fetch(PDO::FETCH_ASSOC);
        return $sq['Nom'];
    }
    
    //Cupos nuevos reservados por config Trabajador y Prestaciones
   
    public function getAllCliEmpresa($empresa=false){

        $sql = $this->_db->query("SELECT Id_cli,
                                         CAST(AES_DECRYPT(Nom1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Nom1_cli,
                                         CAST(AES_DECRYPT(Ape1_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape1_cli,
                                         CAST(AES_DECRYPT(Ape2_cli,'".ENCRYPT_KEY."')AS char(150)) AS Ape2_cli
                                 FROM cliente 
                                 WHERE Id_empresa = $empresa
                                ");
        return $sql->fetchAll();
    }
    
    public function getAllBaterias(){

        $sql = $this->_db->query("SELECT Id_prestacion,
                                         Nom_prestacion
                                 FROM prestacion 
                                 WHERE Id_tprestacion = 1
                                ");
        return $sql->fetchAll();
    }
    
    public function getAllExamenes(){

        $sql = $this->_db->query("SELECT Id_prestacion,
                                         Nom_prestacion 
                                 FROM prestacion 
                                 WHERE Id_tprestacion = 2
                                ");
        return $sql->fetchAll();
    }
    
    public function editCliReserva($idreserva=false, $idcli=false){
        
        $id = (int) $idreserva;
        $cli = (int) $idcli;
        
        $sql = $this->_db->prepare("
                UPDATE reserva SET 
                    Id_cli = :cli 
                WHERE Id_reserva = :id
                    ")
                ->execute(array(
                    ':id' => $id,
                    ':cli' => $cli
                ));
        
        if ($sql) { 
            return true;
        }
    }
    
    //-- editar prestaciones reserva
    
    public function verExistePrestReserva($idr=false,$idpres=false){
        
        $id = (int) $idr;
        $pres = (int) $idpres;
        
        $sql = $this->_db->query("
                    SELECT Id_prestacion 
                    FROM atencion 
                    WHERE Id_reserva = $id
                        AND Id_prestacion = $pres
                ");
        return $sql->fetch();
    }
    
    public function quitarPrestReserva($idr=false,$idpres=false){

        $id = (int) $idr;
        $pres = (int) $idpres;
        
        $sql = $this->_db->query("DELETE FROM atencion 
                            WHERE Id_reserva=$id
                                AND Id_prestacion=$pres
                            ");
        if ($sql) { 
            return true;
        }
    }
    
    public function addPrestReserva($idr=false,$idpres=false){    
              
        $id = (int) $idr;
        $pres = (int) $idpres;
        
        $sql = $this->_db->prepare(
                "INSERT INTO atencion VALUES" . 
                "(NULL, NOW(), :Id_prestacion, :Id_reserva)"               
                )
                ->execute(array(
                    ':Id_prestacion' => $pres,
                    ':Id_reserva' => $id
                ));
        
        if ($sql) { 
            return true;
        }
        
    }
}