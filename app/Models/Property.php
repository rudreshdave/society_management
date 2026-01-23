<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;
    protected $fillable = ['wing_no', 'floor_no', 'flat_no', 'bunglow_no', 'recidency_type'];
    protected $hidden = ['pivot'];
    protected $appends = ['residency_type_label'];
    protected $residency_type_labels = [1 => "Apartment/Flats", 2 => "Bungalow", 3 => "Row House"];
    public function getResidencyTypeLabelAttribute()
    {
        return $this->residency_type_labels[$this->residency_type];
    }

    public static function list_show_query()
    {
        $data_query = Property::select([
            'id',
            'wing_no',
            'floor_no',
            'flat_no',
            'bunglow_no',
            'residency_type'
        ]);
        return $data_query;
    }
}
