<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupActivity\StoreGrupActivityRequest;
use App\Http\Requests\GrupActivity\UpdateGrupActivityRequest;
use App\Models\GrupActivity;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GrupActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupActivities = GrupActivity::orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('GrupActivity/Index', [
            'grupActivities' => $grupActivities,
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
    public function store(StoreGrupActivityRequest $request)
    {
        $validated = $request->validated();
        $validated['code'] = $this->generateCode();
        GrupActivity::create($validated);

        return redirect()->route('grup-activity.index')->with('success', 'Grup Activity berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(GrupActivity $grupActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrupActivity $grupActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrupActivityRequest $request, GrupActivity $grupActivity)
    {
        $validated = $request->validated();
        $grupActivity->update($validated);

        return redirect()->route('grup-activity.index')->with('success', 'Grup Activity berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrupActivity $grupActivity)
    {
        $grupActivity->delete();

        return redirect()->route('grup-activity.index')->with('success', 'Grup Activity berhasil dihapus');
    }

    protected function generateCode()
    {

        $maxNumber = DB::table('grup_activities')
            ->where('code', 'like', 'GA'.'-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED)) as max_number")
            ->value('max_number');

        $nextNumber = $maxNumber ? (int) $maxNumber + 1 : 1;

        return 'GA-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
