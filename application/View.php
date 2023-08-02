<?php
require_once ROOT . 'libs' . DS . 'smarty' . DS . 'libs' . DS . 'Smarty.class.php';

class View extends Smarty{
    
    private $_request;
    private $_js;
    private $_css;
    private $_jsb;
    private $_cssb;
    private $_acl;
    //$this->_aclcli
    private $_rutas;
    private $_jsPlugin;
    private $_cssPlugin;

    public function __construct(Request $peticion, ACL $_acl) {
        parent::__construct();
        $this->_request = $peticion;
        $this->_js = array();
        $this->_css = array();
        $this->_jsb = array();
        $this->_cssb = array();
        $this->_acl = $_acl;//inicializamos $_acl
        //$this->_aclcli = $_aclcli;//inicializamos $_aclcli
        $this->_rutas = array();
        $this->_jsPlugin = array();
        $this->_cssPlugin = array();
        
        $modulo = $this->_request->getModulo();
        $controlador = $this->_request->getControlador();
        
        if($modulo){//verificamos si hay un módulo
            
            $this->_rutas['view'] = ROOT . 'modules' . DS . $modulo . DS . 'views' . DS . $controlador . DS;//ruta vistas modules
            $this->_rutas['js'] = BASE_URL . 'modules/' . $modulo. '/views/' . $controlador . '/js/';
            $this->_rutas['css'] = BASE_URL . 'modules/' . $modulo. '/views/' . $controlador . '/css/';
        }else{
            $this->_rutas['view'] = ROOT . 'views' . DS . $controlador . DS;//ruta vistas modules
            $this->_rutas['js'] = BASE_URL . 'views/' . $controlador . '/js/';
            $this->_rutas['css'] = BASE_URL . 'views/' . $controlador . '/css/';
        
        }
    }
    
    public function renderizar($vista, $item = false, $noLayout = false){
         //para que trabaje la libreria smarty--
        $this->template_dir = ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS;//template por defecto en smarty
        $this->config_dir = ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'configs' . DS;
        $this->cache_dir = ROOT . 'tmp' . DS . 'cache' . DS;
        $this->compile_dir = ROOT . 'tmp' . DS . 'template' . DS;
         //para que trabaje la libreria smarty--
        //menu
        //if(Session::get('autenticado')){
            $home = array(0 => array(
                                  'id' => 'Home',
                                  'title' => ' Home',
                                  'enlace' => BASE_URL,
                                  'imagen' => 'glyphicon glyphicon-home',
                                  'children' => Array()
                                  ),
                       );
            
            $menu_cl = array(0 => array(
                                      'id' => 'mi-area',
                                      'title' => 'Mi área',
                                      'enlace' => '#' ,
                                      'imagen' => 'glyphicon glyphicon-tasks',
                                      'children' => array(0 => array(
                                                              'id' => 'agendar',
                                                              'title' => 'Agendar',
                                                              'enlace' => BASE_URL . 'agendar/',
                                                              'imagen' => 'glyphicon glyphicon-home',
                                                              'children' => Array()
                                                              ),
                                                      ),
                                        )
            );
            

        
        
        $_params = array(
             'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/',
             'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/',
             'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
             'ruta_icons' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/bootstrap-icons/',
            'home' => $home,
            'menu_cl' => $menu_cl,
            'menu_ab' => $menu_ab,
            'menu_su' => $menu_su,
            'menu_je' => $menu_je,
            'menu_ad' => $menu_ad,
            'item' => $item,
            'js' => $this->_js,
            'jsb' => $this->_jsb,
            'js_plugin' => $this->_jsPlugin,
            'css_plugin' => $this->_cssPlugin,
            'css' => $this->_css,
            'cssb' => $this->_cssb,
            'root' => BASE_URL,
            'configs' => array(
                'app_name' => APP_NAME,
                'app_slogan' => APP_SLOGAN,
                'app_company' => APP_COMPANY
            )
         );
        
        //echo '<pre>'; print_r($this->_rutas); exit;// test View
        //echo $this->_rutas['view'] . $vista . '.tpl';exit;
        if(is_readable($this->_rutas['view'] . $vista . '.tpl')){      
            if($noLayout){
                $this->template_dir = $this->_rutas['view'];
                $this->display($this->_rutas['view'] . $vista . '.tpl');
                exit;
            }
            $this->assign('_contenido', $this->_rutas['view'] . $vista . '.tpl');//por defecto vista index.tpl en views global
        }else{
            throw new Exception('Error de vista');
        }
        
        $this->assign('_acl', $this->_acl);//enviamos el objeto Acl a las vistas por method assign
        $this->assign('_layoutParams', $_params);
        $this->display('template.tpl');
    }
    
    public function setJs(array $js){//llama los .js en views especificas
        
        if(is_array($js) && count($js)){
            for($i=0;$i < count($js);$i++){
                $this->_js[] =  $this->_rutas['js']. $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js');
        }
    }
    
    public function setJsb(array $jsb){
        
        if(is_array($jsb) && count($jsb)){
            for($i=0;$i < count($jsb);$i++){
                $this->_jsb[] =  $this->_rutas['js']. $jsb[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js');
        }
    }
    
    public function setJsPlugin(array $js){
        
        if(is_array($js) && count($js)){
            for($i=0; $i < count($js); $i++){
                $this->_jsPlugin[] = BASE_URL . 'public/js/' . $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js Plugin');
        }
    }

    public function setCssPlugin(array $css){
        
        if(is_array($css) && count($css)){
            for($i=0; $i < count($css); $i++){
                $this->_cssPlugin[] = BASE_URL . 'public/css/' . $css[$i] . '.css';
            }
        }else{
            throw new Exception('Error de css Plugin');
        }
    }

    
    public function setCss(array $css){//llama los .js en views especificas
        
        if(is_array($css) && count($css)){
            for($i=0;$i < count($css);$i++){
                $this->_css[] =  $this->_rutas['css']. $css[$i] . '.css';
            }
        }else{
            throw new Exception('Error de css');
        }
    }
    
    public function setCssb(array $cssb){
        
        if(is_array($cssb) && count($cssb)){
            for($i=0;$i < count($cssb);$i++){
                $this->_cssb[] =  $this->_rutas['css']. $cssb[$i] . '.css';
            }
        }else{
            throw new Exception('Error de css');
        }
    }
    
}
    
?>
