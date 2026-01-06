<?php

namespace App\Services;

use App\Models\City;
use App\Models\Society;

class SocietyService
{

  public function __construct() {}

  public function saveSociety(array $data)
  {
    try {
      if(isset($data) && !empty($data)){
        if(isset($data['society_id']) && !empty($data['society_id'])){
          $society = Society::find($data['society_id']);
        }else{
          $society = new Society();
        }
        $society->fill($data);
        $society->save();
        if(isset($society) && !empty($society)){
          $socity_images = $this->attach
        }
      }
    } catch (\Exception $ex) {
      dd($ex->getMessage());
    }
  }

  /**
     * Upload single or multiple attachments
     *
     * @param string $moduleType   Example: Society::class
     * @param int    $moduleId
     * @param array|UploadedFile|null $files
     * @param string $directory    Example: societies/logos
     */
  public function uploadAttachments(
        string $moduleType,
        int $moduleId,
        $files = null,
        string $directory = 'attachments'
    ): void {
      if (empty($files)) {
            return;
        }

      // Normalize to array
      $files = is_array($files) ? $files : [$files];

      // Get next sort order
      $lastOrder = Attachment::where('module_type', $moduleType)
          ->where('module_id', $moduleId)
          ->max('sort_order');

      $sortOrder = is_null($lastOrder) ? 0 : $lastOrder + 1;

      foreach ($files as $file) {
          if (!$file instanceof UploadedFile) {
              continue;
          }

          // Store file
          $path = $file->store($directory, 'public');

          // Save attachment record
          Attachment::create([
              'module_type'   => $moduleType,
              'module_id'     => $moduleId,
              'file_path'     => $path,
              'original_name' => $file->getClientOriginalName(),
              'mime_type'     => $file->getMimeType(),
              'file_size'     => $file->getSize(),
              'sort_order'    => $sortOrder++,
          ]);
      }
    }
}
