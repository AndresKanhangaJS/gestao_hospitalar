<?php
use Hashids\Hashids;

if (!function_exists('codificar')) {
    function codificar($dado) {
        // Usando a APP_KEY como salt para ser único por projeto
        $has = new Hashids(config('app.key'), 10, 'abcdefghijklMNOPQrstuvwxyz1234567890');
        return $has->encode($dado);
    }
}

if (!function_exists('decodificar')) {
    function decodificar($dado) {
        $has = new Hashids(config('app.key'), 10, 'abcdefghijklMNOPQrstuvwxyz1234567890');
        $decoded = $has->decode($dado);
        return $decoded[0] ?? null;
    }
}
