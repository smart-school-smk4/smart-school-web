<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pengumumanController extends Controller
{
    public function index(){
        return view('admin.bel.pengumuman');
    }
}
