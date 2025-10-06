<?php
// app/Http/Controllers/Admin/AIController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AIConversation;
use App\Models\AIIntent;
use App\Models\AITrainingData;
use App\Models\AIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    public function index()
    {
        $intents = AIIntent::withCount(['trainingData', 'responses'])->get();
        return view('admin.ai.index', compact('intents'));
    }

    public function createIntent()
    {
        return view('admin.ai.intents.create');
    }

    public function storeIntent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ai_intents,name',
            'description' => 'nullable|string'
        ]);

        AIIntent::create($validated);

        return redirect()->route('admin.ai.index')
            ->with('success', 'Intent created successfully');
    }

    public function showIntent($id)
    {
        $intent = AIIntent::with(['trainingData', 'responses'])->findOrFail($id);
        return view('admin.ai.intents.show', compact('intent'));
    }

    public function addTrainingData(Request $request, $intentId)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'metadata' => 'nullable|array'
        ]);

        AITrainingData::create([
            'intent_id' => $intentId,
            'text' => $validated['text'],
            'metadata' => $validated['metadata'] ?? null
        ]);

        return back()->with('success', 'Training data added successfully');
    }

    public function addResponse(Request $request, $intentId)
    {
        $validated = $request->validate([
            'response' => 'required|string',
            'priority' => 'integer|min:1|max:10'
        ]);

        AIResponse::create([
            'intent_id' => $intentId,
            'response' => $validated['response'],
            'priority' => $validated['priority'] ?? 1
        ]);

        return back()->with('success', 'Response added successfully');
    }

    public function conversations()
    {
        $conversations = AIConversation::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.ai.conversations', compact('conversations'));
    }

    public function analytics()
    {
        $stats = [
            'total_conversations' => AIConversation::count(),
            'total_intents' => AIIntent::count(),
            'popular_intents' => AIConversation::select('intent', DB::raw('count(*) as count'))
                ->whereNotNull('intent')
                ->groupBy('intent')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'accuracy_stats' => AIConversation::selectRaw(
                'AVG(confidence) as avg_confidence,
                 COUNT(CASE WHEN confidence > 0.7 THEN 1 END) * 100.0 / COUNT(*) as accuracy_rate'
            )->first()
        ];

        return view('admin.ai.analytics', compact('stats'));
    }
}
