<?php

namespace App\Http\Controllers;

use App\Http\Requests\Type\StoreTypeMeetingRequest;
use App\Http\Requests\Type\UpdateTypeMeetingRequest;
use App\Models\TypeMeeting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('TypeMeeting/Index', [
            'typeMeetings' => TypeMeeting::orderBy('id', 'ASC')->search($request->input('search'))->paginate(10)->withQueryString(),
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
    public function store(StoreTypeMeetingRequest $request)
    {
        TypeMeeting::create($request->validated());

        return redirect()->route('type-meetings.index')->with('success', 'Create data successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeMeeting $typeMeeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeMeeting $typeMeeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeMeetingRequest $request, TypeMeeting $typeMeeting)
    {
        $typeMeeting->update($request->validated());

        return redirect()->route('type-meetings.index')->with('success', 'Updated data successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeMeeting $typeMeeting)
    {
        $typeMeeting->delete();

        return redirect()->route('type-meetings.index')->with('success', 'Deleted data successfully');
    }

    public function code()
    {
        $lastCode = TypeMeeting::whereNotNull('type_meeting_code')->orderBy('type_meeting_code', 'desc')->value('type_meeting_code');

        $nextNumber = 1;

        if ($lastCode) {
            $lastNumber = (int) str_replace('TM-', '', $lastCode);
            $nextNumber = $lastNumber + 1;
        }

        $code = 'TM-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(
            [
                'message' => 'Code sucessfully',
                'code' => $code,
            ],
            200,
        );
    }
}
