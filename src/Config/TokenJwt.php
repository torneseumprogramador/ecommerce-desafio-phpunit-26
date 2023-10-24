<?php
namespace Danilo\EcommerceDesafio\Config;

class TokenJwt{
    public static function get(){
        return getenv('JWT_TOKEN') ? getenv('JWT_TOKEN') : '11923ndssdssAADso2n232dsdAWsdwfKJjJ';
    }
}
