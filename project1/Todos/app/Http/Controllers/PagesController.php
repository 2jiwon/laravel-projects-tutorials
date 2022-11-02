<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    // new method
    public function new() {
      return view('new');
    }
}
