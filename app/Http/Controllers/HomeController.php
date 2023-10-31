<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  public $nav = "home";

  public function index()
  {

    $nav = 'home';

    return view('content.pages.home', compact('nav'));
  }
}
