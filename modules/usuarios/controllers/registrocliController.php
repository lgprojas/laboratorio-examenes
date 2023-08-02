<?php

class registrocliController extends Controller{
    
    private $_registrocli;
    
    public function __construct() {
        parent::__construct();
        
        $this->_registrocli = $this->loadModel('registrocli');
    }
    
    public function index($idempresa=false) {
        
        if(!Session::get('autenticado')){$this->redireccionar();}
        $this->_acl->acceso('crear_usucli');
        
        $this->_view->assign('titulo', 'Registro');
        
        $empresa = $this->decrypt($idempresa);
        
        $this->_view->assign('Idempresa_encrypt', $idempresa);
        
        $this->_view->assign('cli', $this->_registrocli->getAllCli($empresa));            
        $this->_view->assign('estusu', $this->_registrocli->getAllEstUsu());
        
        $this->_view->setJs(array('ajax'));
        
        if($this->getInt('enviar') == 1){
            $this->_view->assign('datos', $_POST);
            
            if(!$this->getPostParam('run')){
                $this->_view->assign('_error', 'Debe ingresar RUN cliente');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if(!$this->getSql('nombre')){
                $this->_view->assign('_error', 'Debe introducir su nombre cliente');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if(!$this->getAlphaNum('usuario')){
                $this->_view->assign('_error', 'Debe introducir su nombre usuario');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if($this->_registrocli->verificarUsuarioCli($this->getAlphaNum('usuario'))){
                $this->_view->assign('_error', 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if($this->_registrocli->verificarEmailCli($this->getPostParam('email'))){
                $this->_view->assign('_error', 'El email ' . $this->getAlphaNum('email') . ' ya se encuentra registrado');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if(!$this->getSql('pass')){
                $this->_view->assign('_error', 'Debe introducir su password');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                $this->_view->assign('_error', 'Los passwords no coinciden');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            if(!$this->getSql('cli')){
                $this->_view->assign('_error', 'Debe seleccionar la persona para el usuario');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            
            $this->_registrocli->registrarUsuarioCli(
                    $this->getPostParam('run'),                    
                    $this->getSql('nombre'),
                    $this->getAlphaNum('usuario'),
                    $this->getSql('pass'),
                    $this->getPostParam('email'),
                    5,
                    1,                    
                    $this->getSql('cli'),
                    1
                    );
            
            $usuario = $this->_registrocli->verificarUsuarioCli($this->getAlphaNum('usuario'));
            
            if(!$usuario){
                $this->_view->assign('_error', 'Error al registrar el usuario');
                $this->_view->renderizar('index', 'registro');
                exit;
            }
            
            //enviar email activación cuenta cliente
                $email = $this->_registrocli->getEmailCliActivar($usuario['Id_usucli']);
                $dcli = $this->_registrocli->getDatosCliToEmailActivar($usuario['Id_usucli']);
                $idusu_encrypt = $this->encrypt($usuario['Id_usucli']);
                $codUsu = $usuario['Cod_usucli'];
                        
                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();
                $mail->SetFrom('notificador@pruebajuridico.athel.cl', 'Notificador Plataforma');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                
                $subject = '[Notificación] Se ha creado su cuenta de usuario en nuestra plataforma Jurídica.';
                $subject = "=?UTF-8?B?".base64_encode($subject)."=?=";
                $mail->Subject = $subject;

                $mail->Body = '
                            <div style="box-sizing:border-box;background-color:#363b3e;width:640px;margin:auto;padding:10px" align="center">
                                <p style="color:white;font-size: 30px;">Notificación</p>
                            </div>


                            <div class="" style="box-sizing:border-box;width:640px;background-color:#ffffff;border-bottom-width:1px;border-bottom-color:#c4c6cf;border-bottom-style:solid;margin:auto;padding:34px 36px 52px" align="center">
                            
                            <p style="box-sizing:border-box;font-weight:500;font-size:24px;line-height:32px;color:#232942;margin:34px 0 32px" align="center">Estimado(a) '.$dcli['Nom1_cli'].' '.$dcli['Ape1_cli'].' '.$dcli['Ape2_cli'].'</p>
                            <p style="box-sizing:border-box;font-weight:500;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:0" align="center">Nuestro servicio tiene información para usted:</p>
                            <p class="m_8758230702427326819paragraph-bold" style="box-sizing:border-box;font-weight:700;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:32px 0" align="left">Creación de su cuenta de usuario en nuestra plataforma.</p>
                            <div style="box-sizing:border-box" align="justify">
                            <p style="box-sizing:border-box;font-weight:500;font-size:14px;line-height:22px;letter-spacing:0.25px;color:#4c516d;margin:0" align="center"></p>
                            <p style="box-sizing:border-box;margin:0"><span style="color:#222222;box-sizing:border-box">Estimado cliente,</span></p>

                            <p style="box-sizing:border-box;margin:0"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box">Junto con saludar, informamos que se ha registrado con éxito su cuenta de usuario en nuestra plataforma. </span></span></p>
                            <p style="box-sizing:border-box;margin:0"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box">Sólo nos queda la activación de su cuenta. Para activar su cuenta, sólo debe hacer clic en el siguiente enlace: </span></span></p>
                            <p></p>
                            <p style="box-sizing:border-box;margin:0;text-align: center;"><span style="background-color:white;box-sizing:border-box"><span style="color:#222222;box-sizing:border-box"><a href="https://www.pruebajuridico.athel.cl/usuarios/registrocli/activarCli/'.$idusu_encrypt.'/'.$codUsu.'" alt="Activar cuenta" target="_blank" rel="noopener noreferrer">Activar su cuenta de usuario aquí</a></span></span></p>
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
            
            $this->_view->assign('datos', false);//luego de completado se vacien los campos
            
            if(Session::get('level') == 1){
                Session::setMensaje("Usuario cliente registrado correctamente, sugerir revisar email para activar cuenta.");
                $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
                exit;
            }else{
                Session::setMensaje("Usuario cliente registrado correctamente, sugerir revisar email para activar cuenta.");
                $this->redireccionar('usuarios/indexcli/index/'.$idempresa);
                exit;
            }
            //$this->_view->assign('_mensaje', 'Registro Completado, revise su email para activar su cuenta');
        }
        
        $this->_view->renderizar('index', 'registrocli');
    }
    
    public function gdc(){
        
        if($this->getInt('cli')){
            
        $empresa = $this->decrypt($this->getPostParam('empresa'));
            
        $datos = $this->_registrocli->getDatosCliRegistro(
                                                       $this->getInt('cli'),
                                                       $empresa
                                                       );
        }
        
        echo json_encode($datos);
    }
    
    public function activarCli($idcli, $codigo){
        
        $id = $this->decrypt($idcli);
        
        if(!$this->filtrarInt($id) || !$this->filtrarInt($codigo)){
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro'); 
            exit;
        }
        $row = $this->_registrocli->getUsuarioCliRegistrado(
                        $this->filtrarInt($id),
                        $this->filtrarInt($codigo)
                        );
        
        if(!$row){
            $this->_view->assign('_error', 'Esta cuenta no existe');
            $this->_view->renderizar('activar', 'registro'); 
            exit;
        }
        
        if($row['Id_estusucli'] == 1){
            $this->_view->assign('_error', 'Esta cuenta ya ha sido activada');
            $this->_view->renderizar('activar', 'registro'); 
            exit;
        }
        
        $this->_registrocli->activarUsuarioCli(
                    $this->filtrarInt($id),
                    $this->filtrarInt($codigo)
                );
        
        $row1 = $this->_registrocli->getUsuarioCliRegistrado(
                            $this->filtrarInt($id),
                            $this->filtrarInt($codigo)
                            );

        if($row1['Id_estusucli'] == 0){
            $this->_view->assign('_error', 'Error al activar la cuenta, por favor intente mas tarde');
            $this->_view->renderizar('activar', 'registro'); 
            exit;
        }
        
//        $this->_view->assign('_mensaje', 'Su cuenta ha sido activada');
//         $this->_view->renderizar('activar', 'registro'); 
                Session::setMensaje("Su cuenta ha sido activada con éxito!");
                 $this->_view->renderizar('activar', 'registro'); 
                exit;
    }
}

?>
