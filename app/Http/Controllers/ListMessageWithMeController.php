<?php

namespace App\Http\Controllers;

use App\Models\ListMessageWithMe;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

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
        $obj = ListMessageWithMe::query()->with(['fromUser', 'toUser'])->find($obj->id);
        $arr = $obj->toArray();
        $arr['to_user'] = $obj->toUser->getUserWithShortName();
        $arr['from_user'] = $obj->fromUser->getUserWithShortName();
        return response()->json($arr);
    }

    public function listWithMe(Request $request)
    {
        $request->user()->setTimeOnline();
        $me = $request->user();
        $obj = ListMessageWithMe::query()->where('from_user_id', $me->id)
            ->orWhere('to_user_id', $me->id)
            ->whereNotNull('last_user_id_send')
            ->with('fromUser', 'toUser')
            ->orderByDesc('updated_at')
            ->get();
        
        $arrObj = [];
        foreach ($obj as $item) {
            $arr = $item->toArray();
            //dd($item->toUser->getUserWithShortName());
            $arr['to_user'] = $item->toUser->getUserWithShortName();
            $arr['from_user'] = $item->fromUser->getUserWithShortName();
            $arrObj[] = $arr;
        }
        return response()->json($arrObj);
    }
}
