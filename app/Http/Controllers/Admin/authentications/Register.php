<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use Str;
use App\Models\Society;
use App\Models\State;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash as FacadesHash;

class Register extends Controller
{
  public function index()
  {
    $states = State::selectRaw('id, UPPER(name) as name')
      ->pluck('name', 'id');


    return view('content.authentications.register', compact('states'));
  }

  public function register(Request $request)
  {
    try {

      $data = $request->all();

      DB::beginTransaction();

      $insert_society = [
        'society_name' => $data['society_name'] ?? null,
        'registration_no' => $data['registration_no'] ?? null,
        'address_line_1' => $data['address_line_1'] ?? null,
        'address_line_2' => $data['address_line_2'] ?? null,
        'city_id' => $data['city_id'] ?? null,
        'state_id' => $data['state_id'] ?? null,
        'pincode' => $data['pincode'] ?? null,
        'contact_email' => $data['email'] ?? null,
        'contact_mobile' => $data['mobile'] ?? null,
        'status' => 0
      ];

      $society = new Society();
      $society->fill($insert_society);
      $society->save();
      if (isset($society) && !empty($society)) {
        $insert_user = [
          'name' => $data['name'] ?? null,
          'email' => $data['email'] ?? null,
          'password' => isset($data['password']) && !empty($data['password']) ? Hash::make($data['password']) : null,
          'status' => 0,
        ];

        $user = new User();
        $user->fill($insert_user);
        $user->save();

        if (isset($user) && !empty($user)) {
          $insert_user_role = [
            'user_id' => $user->id ?? null,
            'role_id' => 2,
            'society_id' => $society->id ?? null
          ];

          $user_role = new UserRole();
          $user_role->fill($insert_user_role);
          $user_role->save();
        }
      }

      if (isset($society) && !empty($society) && isset($user) && !empty($user) && isset($user_role) && !empty($user_role)) {
        DB::commit();
        return redirect()->route('login');
      }
    } catch (\Exception $ex) {
      DB::rollBack();
      print_r($ex->getMessage());
      die;
      return redirect()->route('register');
    }
  }
}
