<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Requests\SocietyRequest;

use App\Models\Society;
use App\Models\State;
use App\Models\User;

class SocietyController extends Controller
{

  public function __construct() {}

  public function index()
  {
    $states = State::selectRaw('id, UPPER(name) as name')->pluck('name', 'id');
    $societies = Society::select('id', 'society_name', 'registration_no', 'address_line_1', 'address_line_2', 'state_id', 'city_id', 'pincode', 'contact_email', 'contact_mobile', 'total_wings', 'total_flats', 'status')->with(['state', 'city'])->get();
    if (isset($societies) && !empty($societies)) {
      foreach ($societies as $society) {
        $society->logos = [];
      }
    }
    return view('content.admin.societies.list', compact('societies', 'states'));
  }

  public function store(Request $request) {}

  public function update(Request $request, Society $society) {}
}
