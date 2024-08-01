<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController{
    public static function index(){
        //Obtener url del proyecto
        $proyectoId = $_GET['id'];

        //Redireccionar si no se encuetra el proyecto
        if(!$proyectoId) header('Location:/dashboard');

        //Buscar proyecto en base a la url
        $proyecto = Proyecto::where('url',$proyectoId);
        
        iniciarSesion();
        //Redireccionar si no encuentra proyecto o si el usuario no es el creador del proyecto
        if(!$proyecto || $proyecto -> propietarioId != $_SESSION['id']) header('Location:/404');

        //Buscar tareas que correspondan con el id del proyecto
        $tareas = Tarea::belongsTo('proyectoId',$proyecto -> id);

        echo json_encode(['tareas' => $tareas]);

    }    
    public static function crear(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            iniciarSesion();
            //Busca el proyecto por la url enviada por metodo POST
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);

            //Revisar si existe el proyecto o si el propietario es dueño del proyecto
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ){
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al agregar la tarea',
                    'tipo' => 'error'
                ];
                //Retorna la respuesta como json
                echo json_encode($respuesta);
                return;
            }
            
            //Crea nuevo objeto tarea con los datos recibidos por POST
            $tarea = new Tarea($_POST);

            //Asigna el id del proyecto
            $tarea -> proyectoId = $proyecto -> id;

            //Guarda la tarea
            $resultado = $tarea -> guardar();

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente',
                'proyectoId' => $proyecto -> id
            ];

            //Retorna la respuesta json
            echo json_encode($respuesta);            
        }
    }
    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

            iniciarSesion();
            //Busca el proyecto por la url enviada por metodo POST
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);

            //Revisar si existe el proyecto o si el propietario es dueño del proyecto
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ){
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea (proyecto no existe o usuario no es dueño del proyecto)',
                    'tipo' => 'error'
                ];
                //Retorna la respuesta como json
                echo json_encode($respuesta);
                return;
            }
          
            $tarea = Tarea::find($_POST['id']);

            if(!$tarea){
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea (la tarea no existe)',
                    'tipo' => 'error'
                ];
                //Retorna la respuesta como json
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea -> proyectoId = $proyecto -> id;
            if($tarea -> guardar()){
                $respuesta = [                   
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Actualizado correctamente',
                ];
                echo json_encode($respuesta);
                return;
            }else{
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea',
                    'tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            
            
        }
    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){            
            iniciarSesion();
            //Busca el proyecto por la url enviada por metodo POST
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);

            //Revisar si existe el proyecto o si el propietario es dueño del proyecto
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id'] ){
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea (proyecto no existe o usuario no es dueño del proyecto)',
                    'tipo' => 'error'
                ];
                //Retorna la respuesta como json
                echo json_encode($respuesta);
                return;
            }

            $tarea = Tarea::find($_POST['id']);

            if(!$tarea){
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea (la tarea no existe)',
                    'tipo' => 'error'
                ];
                //Retorna la respuesta como json
                echo json_encode($respuesta);
                return;
            }
            
            if($tarea -> eliminar()){
                $respuesta = [                   
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea eliminada correctamente'
                ];
                echo json_encode($respuesta);
                return;
            }else{
                $respuesta = [                    
                    'mensaje' => 'Hubo un error al actualizar la tarea',
                    'tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }
        }
    }
}