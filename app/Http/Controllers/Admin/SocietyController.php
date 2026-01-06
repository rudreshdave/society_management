<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SocietyRequest;
use App\Services\SocietyService;
use App\Models\Society;
use App\Models\State;
use App\Models\User;

class SocietyController extends Controller
{

  public $society_service;

  public function __construct(SocietyService $societyService)
  {
    $this->society_service = $societyService;
  }

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

  public function store(SocietyRequest $request)
  {
    try {
      $data = $request->all();
      if (isset($data) && !empty($data)) {
        $save_society = $this->society_service->saveSociety($data);
        if ($save_society) {
          return $this->index();
        }
      }
    } catch (\Exception $ex) {
      dd($ex->getMessage());
    }
  }

  public function update(SocietyRequest $request, Society $society)
  {
    try {
      $data = $request->all();
      $data['society_id'] = $society->id ?? null;
      if (isset($data) && !empty($data) && isset($society) && !empty($society)) {
        $save_society = $this->society_service->saveSociety($data);
        if ($save_society) {
          return $this->index();
        }
      }
    } catch (\Exception $ex) {
      dd($ex->getMessage());
    }
  }
}
