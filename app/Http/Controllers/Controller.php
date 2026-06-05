<?php

namespace App\Http\Controllers;  // ✅ Benar

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController  // ✅ Nama class = Controller
{
    use AuthorizesRequests, ValidatesRequests;
}