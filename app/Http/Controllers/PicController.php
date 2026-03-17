<?php

namespace App\Http\Controllers;

use App\Http\Requests\PIC\StorePicRequest;
use App\Http\Requests\PIC\UpdatePicRequest;
use App\Models\Pic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Pic/Index', [
            'pics' => Pic::with('user')->orderBy('id', 'ASC')->paginate(10)->withQueryString(),
            'users' => User::get(),
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
    public function store(StorePicRequest $request)
    {
        $validated = $request->validated();
        $validated['code'] = $this->generatedCode();

        Pic::create($validated);

        return redirect()->route('pic.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pic $pic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pic $pic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePicRequest $request, Pic $pic)
    {
        $validated = $request->validated();

        $pic->update($validated);

        return redirect()->route('pic.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pic $pic)
    {
        $pic->delete();

        return redirect()->route('pic.index')->with('success', 'Data berhasil dihapus');
    }

    protected function generatedCode()
    {
        $maxNumber = DB::table('pics')
            ->where('code', 'like', 'PIC'.'-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED)) as max_number")
            ->value('max_number');

        $nextNumber = $maxNumber ? (int) $maxNumber + 1 : 1;

        return 'PIC-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
