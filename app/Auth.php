<?php

namespace App;

class Auth
{
    public static function auth()
    {
        return auth()->user();
    }
}
