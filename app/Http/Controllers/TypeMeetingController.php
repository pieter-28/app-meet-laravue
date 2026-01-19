<?php

namespace App\Http\Controllers;

use App\Models\TypeMeeting;
use App\Http\Requests\StoreTypeMeetingRequest;
use App\Http\Requests\UpdateTypeMeetingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TypeMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('TypeMeeting/Index', [
        'typeMeetings' => TypeMeeting::orderBy('id')
            ->search($request->input('search'))
            ->paginate(5)
            ->withQueryString(),

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
        return Inertia::render('TypeMeeting/Create', [
            'typeMeetings' => TypeMeeting::orderBy('id', 'ASC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeMeetingRequest $request)
    {
        $validate = $request->validated();
        $validate['type_meeting_code'] = mt_rand('00000', '99999') . '-' . date('h:i:s') . '-' . Auth::id();
        TypeMeeting::create($validate);
        return redirect()->route('type-meetings.index')->with('success', 'Type Meeting berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeMeeting $typeMeeting)
    {
        return Inertia::render('TypeMeeting/Detail', [
            'typeMeeting' => $typeMeeting,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeMeeting $typeMeeting)
    {
        return Inertia::render('TypeMeeting/Edit', [
            'typeMeeting' => $typeMeeting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeMeetingRequest $request, TypeMeeting $typeMeeting)
    {
        $typeMeeting->update($request->validated());
        return redirect()->route('type-meetings.index')->with('success', 'Type Meeting berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeMeeting $typeMeeting)
    {
        $typeMeeting->delete();
        return redirect()->route('type-meetings.index')->with('success', 'Type Meeting berhasil dihapus');
    }
}
