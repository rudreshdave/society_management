<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use SoftDeletes;
    protected $fillable = ['resident_type', 'user_id', 'property_id', 'alternate_mobile', 'move_in_date', 'emergency_contact'];
    protected $hidden = ['pivot'];
    protected $appends = ['recident_type_label'];
    protected $recidency_type_labels = [1 => "Owner", 2 => "Tenant"];
    public function getResidentTypeLabelAttribute()
    {
        return $this->recidency_type_labels[$this->recidency_type];
    }

    public static function list_show_query()
    {
        $data_query = Resident::select([
            'id',
            'resident_type',
            'user_id',
            'property_id',
            'alternate_mobile',
            'move_in_date',
            'emergency_contact'
        ]);
        return $data_query;
    }
}
