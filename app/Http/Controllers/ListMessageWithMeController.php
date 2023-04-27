<?php

namespace App\Http\Controllers;

use App\Models\ListMessageWithMe;
use Illuminate\Http\Request;

class ListMessageWithMeController extends Controller
{
    public function store(Request $request)
    {
        $request->user()->setTimeOnline();
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
        ]);

        $obj = ListMessageWithMe::query()->where('from_user_id', $validated['from_user_id'])
            ->where('to_user_id', $validated['to_user_id'])
            ->orWhere('from_user_id', $validated['to_user_id'])
            ->where('to_user_id', $validated['from_user_id'])
            ->get()->first();
        if ($obj) {
            return response()->json([
                'status' => false,
            ], 404);
        }
        $obj = ListMessageWithMe::create($validated);
        return response()->json($obj);
    }

    public function listWithMe(Request $request)
    {
        $request->user()->setTimeOnline();
        $me = $request->user();
        $obj = ListMessageWithMe::query()->where('from_user_id', $me->id)
            ->orWhere('to_user_id', $me->id)
            ->with('fromUser', 'toUser')
            ->orderByDesc('updated_at')
            ->get();
        return response()->json($obj);
    }
}
