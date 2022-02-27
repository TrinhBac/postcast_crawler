<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

Trait ModelHelperTrait {

    public function scopeFilter($query, $request) {
        foreach ($request->all() as $field => $value) {
            $method = 'scope' . Str::studly($field);

            if (is_null($value) || !isset($this->filterEqual) || !is_array($this->filterEqual)) {
                continue;
            }

            //check if method was defined, then call it
            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            } elseif (in_array($field, $this->filterEqual)) {
                $query->where($this->table . '.' . $field, $value);
            } elseif (key_exists($field, $this->filterEqual)) {
                $query->where($this->table . '.' . $this->filterEqual[$field], $value);
            }
        }

        return $query;
    }

}
