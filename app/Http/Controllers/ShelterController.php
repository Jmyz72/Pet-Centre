<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function index()
    {
        return view('services.shelter.index');
    }
}
