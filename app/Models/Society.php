<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Society extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'societies';
    protected $appends = ['status_label'];

    protected $hidden = ['pivot'];

    protected $status_labels = [1 => "Active", 2 => "Inactive", 3 => "Block"];
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
        'longitude',
        'database_name',
        'database_username',
        'database_password'
    ];

    public function getStatusLabelAttribute()
    {
        return $this->status_labels[$this->status];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class)->select('id', 'name');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'module')
            ->orderBy('sort_order');
    }
}
