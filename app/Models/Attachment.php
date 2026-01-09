<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
  use HasFactory;

  protected $table = 'attachments';

  protected $appends = ['file_url'];
  protected $fillable = [
    'module_type',
    'module_id',
    'file_path',
    'sort_order',
  ];

  /**
   * Polymorphic relation
   */
  public function module()
  {
    return $this->morphTo();
  }

  /**
   * Full public URL of attachment
   */
  public function getFileUrlAttribute(): string
  {
    return asset('storage/' . $this->file_path);
  }
}
