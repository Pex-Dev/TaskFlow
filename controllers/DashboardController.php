<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{

    public static function index(Router $router){
        iniciarSesion();        
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId',$id);    
                
        $router  -> render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        iniciarSesion();        
        isAuth();

        $alertas = [];
        $proyecto = new Proyecto();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $proyecto -> sincronizar($_POST);
            $alertas = $proyecto -> validarProyecto();
            if(empty($alertas)){
                //Generar URL  única
                $hash = md5(uniqid());
                $proyecto -> url = $hash;

                //Almacenar el creador del proyecto
                $creadorId = $_SESSION['id'];
                $proyecto -> propietarioId = $creadorId;

                

                //Guardar
                if($proyecto -> guardar()){
                    header('Location:/proyecto?id='.$proyecto -> url);
                }else{
                    Proyecto::setAlerta('error','Ha ocurrido un error. Intenta mas tarde :(');
                    $alertas = Proyecto::getAlertas();
                }
            }
        }

        $router  -> render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas,
            'proyecto' => $proyecto
        ]);
    }

    public static function eliminar_proyecto(){
        iniciarSesion();        
        isAuth();

        if($_SERVER['REQUEST_METHOD']==='GET'){            
            //Obtener url del proyecto
            $proyectoId = $_GET['id'];

            //Redireccionar si no se encuetra un id
            if(!$proyectoId) header('Location:/dashboard');

            //Buscar proyecto en base a la url
            $proyecto = Proyecto::where('url',$proyectoId);

            $proyecto -> eliminar();
            header('Location:/dashboard');      
        }
    }

    public static function proyecto(Router $router){
        iniciarSesion();        
        isAuth();

        $alertas = [];

        $token =  $_GET['id'];//Obtener id

        if(!$token){header('Location:/dashboard');}//Redireccionar si no hay token de id

        $proyecto = Proyecto::where('url',$token);//Obtener proyecto

        if(!$proyecto){header('Location:/dashboard');}//Redireccionar si no se encuentra el proyecto

        if($proyecto -> propietarioId !== $_SESSION['id']){ header('Location:/dashboard');}//Redireccionar si el usuario no es el propietario del proyecto
        
        $router  -> render('dashboard/proyecto',[
            'titulo' => $proyecto -> proyecto,
            'alertas' => $alertas,
            'proyecto' => $proyecto
        ]);
    }

    public static function perfil(Router $router){
        iniciarSesion();        
        isAuth();

        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario -> sincronizar($_POST);
            $alertas = $usuario -> validarPerfil();
            
            if(empty($alertas)){
                //Verificar que el correo no corresponda a otro usuario
                $existeUsuario = Usuario::where('email',$usuario -> email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){ //Tambien se verifica que el id sea diferente ya que se puede cambiar por el mismo email
                    //Mensaje de error
                    Usuario::setAlerta('error','El correo ya esta registrado');
                    $alertas = Usuario::getAlertas();

                }else{
                    //Guardar usuario
                    $usuario -> guardar();
                    
                    $_SESSION['nombre'] = $usuario -> nombre;

                    Usuario::setAlerta('exito','Cambios guardados correctamente');
                    $alertas = Usuario::getAlertas();
                }
                
            }
        }

        $router  -> render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router){
        iniciarSesion();
        isAuth();

        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario -> sincronizar($_POST);
           
            $alertas = $usuario -> validarNuevoPassword();
            
            if(empty($alertas)){
                $resultado = $usuario -> comprobarPassword();
                if($resultado){
                    //asignar nuevo password
                    $usuario->password = $usuario->password_nuevo;

                    //Hashear nuevo password
                    $usuario -> hashPassword();

                    //Eliminar propiedades no necesarias
                    unset($usuario -> password_actual);
                    unset($usuario -> password_nuevo);
                    unset($usuario -> password2);

                    if($usuario -> guardar()){
                        Usuario::setAlerta('exito','Contraseña actualizada correctamente');
                    }else{
                        Usuario::setAlerta('error','Error al actualizar la contraseña :(');
                    }
                    $alertas = Usuario::getAlertas();
                }else{
                    Usuario::setAlerta('error','La contraseña actual es incorrecta');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router -> render('dashboard/cambiar-password',[
            'titulo' => 'Cambiar contraseña',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }
}