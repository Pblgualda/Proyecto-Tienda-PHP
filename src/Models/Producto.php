<?php

namespace NextLevelHub\Models;

class Producto
{
    public function __construct(
        private int|null    $id = null,
        private int         $categoria_id = 0,
        private string      $nombre = '',
        private string      $descripcion = '',
        private float       $precio = 0.0,
        private float|null  $precio_oferta = null,
        private int         $stock = 0,
        private int         $activo = 1,
        private string|null $imagen = null,
        private string      $created_at = '',
        private string      $updated_at = ''
    )
    {}

    public static function fromArray(array $data): self
    {
        $id = (isset($data['id']) && $data['id'] !== '') ? (int)$data['id'] : null;

        return new self(
            id: $id,
            categoria_id: isset($data['categoria_id']) ? (int)$data['categoria_id'] : 0,
            nombre: $data['nombre'] ?? '',
            descripcion: $data['descripcion'] ?? '',
            precio: isset($data['precio']) ? (float)$data['precio'] : 0.0,
            precio_oferta: (isset($data['precio_oferta']) && $data['precio_oferta'] !== '')
                ? (float)$data['precio_oferta']
                : null,
            stock: isset($data['stock']) ? (int)$data['stock'] : 0,
            activo: isset($data['activo']) ? (int)$data['activo'] : 1,
            imagen: $data['imagen'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}