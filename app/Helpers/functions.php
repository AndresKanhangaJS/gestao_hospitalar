<?php
use Hashids\Hashids;

if (!function_exists('codificar')) {
    function codificar($dado): string {
        return app(Hashids::class)->encode($dado);
    }
}

if (!function_exists('decodificar')) {
    function decodificar(string $dado): ?int {
        return app(Hashids::class)->decode($dado)[0] ?? null;
    }
}

