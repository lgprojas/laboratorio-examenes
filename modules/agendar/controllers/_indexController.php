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
      $this->_view->setJs(array('fun'));
      
      $this->_view->assign('bateria', $this->_index->getBaterias());
      $this->_view->assign('examen', $this->_index->getExamenes());
      
      if($this->getInt('guardar') == 1){
          
        $id = 2;//cantidad de personas

      //Session::destroy(Session::get('carro'));
      
      if(Session::get('carro')){//si ya existe un carro creado
          Session::destroy('carro');
          echo 'hola1';exit;
          var_dump(Session::get('carro'));
//            if($this->filtrarInt($id)){
//                $carro = $_SESSION['carro'];
//                $encontro = false;
//                $numero = 0;
//                
//                for($i=0;$i < count($carro);$i++){
//                    if($carro[$i]['Id'] == $this->filtrarInt($id)){
//                        $encontro = true;
//                        $numero = $i;
//                    }
//                }
//                if($encontro == true){
//                    $carro[$numero]['Cant'] = $carro[$numero]['Cant'] + $cant;
//                    $_SESSION['carro'] = $carro;//si existe actualiza cant prod en el carro
//
//                }  else {
//                    //var_dump($_SESSION);
//                    $row = $this->_carro->getDatosProd($this->filtrarInt($id));
//                    
//                    $ref = md5($row['Cod_prod']);
//                    $cantidad = $cant;
//                    $subtotal = $row['Preini_prod'] * $cant;
//                    $identif = $row['Id_prod'];
//                    $nombre = $row['Nom_prod'];
//                    $precio = $row['Preini_prod'];
//                    $image = $row['Nom_img'];
//                    $codigo = $row['Cod_prod'];
//                    
//                    $nuevoProd = array(
//                                    'Ref'=>$ref,
//                                    'Cant'=>$cantidad,
//                                    'Subtotal'=> $subtotal,
//                                    'Id'=>$identif,
//                                    'Nombre'=>$nombre,
//                                    'Precio'=>$precio,
//                                    'Image'=>$image,
//                                    'Cod'=>$codigo
//                                    );
//                    array_push($carro, $nuevoProd);//si no existe prod agrega nuevo prod
//                    $_SESSION['carro'] = $carro; 
//                }
//            }
        }  else {
            //si no existe un carro creado aún
            //echo 'hola2';exit;
            if(!empty($id)){
                
                for ($i = 0; $i <= count($id); $i++) {
                    $valor = $i + 1;
//                    foreach($this->getPostParam("pres_{$valor}") as $prestaciones) {
//                     $pres[] = $prestaciones;
//                    }

                    $carro[] = array(
                                    'Ref'=> $valor,
                                    'Fono'=> $this->getPostParam("fono{$valor}"),
                                    'Prestaciones'=> $this->getPostParam("pres_{$valor}"),        
                                    );
                }
                Session::set('carro', $carro); 
                var_dump(Session::get('carro'));exit;
        }
        
        }
        
        if(Session::get('carro')){
            echo 'hola3';exit;
            
            $carro = $_SESSION['carro'];
            $subtotal = 0;
            $total = 0;
            for($i=0;$i < count($carro);$i++){
                $subtotal = $carro[$i]['Cant'] * $carro[$i]['Precio'];
                $total = $total + $subtotal;
            }
        }
      }

      $this->_view->renderizar('index');
    }
    
}
