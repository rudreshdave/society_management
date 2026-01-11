<?php

namespace App\Services;

use App\Models\Role;
use DB;

class RoleService
{
  public function __construct() {}

  public function saveRole(array $validated, int $id = null)
  {
    DB::beginTransaction();
    try {
      $role = Role::updateOrCreate(
        ['id' => $id],
        $validated
      );

      DB::commit();

      return $role;
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
