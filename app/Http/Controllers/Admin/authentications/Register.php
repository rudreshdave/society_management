<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;

class Register extends Controller
{
  public function index()
  {
    $states = State::selectRaw('id, UPPER(name) as name')
      ->pluck('name', 'id');


    return view('content.authentications.register', compact('states'));
  }

  public function register(Request $request) {}
}
