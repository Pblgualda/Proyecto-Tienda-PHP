<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Producto;
use RuntimeException;
use PDOException;

class ProductoRepository implements ProductoRepositoryInterface
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "SELECT * FROM productos";
            $this->conexion->ejecutar($sql);

            $productos = [];
            foreach($this->conexion->extraer_todos() as $fila){
                $productos[] = Producto::fromArray($fila);
            }
            return $productos;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener los productos: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Producto $producto): bool
    {
        try{
            if ($producto->getId() === null || $producto->getId() === 0) {
                return $this->insert($producto);
            }
            return $this->update($producto);

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al guardar el producto: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function insert(Producto $producto): bool
    {
        $sql = "INSERT INTO productos 
            (categoria_id, nombre, descripcion, precio, precio_oferta, stock, activo, imagen, created_at, updated_at)
            VALUES 
            (:categoria_id, :nombre, :descripcion, :precio, :precio_oferta, :stock, :activo, :imagen, :created_at, :updated_at)";

        return $this->conexion->ejecutar($sql, [
            ':categoria_id'   => $producto->getCategoriaId(),
            ':nombre'         => $producto->getNombre(),
            ':descripcion'    => $producto->getDescripcion(),
            ':precio'         => $producto->getPrecio(),
            ':precio_oferta'  => $producto->getPrecioOferta(),
            ':stock'          => $producto->getStock(),
            ':activo'         => $producto->getActivo(),
            ':imagen'         => $producto->getImagen(),
            ':created_at'     => $producto->getCreatedAt(),
            ':updated_at'     => $producto->getUpdatedAt(),
        ]);
    }

    public function update(Producto $producto): bool
    {
        $sql = "UPDATE productos SET
            categoria_id = :categoria_id,
            nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            precio_oferta = :precio_oferta,
            stock = :stock,
            activo = :activo,
            imagen = :imagen,
            updated_at = :updated_at
            WHERE id = :id";

        return $this->conexion->ejecutar($sql, [
            ':id'             => $producto->getId(),
            ':categoria_id'   => $producto->getCategoriaId(),
            ':nombre'         => $producto->getNombre(),
            ':descripcion'    => $producto->getDescripcion(),
            ':precio'         => $producto->getPrecio(),
            ':precio_oferta'  => $producto->getPrecioOferta(),
            ':stock'          => $producto->getStock(),
            ':activo'         => $producto->getActivo(),
            ':imagen'         => $producto->getImagen(),
            ':updated_at'     => $producto->getUpdatedAt(),
        ]);
    }
}