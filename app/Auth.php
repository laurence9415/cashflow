<?php

namespace App;

class Auth
{
    public function auth()
    {
        return auth()->user();
    }
}
