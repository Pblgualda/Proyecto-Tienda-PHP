<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Categoria;
use NextLevelHub\Services\CategoriaService;
// use NextLevelHub\Request\CategoriaRequest; // opcional si lo tienes

class CategoriaController
{
    private CategoriaService $service;
    private Pages $pages;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new CategoriaService($db);
        $this->pages = new Pages();
    }

    public function listar(): void{
        $categorias = $this->service->findAll();
        $this->pages->render('categoria/showCategorias', [
            'categorias' => $categorias
        ]);
    }

    public function nuevaCategoria(): void{
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['data'])) {
            $this->pages->render('categoria/formCategoria');
            return;
        }

        $data = $_POST['data'];

        // Si tienes request validator, descomenta:
        // $data = CategoriaRequest::sanitize($_POST["data"]);
        // $errores = CategoriaRequest::validate($data);

        $errores = []; // temporal si no tienes validación

        if (!empty($errores)) {
            $categoriaParcial = Categoria::fromArray($data);
            $this->pages->render('categoria/formCategoria', [
                'categoria' => $categoriaParcial,
                'errores'   => $errores
            ]);
            return;
        }

        $categoria = Categoria::fromArray($data);
        $esNueva = $categoria->getId() === null || $categoria->getId() === 0;

        $exito = $this->service->save($categoria);

        $resultado = match (true) {
            !$exito  => "No se ha podido crear la categoría",
            $esNueva => "Categoría agregada con éxito",
            default  => "Categoría modificada con éxito",
        };

        $this->pages->render('categoria/resultado', [
            'resultado' => $resultado
        ]);
    }
}