<?php

namespace NextLevelHub\Models;

class Usuario
{
    public function __construct(
        private int|null $id = null,
        private string $nombre = '',
        private string $apellidos = '',
        private string $email = '',
        private string $password = '',
        private string $rol = 'usuario',
        private bool $confirmado = false,
        private string|null $token = null,
        private string|null $token_exp = null,
        private string $created_at = '',
        private string $updated_at = ''
    )
    {
    }

    public static function fromArray(array $data): self
    {
        $id = (isset($data['id']) && $data['id'] !== '') ? (int)$data['id'] : null;

        return new self(
            id: $id,
            nombre: $data['nombre'] ?? '',
            apellidos: $data['apellidos'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            rol: $data['rol'] ?? 'usuario',
            confirmado: isset($data['confirmado']) ? (bool)$data['confirmado'] : false,
            token: $data['token'] ?? null,
            token_exp: $data['token_exp'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
        );
    }
}