<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Usuario;
use NextLevelHub\Services\UsuarioService;
// use NextLevelHub\Request\UsuarioRequest;

class UsuarioController
{
    private UsuarioService $service;
    private Pages $pages;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new UsuarioService($db);
        $this->pages = new Pages();
    }

    public function listar(): void{
        $usuarios = $this->service->findAll();
        $this->pages->render('usuario/showUsuarios', [
            'usuarios' => $usuarios
        ]);
    }

    public function nuevoUsuario(): void{
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['data'])) {
            $this->pages->render('usuario/formUsuario');
            return;
        }

        $data = $_POST['data'];

        // $data = UsuarioRequest::sanitize($data);
        // $errores = UsuarioRequest::validate($data);

        $errores = [];

        if (!empty($errores)) {
            $usuarioParcial = Usuario::fromArray($data);
            $this->pages->render('usuario/formUsuario', [
                'usuario' => $usuarioParcial,
                'errores' => $errores
            ]);
            return;
        }

        // 🔐 hash de contraseña si viene informada
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $usuario = Usuario::fromArray($data);
        $esNuevo = $usuario->getId() === null || $usuario->getId() === 0;

        $exito = $this->service->save($usuario);

        $resultado = match (true) {
            !$exito  => "No se ha podido guardar el usuario",
            $esNuevo => "Usuario creado con éxito",
            default  => "Usuario actualizado con éxito",
        };

        $this->pages->render('usuario/resultado', [
            'resultado' => $resultado
        ]);
    }
}