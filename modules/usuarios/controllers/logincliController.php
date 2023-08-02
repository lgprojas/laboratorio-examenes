<?php

Class logincliController extends Controller{
    
    private $_logincli;

    public function __construct() {
        parent::__construct();
        $this->_logincli = $this->loadModel('logincli');
    }
    
    public function index(){
        
        if(Session::get('autenticado')){//verifica si esta logeado así no permitira entrar nuevamente al login           
            $this->redireccionar();
        }
        
        $this->_view->assign('titulo', 'Iniciar Sesión Cliente');
        
        $this->_view->assign('empresas', $this->_logincli->getAllEmpresas());
        
        $this->_view->setCss(array('style'));
        
        if($this->getInt('enviar') == 1){
            $this->_view->assign('datos', $_POST);
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->assign('_error', 'Debe introducir su nombre de usuario');
                $this->_view->renderizar('index','login');
                exit;        
            }
            
            if(!$this->getSql('pass')){
                $this->_view->assign('_error', 'Debe introducir su password');
                $this->_view->renderizar('index','login');
                exit;        
            }
            
            if(!$this->getSql('empresa')){
                $this->_view->assign('_error', 'Debe seleccionar empresa');
                $this->_view->renderizar('index','login');
                exit;        
            }
            
            ///procesamos datos de ingreso
            $row = $this->_logincli->getUsuarioCli(
                    $this->getAlphaNum('usuario'),
                    $this->getSql('pass'),
                    $this->getSql('empresa')
                    );
            
            if(!$row){
                $this->_view->assign('_error', 'Datos ingresados no son correctos');
                $this->_view->renderizar('index','logincli');
                exit;
            }
            
            if($row['Id_estusucli'] != 1 || $row['Id_activarcli'] != 1){
                $this->_view->assign('_error', 'Este usuario no esta habilitado');
                $this->_view->renderizar('index','logincli');
                exit;
            }
            
            Session::set('autenticado', true);
            Session::set('level', $row['Id_rolecli']);
            Session::set('nombre_usu', $row['Nom_usucli']);
            Session::set('usuario', $row['Usu_usucli']);
            Session::set('id_usuario', $row['Id_usucli']);
            Session::set('rut_usu', $row['Rut_usucli']);
            Session::set('id_cli', $row['Id_cli']);
            
            Session::set('id_empresa', $row['Id_empresa']);
            
            Session::set('idencript_cli', $this->encrypt($row['Id_cli']));
            Session::set('idencript_empresa', $this->encrypt($row['Id_empresa']));
            
            $nom_empresa = $this->_logincli->getNomEmpresa($row['Id_empresa']);
            Session::set('empresa', $nom_empresa);
            
            //notificaciones
            if(Session::get('level') == 5){
                $msj = $this->_logincli->getNotifCli(Session::get('id_cli'),Session::get('id_empresa'));
                Session::set('msj', $msj);
                
                $menu = $this->_logincli->getUltimasNotifCli(Session::get('id_cli'),Session::get('id_empresa'));
                for ($i = 0; $i < count($menu); $i++) {
                    $menu[$i]['Idcli_encrypt'] = $this->encrypt($menu[$i]['Id_cli']);
                    $menu[$i]['Idempresa_encrypt'] = $this->encrypt($menu[$i]['Id_empresa']);
                    $menu[$i]['Idaccion_encrypt'] = $this->encrypt($menu[$i]['Id_accion']);
                    $menu[$i]['Date_mov'] = $this->formatDateTimeOnlyDate($menu[$i]['Fch_mov']);
                    $menu[$i]['Time_mov'] = $this->formatDateTimeOnlyTime($menu[$i]['Fch_mov']);
                }
                Session::set('menu_noti', $menu);
            }  
            
            
            
            Session::set('tiempo', time());

            //para testear usar print_r($_SESSION);//comprueba si funciona sesión
            $this->redireccionar();
        }
        
        $this->_view->renderizar('index', 'logincli');
        
    }
    
    public function recuperarPassword(){
        
        if(Session::get('autenticado')){           
            $this->redireccionar();
        }
        
        $this->_view->assign('titulo', 'Recuperar Password');
        
        $this->_view->setJs(array('fun'));
        
        if($this->getInt('re') == 1){
            $this->_view->assign('datos', $_POST);
            
            //Elimina de la cadena todo lo que no sea 0-9 . - k K
            //Ejemplo recibe 15/(0-?´´. entrega 150-.
            if(!$this->getRUNPuntosYGuion('r')){
                $this->_view->assign('_error', 'Debe introducir su RUN');
                $this->_view->renderizar('recuperarPass','logincli');
                exit;        
            }
            
            if(!$this->poseeDosPuntosYUnGuion($this->getRUNPuntosYGuion('r'))){              
                $this->_view->assign('_error', 'El RUN no es válido');
                $this->_view->renderizar('recuperarPass','logincli');
                exit;        
            }
           
            $run = $this->quitarPuntosRUN($this->getRUNPuntosYGuion('r'));

            if(!$this->_logincli->verExisteCliRecuperar($run)){
                $this->_view->assign('_error', 'El RUN no existe');
                $this->_view->renderizar('recuperarPass','logincli');
                exit;
            }           
            
            $idcli = $this->_logincli->getIdCliRecuperar($run);
            
            if(!$this->_logincli->verExisteUsuCliRecuperar($idcli)){
                $this->_view->assign('_error', 'El usuario no existe');
                $this->_view->renderizar('recuperarPass','logincli');
                exit;
            } 
            
            //Enviar email cambiar password
            $email = $this->_logincli->getEmailCliRecuperar($idcli);
            $dcli = $this->_logincli->getDatosCliToEmailRecuperar($idcli);
            
            $code = date("Ymdhis");
            
            //Registramos el his_recuperar_pass_cli
            $idusu = $this->_logincli->getUsuCliRecuperar($idcli);
            $ok = $this->_logincli->saveHisRecuperarPassCli($idusu, $this->filtrarInt($code));
            
            $code_encrypt = $this->encrypt($code);
            $idusu_encrypt = $this->encrypt($idusu);

            if($ok == true){//guardo his
                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();
                $mail->SetFrom('notificador@pruebajuridico.athel.cl', 'Notificador Plataforma');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                $subject = '[Cambio de Password] Se ha generado el proceso de cambio de password desde nuestra plataforma Jurídica.';
                $subject = "=?UTF-8?B?".base64_encode($subject)."=?=";
                $mail->Subject = $subject;

                $mail->Body = '
                            <div style="box-sizing:border-box;background-color:#c00134;width:640px;margin:auto;padding:10px" align="center">
                                <p style="color:white;font-size: 30px;">Cambio de Password</p>
                            </div>


                            <div class="" style="box-sizing:border-box;width:640px;background-color:#ffffff;border-bottom-width:1px;border-bottom-color:#c4c6cf;border-bottom-style:solid;margin:auto;padding:34px 36px 52px" align="center">

                            <p style="box-sizing:border-box;font-weight:500;font-size:24px;line-height:32px;color:#232942;margin:34px 0 32px" align="center">Estimado(a) '.$dcli['Nom1_cli'].' '.$dcli['Ape1_cli'].' '.$dcli['Ape2_cli'].'</p>
                            <p style="box-sizing:border-box;font-weight:500;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:0" align="center">Nuestro servicio tiene información para usted:</p>
                            <p class="m_8758230702427326819paragraph-bold" style="box-sizing:border-box;font-weight:700;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:32px 0" align="left">Proceso de creación de nuevo password en nuestra plataforma.</p>
                            <div style="box-sizing:border-box" align="justify">
                            <p style="box-sizing:border-box;font-weight:500;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:0" align="center"></p>
                            <p style="box-sizing:border-box;margin:0"><span style="color:#222222;box-sizing:border-box">Estimado cliente,</span></p>

                            <p style="box-sizing:border-box;margin:0"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box">Junto con saludar, informamos que se ha solicitado cambio de password en nuestra plataforma. </span></span></p>
                            <p style="box-sizing:border-box;margin:0"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box">Sólo resta hacer clic en el siguiente enlace y definir su nuevo password: </span></span></p>
                            <p></p>
                            <p style="box-sizing:border-box;margin:0;text-align: center;"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box"><a href="https://www.pruebajuridico.athel.cl/usuarios/logincli/nuevoPasswordUsuCli/'.$idusu_encrypt.'/'.$code_encrypt.'" alt="Nuevo password" target="_blank" rel="noopener noreferrer">Crear un nuevo password aquí</a></span></span></p>
                            <p></p>
                            <p></p>
                            <p style="box-sizing:border-box;margin:0"><span style="color:#222222;box-sizing:border-box">Atte.</span></p>
                            <p></p>
                            <p style="box-sizing:border-box;margin:0"><span style="color:#222222;box-sizing:border-box">Notificador de Sistema Jurídico.</span></p>

                            </div>
                            <div style="box-sizing:border-box;margin-top:52px">
                            <div style="box-sizing:border-box;background-color:#ffffff;width:170px;font-weight:700;letter-spacing:0.4px;margin:auto" align="center">
                            <p style="box-sizing:border-box;margin:12px 0 0px">
                            Nombre empresa
                            </p>
                            </div>

                            </div>
                            </div>

                            <div class="" style="box-sizing:border-box;background-color:white;color:#4c516d;letter-spacing:0.3px;width:640px;margin:auto;padding:20px 36px" align="center">
                            <p style="box-sizing:border-box;margin:0">

                            <p style="box-sizing:border-box;margin:0">
                            Correo generado automáticamente desde Plataforma Jurídica.';   

                $mail->AltBody = 'Su servidor de correo no soporta HTML';
                $mail->AddAddress($email);
                $mail->Send();
                //enviar email activación cuenta cliente

                Session::setMensaje("Se le ha enviado un email a su correo para continuar con el proceso.");
                $this->redireccionar('usuarios/logincli/index/');
                exit;
                
            }else{
                
                Session::setError("Sucedió un error en el proceso de restauración de password.");
                $$this->redireccionar('usuarios/logincli/index/');
                exit;
            }
        }
        
        $this->_view->renderizar('recuperarPass', 'logincli');
    }
    
    public function nuevoPasswordUsuCli($idusu=false,$code=false) {
        
        $usu = $this->decrypt($idusu);
        $cod = $this->decrypt($code);

        
        $this->_view->assign('titulo', 'Crear nuevo Password');
        
        if(!$this->filtrarInt($usu)){
            $this->_view->assign('_error', 'Error faltan datos');
                $this->_view->renderizar('newPassCli', 'logincli');
                exit;
        }
        
        if(!$this->filtrarInt($cod)){
            $this->_view->assign('_error', 'Error faltan datos');
                $this->_view->renderizar('newPassCli', 'logincli');
                exit;
        }
        
        $estLink = $this->_logincli->verificarEstRestauracionPassCli(
                                        $this->filtrarInt($usu),
                                        $this->filtrarInt($cod)
                                        );
        if($estLink == 1){
                Session::setError("El link ya caducó.");
                $this->redireccionar('usuarios/logincli/index/');
                exit;
        }
        
        $this->_view->setJs(array('pass'));
        
        if($this->getPostParam('guardar') == 1){
            $this->_view->assign('datos1', $_POST);
            
            if(!$this->getPostParam('pass1')){
                $this->_view->assign('_error', 'Debe introducir el nuevo password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(strlen($this->getPostParam('pass1')) < 8){
                $this->_view->assign('_error', 'Debe introducir mínimo 8 caracteres como password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(!$this->getPostParam('pass2')){
                $this->_view->assign('_error', 'Debe introducir nuevamente el nuevo password');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if(strlen($this->getPostParam('pass2')) < 8){
                $this->_view->assign('_error', 'Debe introducir mínimo 8 caracteres como password en ambas casillas');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }
            if($this->getPostParam('pass1') !== $this->getPostParam('pass2')){
                $this->_view->assign('_error', 'Los valores introducidos de ambas casillas deben coincidir');
                $this->_view->renderizar('editpasscli', 'indexcli');
                exit;
            }

            $this->_logincli->editPassUsuarioCliRestaurar(
                    $usu,
                    $this->getPostParam('pass2')
                    );
            
            $ok = $this->_logincli->updateHisRecuperarPassCli($usu,$cod);
            
            if($ok == true){
                Session::setMensaje("Cambio realizado con éxito. Ya puede iniciar sesión con su nuevo password.");
                $this->redireccionar('usuarios/logincli');
                exit;
            }else{
                Session::setError("No se pudo realizar el registro del nuevo password.");
                $this->redireccionar('usuarios/logincli');
                exit;
            }
        }
        
        
        $this->_view->renderizar('newPassCli', 'logincli');
    }
    
    public function cerrar(){
        
        Session::destroy();//2 var Session::destroy(array('var1', 'var2'));
        $this->redireccionar();//$this->redireccionar('login/mostrar');
    }
}

?>
