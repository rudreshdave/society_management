<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SocietyRequest;
use App\Services\SocietyService;
use App\Models\Society;
use App\Models\State;
use App\Models\User;

class SocietyController extends Controller
{
  protected SocietyService $societyService;

  public function __construct(SocietyService $societyService)
  {
    $this->societyService = $societyService;
  }

  public function index()
  {
    $societies = Society::with(['attachments'])->latest()->get();
    foreach ($societies as $society) {
      $society->logos = [];
    }
    $states = State::selectRaw('id, UPPER(name) as name')->pluck('name', 'id');
    return view('content.admin.societies.list', compact('societies', 'states'));
  }

  public function store(SocietyRequest $request)
  {
    $validated = $request->validated();
    $this->societyService->saveSociety($validated, $request);

    return redirect()
      ->route('societies.index')
      ->with('success', 'Society created successfully');
  }

  public function update(SocietyRequest $request, $id)
  {
    $validated = $request->validated();
    $validated['id'] = $id;
    $this->societyService->saveSociety($validated, $request, $id);

    return redirect()
      ->route('societies.index')
      ->with('success', 'Society updated successfully');
  }

  public function destroy($id)
  {
    Society::findOrFail($id)->delete();

    return back()->with('success', 'Society deleted successfully');
  }

  public function changeStatus(Request $request)
  {
    $request->validate([
      'id'     => 'required|exists:societies,id',
      'status' => 'required|in:1,2,3'
    ]);

    $society = Society::findOrFail($request->id);
    $society->status = $request->status;
    $society->save();

    return response()->json([
      'success' => true,
      'message' => 'Society status updated successfully'
    ]);
  }
}
