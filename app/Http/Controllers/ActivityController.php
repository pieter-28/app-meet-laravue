<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activity\StoreActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Activity/Index', [
            'activities' => Activity::latest()->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $validated = $request->validated();
        $validated['code'] = $this->generatedCode();
        Activity::create($validated);

        return redirect()->route('activity.index')->with('success', 'Activity created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        return redirect()->route('activity.index')->with('success', 'Activity updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activity.index')->with('success', 'Activity deleted successfully');
    }

    protected function generatedCode()
    {
        $maxNumber = DB::table('activities')
            ->where('code', 'like', 'ACT'.'-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED)) as max_number")
            ->value('max_number');

        $nextNumber = $maxNumber ? (int) $maxNumber + 1 : 1;

        return 'ACT-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
