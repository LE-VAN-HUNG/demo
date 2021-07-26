<?php


namespace App\Services;


use Firebase\JWT\JWT;

class GeneralTokenService
{
    public function genToken(&$data, $exp, $key)
    {
        $data['exp'] = time() + $exp;
        $encoded     = JWT::encode($data, $key);
        return $encoded;
    }
}
