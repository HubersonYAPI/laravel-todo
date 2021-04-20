<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades;

class AproposController extends Controller
{
    public function index() {
        return View('apropos.index');
    }
}
