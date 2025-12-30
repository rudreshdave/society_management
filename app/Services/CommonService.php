<?php

namespace App\Services;

use App\Models\City;

class CommonService
{

  public function getCities(array $data = [])
  {
    $cities = new City();
    if (isset($data['state_id']) && !empty($data['state_id'])) {
      $cities = $cities->where('state_id', $data['state_id']);
    }
    return $cities = $cities->pluck('name', 'id');
  }
}
