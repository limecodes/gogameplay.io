<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NonmobileController extends Controller
{
    public function index()
    {
    	return response('non mobile', 200);
    }
}
