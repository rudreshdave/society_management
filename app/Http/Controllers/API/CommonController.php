<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CommonService;

class CommonController extends Controller
{

  public $common_service;
  public function __construct(CommonService $common_service)
  {
    $this->common_service = $common_service;
  }

  public function cities(Request $request)
  {
    $data = $request->all();
    $cities = $this->common_service->getCities($data);
    if (isset($cities)) {
      $response['status'] = true;
      $response['message'] = "Cities found!";
      $response['data'] = $cities;

      return response()->json($response, 200);
    }
  }
}
