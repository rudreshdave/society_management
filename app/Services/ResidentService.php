<?php

namespace App\Services;

use App\Http\Requests\ResidentRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Helpers\Helper;
use App\Models\UserRole;
use App\Models\Property;
use App\Models\Resident;
use App\Models\User;

use Storage, Log, DB;

class ResidentService
{
  use ApiResponse;

  /**
   * @var \App\Helpers\Helper
   */
  private $helper;

  public function __construct(Helper $helper)
  {
    $this->helper = $helper;
  }

  /**
   * Create or Update Resident
   */
  public function save_resident(array $validated, ResidentRequest $request)
  {
    DB::beginTransaction();
    try {
      dd($validated);
      if (isset($validated) && !empty($validated)) {
        $user = new User();
        $user->fill([
          "name" => $validated['name'] ?? null,
          "email" => $validated['email'] ?? null,
          "mobile" => $validated['mobile'] ?? null,
          "status" => 2
        ]);
        $user->save();
        if (isset($user) && !empty($user)) {
          if (isset($validated['resident_type']) && !empty($validated['resident_type'])) {
            if ($validated['resident_type'] == 1) {
              $role_id = 3;
            } elseif ($validated['resident_type'] == 2) {
              $role_id = 4;
            }
          }
          $user_role = new UserRole();
          $user_role->fill([
            "role_id" => $role_id,
            "user_id" => $user->id
          ]);
          $user_role->save();

          $ptoperty = new Property();
          $ptoperty->fill([
            'wing_no' => $validated['wing_no'] ?? null,
            'floor_no' => $validated['floor_no'] ?? null,
            'flat_no' => $validated['flat_no'] ?? null,
            'bunglow_no' => $validated['bunglow_no'] ?? null,
            'residency_type' => $validated['residency_type'] ?? null
          ]);
          $ptoperty->save();
          if (isset($property) && !empty($property)) {
            $resident = new Resident();
            $resident->fill([
              'resident_type' => $validated['resident_type'] ?? null,
              'user_id' => $validated['user_id'] ?? null,
              'property_id' => $validated['property_id'] ?? null,
              'alternate_mobile' => $validated['alternate_mobile'] ?? null,
              'move_in_date' => $validated['move_in_date'] ?? null,
              'emergency_contact' => $validated['emergency_contact'] ?? null
            ]);
            $resident->save();
          }
        }
      }
      if ($user && $property && $resident) {
        $user_id = $user->id ?? null;
        $this->add_admin_user($validated, $user_id);
        DB::commit();
        return $this->customResponse(1, trans("translate.record_saved", ["model" => $this->model]), $schedular);
      } else {
        DB::rollback();
        return $this->customResponse(1, trans("translate.bad_request"));
      }
    } catch (\Exception $ex) {
      DB::rollback();
      return $this->customResponse(-1, null, $ex->getMessage());
    }
  }

  public function add_superadmin_user(array $validated, int $user_id)
  {
    try {
      $this->helper->configure_database_connection(env('DB_DATABASE'));
      $society_id = Auth::user()->currentAccessToken()->abilities['society_id'];
      if (isset($validated) && !empty($validated)) {
        $user = new User();
        $user->fill([
          "name" => $validated['name'] ?? null,
          "email" => $validated['email'] ?? null,
          "mobile" => $validated['mobile'] ?? null,
          "status" => 2
        ]);
        $user->save();
        if (isset($user) && !empty($user)) {
          if (isset($validated['resident_type']) && !empty($validated['resident_type'])) {
            if ($validated['resident_type'] == 1) {
              $role_id = 3;
            } elseif ($validated['resident_type'] == 2) {
              $role_id = 4;
            }
          }
          $user_role = new UserRole();
          $user_role->fill([
            "role_id" => $role_id ?? null,
            "user_id" => $user->id ?? null,
            "society_id" => $society_id ?? null,
            "society_user_id" => $user_id ?? null
          ]);
          $user_role->save();
        }
      }
      if ($user && $user_role) {
        return true;
      }
    } catch (\Exception $ex) {
      return $this->customResponse(-1, null, $ex->getMessage());
    }
  }
}
