<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Society extends Model
{
    use HasFactory;

    protected $table = 'societies';
    protected $fillable = [
        'society_name',
        'registration_no',
        'address_line_1',
        'address_line_2',
        'city_id',
        'state_id',
        'pincode',
        'contact_email',
        'contact_mobile',
        'total_wings',
        'total_flats',
        'status',
        'latitude',
        'longitude'
    ];


    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
