<?php

namespace Modules\LevelAccess\Helpers;


class GetClientIpHelper
{
        
    /**
     * Obtener ip del cliente
     * 
     * @return string
     */
    public function getClientIp()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } 
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        {
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        }

        return $this->isValidIp($ip) ? $ip : null;
    }


    /**
     *
     * @param string $ip
     * @return bool
     */
    public function isValidIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
        {
            return true;
        }

        return false;
    }

 
}