<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Society;
use App\Models\User;

class SocietyController extends Controller
{

  public function __construct() {}

  public function index()
  {
    $users = User::all();
    return view('content.admin.societies.list', compact('users'));
  }
}
