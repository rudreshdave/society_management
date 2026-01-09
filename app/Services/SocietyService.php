<?php

namespace App\Services;

use App\Models\City;
use App\Models\Society;
use App\Models\Attachment;
use App\Http\Requests\SocietyRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;

class SocietyService
{
  public function __construct() {}

  /**
   * Create or update society
   */
  public function saveSociety(array $validated, SocietyRequest $request, int $id = null)
  {


    DB::beginTransaction();

    try {
      /** -----------------------------
       * Create or Update Society
       * ----------------------------- */
      $society = Society::updateOrCreate(
        ['id' => $id],
        $validated
      );

      /** -----------------------------
       * Remove deleted logos
       * ----------------------------- */
      if ($request->filled('removed_logos')) {
        $removedIds = explode(',', $request->removed_logos);

        $attachments = Attachment::whereIn('id', $removedIds)->get();

        foreach ($attachments as $file) {
          Storage::disk('public')->delete($file->file_path);
          $file->delete();
        }
      }

      /** -----------------------------
       * Upload new logos
       * ----------------------------- */
      if ($request->hasFile('logos')) {
        foreach ($request->file('logos') as $key => $logo) {
          $path = $logo->store('societies/logos', 'public');

          Attachment::create([
            'module_type' => Society::class,
            'module_id'   => $society->id,
            'file_path'       => $path,
            'sort_order'      => $key + 1
          ]);
        }
      }

      $attachments = json_decode($request->attachments_sort, true);

      foreach ($attachments as $item) {
        Attachment::where('id', $item['id'])
          ->update(['sort_order' => $item['sort_order']]);
      }

      DB::commit();

      return $society;
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Upload single or multiple attachments
   */
  public function uploadAttachments(
    string $moduleType,
    int $moduleId,
    $files = null,
    string $directory = 'attachments'
  ): void {
    Log::info('uploadAttachments started', [
      'module_type' => $moduleType,
      'module_id'   => $moduleId,
      'directory'   => $directory
    ]);

    if (empty($files)) {
      Log::warning('No files provided for upload');
      return;
    }

    // Normalize to array
    $files = is_array($files) ? $files : [$files];

    // Get last sort order
    $lastOrder = Attachment::where('module_type', $moduleType)
      ->where('module_id', $moduleId)
      ->max('sort_order');

    $sortOrder = is_null($lastOrder) ? 0 : $lastOrder + 1;

    Log::info('Initial sort order', ['sort_order' => $sortOrder]);

    foreach ($files as $index => $file) {

      if (!$file instanceof UploadedFile) {
        Log::warning('Invalid file skipped', [
          'index' => $index
        ]);
        continue;
      }

      Log::info('Uploading file', [
        'original_name' => $file->getClientOriginalName(),
        'mime_type'     => $file->getMimeType(),
        'size'          => $file->getSize()
      ]);

      // Store file
      $path = $file->store($directory, 'public');

      Log::info('File stored successfully', [
        'path' => $path
      ]);

      // Save attachment
      Attachment::create([
        'module_type'   => $moduleType,
        'module_id'     => $moduleId,
        'file_path'     => $path,
        'original_name' => $file->getClientOriginalName(),
        'mime_type'     => $file->getMimeType(),
        'file_size'     => $file->getSize(),
        'sort_order'    => $sortOrder++,
      ]);

      Log::info('Attachment record created', [
        'module_id' => $moduleId,
        'path'      => $path
      ]);
    }

    Log::info('uploadAttachments completed', [
      'module_id' => $moduleId
    ]);
  }
}
