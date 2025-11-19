<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('inicio_gestion_citas');
    }
}
