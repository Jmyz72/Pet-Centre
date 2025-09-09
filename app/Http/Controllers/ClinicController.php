<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        return view('services.clinic.index');
    }
}
