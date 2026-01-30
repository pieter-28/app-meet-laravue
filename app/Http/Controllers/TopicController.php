<?php

namespace App\Http\Controllers;

use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Requests\Topic\UpdateTopicRequest;
use App\Models\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Topic/Index', [
            'topics' => Topic::orderBy('id', 'ASC')->search($request->input('search'))->paginate(10)->withQueryString(),
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
    public function store(StoreTopicRequest $request)
    {
        Topic::create($request->validated());

        return redirect()->route('topic.index')->with('success', 'Create data successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());
        return redirect()->route('topic.index')->with('success', 'Updated data successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('topic.index')->with('success', 'Deleted data successfully');
    }

    public function code()
    {
        $lastCode = Topic::whereNotNull('topic_code')->orderBy('topic_code', 'desc')->value('topic_code');

        $nextNumber = 1;

        if ($lastCode) {
            $lastNumber = (int)str_replace('TP-', '', $lastCode);
            $nextNumber = $lastNumber + 1;
        }

        $code = 'TP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(
            [
                'message' => 'Code sucessfully',
                'code' => $code,
            ],
            200,
        );
    }
}
