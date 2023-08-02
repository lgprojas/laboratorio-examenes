<?php

class indexController extends agendarController {
    
    private $_index;
    
    public function __construct() {
        parent::__construct();
        $this->_index =  $this->loadModel('index');
    }
    
    public function index(){
        
      $this->_view->assign('titulo', 'Agendar');
      
      $this->_view->setCss(array('style'));
      $this->_view->setJsPlugin(array('jquery-ui-1.10.3.custom'));
      $this->_view->setJsPlugin(array('jquery-ui-1.10.3.custom.min'));
      $this->_view->setCssPlugin(array('jquery-ui-1.10.3.custom'));
      $this->_view->setJs(array('fun'));
      
      $this->_view->setJs(array('bootstrap-waitingfor'));      
      $this->_view->setJs(array('ajax'));

      
//      if($this->getInt('ver') == 1){
//          $this->_view->assign('datos', $_POST);
//            
//            if (!$this->getPostParam('ciu')){
//                $this->_view->assign('_error', 'Debe seleccionar ciudad');
//                $this->_view->renderizar('index');
//                exit;
//            }      
//            if (!$this->getPostParam('date')){
//                $this->_view->assign('_error', 'Debe seleccionar fecha');
//                $this->_view->renderizar('index');
//                exit;
//            }   
//                
//            $this->redireccionar('agendar/index/reservar/'.$this->getPostParam('ciu').'/'.$this->getPostParam('date'));
//            exit;
//          
//      }
      
      $this->_view->renderizar('index');
    }
    
    public function getAllHoursReserved(){  
        
        $ide = 1;//idempresa
        
        if($ide){            
         
            $lab = $this->getInt('lab');
            $fch = $this->formatDate($this->getPostParam('fch'));

            $hours = $this->_index->getAllHoursReserved($ide,$lab,$fch);
            for($i=0;$i < count($hours);$i++){
                $hours[$i]['Trab'] = $this->_index->getAllCliEmpresa($ide);
                $hours[$i]['Baterias'] = $this->_index->getBateriasCupo($hours[$i]['Id_reserva']);
                $hours[$i]['Examenes'] = $this->_index->getExamenesCupo($hours[$i]['Id_reserva']);
                $hours[$i]['Total_prest'] = $this->_index->getTotalPrest($hours[$i]['Id_reserva']);
                
                $hours[$i]['AllBaterias'] = $this->_index->getAllBaterias();
                $hours[$i]['AllExamenes'] = $this->_index->getAllExamenes();
            }
        
        
        echo json_encode($hours);
        }
    }
    
    public function getAllHours(){  
        
        if($this->getInt('lab')){
         
            $lab = $this->getInt('lab');
            $fch = $this->formatDate($this->getPostParam('fch'));

            $hours = $this->_index->getAllHours();

            for($i=0;$i < count($hours);$i++){
                $ocupados = $this->_index->getAllCuposHour($hours[$i]['Id_hora'],$lab,$fch);
                $total = (CUPOSDIA - $ocupados);
                $hours[$i]['Cupos'] = $total;
               
            }
        
        
        echo json_encode($hours);
        }
    }
    
    public function itsok() {          
        //verifica cant cupos disponibles otra vez
        //cuando es un sólo array new[]
        
        $lab = $this->getSql('lab');
        $fch = $this->formatDate($this->getPostParam('fch'));
        $hora = $this->getSql('hora');

        $nomHora = $this->_index->getNomHora($hora);
        $result = $this->_index->verCantCuposHora($lab,$fch,$hora);
        $new['Hora'] = $nomHora;
        $new['Total'] = (CUPOSDIA - $result);

        echo json_encode($new);
    }
    
