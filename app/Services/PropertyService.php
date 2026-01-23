<?php

namespace App\Services;

use App\Models\Property;
use App\Http\Requests\PropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Storage, Log, DB;

class PropertyService
{

  public function __construct() {}

  /**
   * Create or Update Property
   */
  public function saveProperty(array $validated, PropertyRequest $request) {}
}
