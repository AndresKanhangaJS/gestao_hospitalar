<?php

namespace App\Traits;

use Hashids\Hashids;

trait HasHashId
{
    public function getRouteKey()
    {
        return app(Hashids::class)->encode($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = app(Hashids::class)->decode($value)[0] ?? null;
        return $id ? $this->find($id) : null;
    }
}