    public function snc() {                         
        
        $lab = $this->getSql('lab');
        $fch = $this->formatDate($this->getPostParam('fch'));
        $cant = $this->getSql('cant');
        $hora = $this->getSql('hora');
        $empresa = 1;
        $usu = 1;
        
        //el for es jquery       
        //aquí sólo se almacena
        $codEmpresa = $this->_index->getCodEmpresa($empresa);
        $codEmp = strtolower($codEmpresa);
        
        $data = array();
        
        for($i=0;$i < $cant;$i++){
            //cod reserva
            $idLast = $this->_index->getLastIdReserva();
            $idreserva = $idLast + 1;
            $newcod = str_pad($idreserva, 6, "0", STR_PAD_LEFT);
            $cod = 're-'.$codEmp.'-'.$newcod;
            
            $this->_index->addNewReserva(
                                         $cod,
                                         $fch,                                            
                                         $hora,
                                         $lab,
                                         $empresa,
                                         $usu
                                        );
            
            $data[$i]['add'] = "1";
            $data[$i]['idr'] = $this->_index->getIdReserva($cod);
            $data[$i]['hora'] = $this->_index->getNomHoraReserva($hora);
            $data[$i]['trab'] = $this->_index->getAllCliEmpresa($empresa);
            
            $data[$i]['AllBaterias'] = $this->_index->getAllBaterias();
            $data[$i]['AllExamenes'] = $this->_index->getAllExamenes();
    
        }
        
        echo json_encode($data);
        
    }
    
    public function editTrabCupo() {                         
        
        $idreserva = $this->getSql('id');
        $idcli = $this->getSql('opcion');
        
        $respuesta = $this->_index->editCliReserva(
                                         $idreserva,                                            
                                         $idcli
                                        );
        
            if ($respuesta == true){
                
                //Session::setMensaje("Editado correctamente.");
                $data = ['valor' => "1",
                         'mssg' => "Editado correctamente."
                ];  
                
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($data);
                exit;
            }else{
                Session::setError("No se pudo editar correctamente.");
                $this->redireccionar('agendar/index/index/');
                exit;
            }

        echo json_encode($data);
        
    }
    
    public function editPrestRerserva() {                         
        
        $idreserva = $this->getSql('id');
        $idpres = $this->getSql('pres');
            
            $existe = $this->_index->verExistePrestReserva(
                                         $idreserva,                                            
                                         $idpres
                                        );
            
            if($existe){
                
                $respuesta = $this->_index->quitarPrestReserva(
                                         $idreserva,                                            
                                         $idpres
                                        );
        
                if ($respuesta == true){
                    
                    $total = $this->_index->getTotalPrest($idreserva);
                    
                    //Session::setMensaje("Editado correctamente.");
                    $data = ['valor' => "1",
                             'cod' => "$idreserva",
                             'total' => "$total",
                             'mssg' => "Quitado correctamente."
                    ];  

                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($data);
                    exit;
                }else{
                    $data = ['valor' => "2",
                             'cod' => "$idreserva",
                             'mssg' => "No se pudo quitar correctamente."
                    ];  

                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($data);
                    exit;
                } 
                
            }else{
                
                $respuesta = $this->_index->addPrestReserva(
                                         $idreserva,                                            
                                         $idpres
                                        );
        
                if ($respuesta == true){

                    $total = $this->_index->getTotalPrest($idreserva);
                    
                    //Session::setMensaje("Editado correctamente.");
                    $data = ['valor' => "1",
                             'cod' => "$idreserva",
                             'total' => "$total",
                             'mssg' => "Agregado correctamente."
                    ];  

                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($data);
                    exit;
                }else{
                    $data = ['valor' => "2",
                             'cod' => "$idreserva",
                             'mssg' => "No se pudo agregar."
                    ];  

                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($data);
                    exit;
                }
                
            }
            
            

        echo json_encode($data);
        
    }
    
    
    
    
    
    
    public function reservar($ciu=false,$fch=false) {
        
        $this->_view->assign('titulo', 'Reservar');
        
        //debe listar los cupos por hora y dar la opción de seleccionar al trabajador en cada cupo
        
        $this->_view->renderizar('reservar');
    }
    
}
