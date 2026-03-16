<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Http\Requests\Place\StorePlaceRequest;
use App\Http\Requests\Place\UpdatePlaceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Place/Index', [
            'places' => Place::orderBy('id', 'ASC')->search($request->input('search'))->paginate(10)->withQueryString(),
            'filters' => [
                'search' => $request->input('search'),
            ],
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
    public function store(StorePlaceRequest $request)
    {
        $validated = $request->validated();
        $validated['code'] = $this->generateCode();
        Place::create($validated);
        return redirect()->route('place.index')->with('success', 'Place created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $validated = $request->validated();
        $place->update($validated);
        return redirect()->route('place.index')->with('success', 'Place updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $place->delete();
        return redirect()->route('place.index')->with('success', 'Place deleted successfully');
    }

    protected function generateCode()
    {
        $lastCode = Place::whereNotNull('code')->orderBy('code', 'desc')->value('code');

        $nextNumber = 1;

        if ($lastCode) {
            $lastNumber = (int)str_replace('PL-', '', $lastCode);
            $nextNumber = $lastNumber + 1;
        }

        return 'PL-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
