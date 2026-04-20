<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Producto;
use NextLevelHub\Services\ProductoService;
// use NextLevelHub\Request\ProductoRequest; // opcional si lo tienes

class ProductoController
{
    private ProductoService $service;
    private Pages $pages;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new ProductoService($db);
        $this->pages = new Pages();
    }

    public function listar(): void{
        $productos = $this->service->findAll();
        $this->pages->render('producto/showProductos', [
            'productos' => $productos
        ]);
    }

    public function nuevoProducto(): void{
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['data'])) {
            $this->pages->render('producto/formProducto');
            return;
        }

        $data = $_POST['data'];

        // Si tienes request validator, descomenta:
        // $data = ProductoRequest::sanitize($_POST["data"]);
        // $errores = ProductoRequest::validate($data);

        $errores = []; // temporal si no tienes validación

        if (!empty($errores)) {
            $productoParcial = Producto::fromArray($data);
            $this->pages->render('producto/formProducto', [
                'producto' => $productoParcial,
                'errores'  => $errores
            ]);
            return;
        }

        $producto = Producto::fromArray($data);
        $esNuevo = $producto->getId() === null || $producto->getId() === 0;

        $exito = $this->service->save($producto);

        $resultado = match (true) {
            !$exito  => "No se ha podido guardar el producto",
            $esNuevo => "Producto agregado con éxito",
            default  => "Producto modificado con éxito",
        };

        $this->pages->render('producto/resultado', [
            'resultado' => $resultado
        ]);
    }
}