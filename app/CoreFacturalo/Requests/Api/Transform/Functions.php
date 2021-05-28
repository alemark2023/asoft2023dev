<?php

namespace App\CoreFacturalo\Requests\Api\Transform;

class Functions
{

    /**
     * @param array  $inputs
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     * @deprecated  use InArray instead
     */
    public static function valueKeyInArray($inputs, $key, $default = null) {
        return array_key_exists($key, $inputs) ? $inputs[$key] : $default;
    }

    /**
     * Devuelve el contenido del array si existe, sino devuelve el default.
     *
     * @param array  $inputs
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public static function InArray($inputs = [], $key = '', $default = null) {
        return isset($inputs[$key]) ? $inputs[$key] : $default;
    }
}
