<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    
    public static function index(Router $router){        
        iniciarSesion();
        if(isset($_SESSION['login']) && $_SESSION['login']){
            header('Location:/dashboard');
        }

        $alertas = [];
        $usuario = new Usuario();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario -> sincronizar($_POST);
            
            $alertas = $usuario -> validarLogin();
            
            if(empty($alertas)){
                $usuarioEncontrado = $usuario -> where('email',$usuario -> email);//Buscar  usuario que corresponda al email
                if($usuarioEncontrado && $usuarioEncontrado -> confirmado){
                    if($usuarioEncontrado -> validarPasswordlogin($usuario -> password)){//Conprueba la contraseña ingresada
                        iniciarSesion();
                        $_SESSION['login'] = true;
                        $_SESSION['nombre'] = $usuarioEncontrado -> nombre;
                        $_SESSION['id'] = $usuarioEncontrado -> id;
                        $_SESSION['admin'] = true;
                        header('Location:/dashboard');
                    }else{
                        Usuario::setAlerta('error','Email o contraseña incorrecta');
                    }                   

                }else{//Si no se encuentra un usuario con ese email
                    Usuario::setAlerta('error','Email incorrecto o usuario no confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router -> render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function logout(){
        iniciarSesion();
        $_SESSION = [];
        session_destroy();
        header('Location:/');
    }

    public static function crear(Router $router){
        $usuario = new Usuario();//Crea objeto vacio
        $alertas = [];
        
        //Se ejecuta cuando se mando algo por post
        if($_SERVER['REQUEST_METHOD']==='POST'){
            //Sincroniza datos de post con el objeto
            $usuario -> sincronizar($_POST);

            //Valida los datos y devuelve un arreglo con la lista de errores si los hay
            $alertas = $usuario -> validarNuevaCuenta();           

            //Si no hay errores 
            if(empty($alertas)){

                //Busca usuario por email
                $existeUsuario = Usuario::where('email',$usuario -> email);

                //Si existe el usuario
                if($existeUsuario){
                    //Añade una alaerta
                    Usuario::setAlerta('error','El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();//Obtiene las alertas
                }else{//Si no existe el usaurio

                    //Hashear password
                    $usuario -> hashPassword();

                    //Eliminar password2
                    unset($usuario -> password2);

                    //Crear token 
                    $usuario -> crearToken();
                    
                    //Guardar nuevo usuario
                    $resultado = $usuario -> guardar();
                    
                    //Enviar email
                    $email = new Email($usuario -> email, $usuario -> nombre, $usuario -> token);
                    $email -> enviarConfirmacion();

                    if($resultado){
                        header('Location:/mensaje');
                    }
                }
            }
            
        }

        $router -> render('auth/crear',[
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];
        $usuario = new Usuario;
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario -> sincronizar($_POST);
            $alertas = $usuario -> validarEmail();
            if(empty($alertas)){
                //Buscar el usuario por medio del email
                $usuario = Usuario::where('email', $usuario -> email);
                if($usuario && $usuario -> confirmado){
                    //Generar nuevo token
                    $usuario -> crearToken();

                    //Actualizar el usuario
                    unset($usuario -> password2);
                    $usuario -> guardar();

                    //Enviar el email
                    $email = new Email($usuario -> email, $usuario -> nombre, $usuario -> token);
                    $email -> enviarInstrucciones();

                    //Imprimir alerta
                    Usuario::setAlerta('exito','Hemos enviado las instrucciones para reestablecer tu contraseña a tu email');
                    $usuario = new Usuario;   
                }else{
                    $usuario = new Usuario;                    
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router -> render('auth/olvide',[
            'titulo' => 'Reestablecer Contraseña',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $alertas = [];
        $mostrar = false;
        if(!$token) header('Location:/');

        //Encontrar al usuario con el token
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){//No se encontro el usuario con ese token
            Usuario::setAlerta('error','Token no valido');
        }else{
            $mostrar = true;
            if($_SERVER['REQUEST_METHOD']==='POST'){
                $usuario -> sincronizar($_POST);
                $alertas = $usuario -> validarPassword();
                if(empty($alertas)){
                    //Hashear el password nuevo
                    $usuario -> hashPassword();

                    //Eliminar token
                    $usuario -> token = null;
                    unset($usuario -> password2);

                    //guardar usuario en la DB
                    $resultado = $usuario -> guardar();

                    if($resultado){
                        header('Location:/');
                    }
                    
                }
            }
        }
        

        $alertas = Usuario::getAlertas();

        $router -> render('auth/reestablecer',[
            'titulo' => 'Reestablecer Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){       
         $router -> render('auth/mensaje',[
            'titulo' => 'Confirmar Correo'
        ]);
    }

    public static function confirmar(Router $router){
        //Obtiene el token por metodo get
        $token = s($_GET['token']);

        //Redirecciona si no hay token
        if(!$token) header('Location:/');

        //Encontrar al usuario con el token
        $usuario = Usuario::where('token',$token);
        
        if(empty($usuario)){//No se encontro token
            Usuario::setAlerta('error','Token no valido');
        }else{//Confirmar cuenta
            //Quita el token
            $usuario -> token = null;
            //Confirma la cuenta
            $usuario -> confirmado = 1;
            //Quita la variable password2 del objeto 
            unset($usuario -> password2);
            
            //Guarda los cambios del usuario
            $usuario -> guardar();

            Usuario::setAlerta('exito','Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router -> render('auth/confirmar',[
            'titulo' => 'Confirmar cuenta',
            'alertas' => $alertas
        ]);
    }
}