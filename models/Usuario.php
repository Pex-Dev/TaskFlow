<?php 

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {   
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //Validar formulario de crear nueva cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del usuario es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña del usuario es obligatoria';
        }else 
        if(!self::validar_contraseña($this->password)){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres, y que contener letras mayúsculas, minúsculas y símbolos';
        }
        if(strlen($this->password)>10){
            self::$alertas['error'][] = 'La contraseña del usuario debe tener como maximo 10 caracteres';
        }
        if($this->password!=$this->password2){
            self::$alertas['error'][] = 'Las contraseñas son diferentes';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña del usuario es obligatoria';
        }else 
        if(!self::validar_contraseña($this->password)){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres, y que contener letras mayúsculas, minúsculas y símbolos';
        }
        if(strlen($this->password)>10){
            self::$alertas['error'][] = 'La contraseña del usuario debe tener como maximo 10 caracteres';
        }
        if($this->password!=$this->password2){
            self::$alertas['error'][] = 'Las contraseñas son diferentes';
        }
        return self::$alertas;
    }

    //Valida email para reestablecer contraseña
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }else
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El email ingresado no es valido';
        }
        return self::$alertas;
    }

    //Validar campos para inicio de sesion
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña del usuario es obligatoria';
        }
        return self::$alertas;
    }

    //Hashea el password
    public function hashPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }

    //Generar Token
    public function crearToken(){
        $this->token = uniqid();
    }

    //Validar password para iniciar sesión
    public function validarPasswordlogin($password){
        return password_verify($password,$this->password);
    }
    
    public function validarPerfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        return self::$alertas;
    }

    public function validarNuevoPassword(){
        if(!$this->password_actual){
            self::$alertas['error'][] = 'La contraseña actual es obligatoria';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'La contraseña nueva es obligatoria';
        }else
        if(!self::validar_contraseña($this->password_nuevo)){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres, y que contener letras mayúsculas, minúsculas y símbolos';
        }
        return self::$alertas;
    }

    protected function validar_contraseña($contraseña) {
        $longitud = strlen($contraseña);
        $tiene_mayuscula = preg_match('/[A-Z]/', $contraseña);
        $tiene_minuscula = preg_match('/[a-z]/', $contraseña);
        $tiene_simbolo = preg_match('/[\W]/', $contraseña); // \W busca cualquier carácter que no sea una letra o número.
    
        if ($longitud >= 6 && $tiene_mayuscula && $tiene_minuscula && $tiene_simbolo) {
            return true;
        } else {
            return false;
        }
    }

    //Comprobar password
    public function comprobarPassword(){
        return password_verify($this->password_actual,$this->password);
    }
}