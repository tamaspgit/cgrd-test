<?php

namespace CGRD\Core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        $_SESSION = [];        
        session_unset();        
        session_destroy();
    }

    public function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public function getFlash(string $key): ?string
    {
        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        
        return $message;
    }

    public function getAllFlash(): ?array
    {
        $flash = $_SESSION['_flash'] ?? null;
        unset($_SESSION['_flash']);
        
        return $flash;  
    }

    public function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public function generateCsrfToken(string $form = 'default'): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['_csrf'][$form] = $token;
        
        return $token;
    }

    public function getCsrfToken(string $form = 'default'): ?string
    {
        return $_SESSION['_csrf'][$form] ?? null;
    }

    public function validateCsrfToken(string $token, string $form = 'default'): bool
    {
        return isset($_SESSION['_csrf'][$form]) && hash_equals($_SESSION['_csrf'][$form], $token);
    }

    public function removeCsrfToken(string $form = 'default'): void
    {
        unset($_SESSION['_csrf'][$form]);
    }
}
