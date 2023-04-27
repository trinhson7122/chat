<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Events\UpdateListMessageEvent;
use App\Models\ListMessageWithMe;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function fetchMessages(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
        ]);

        $data = Message::query()->where('from_user_id', $validated['from_user_id'])
            ->where('to_user_id', $validated['to_user_id'])
            ->orWhere('from_user_id', $validated['to_user_id'])
            ->where('to_user_id', $validated['from_user_id'])
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'list_message_id' => 'required|exists:list_message_with_mes,id',
            'message' => 'required',
        ]);

        $data = Message::create($validated);

        $obj = ListMessageWithMe::query()->with('fromUser', 'toUser')->find($validated['list_message_id']);
        $obj->update([
            'last_message' => $validated['message'],
            'last_user_id_send' => $validated['from_user_id'],
        ]);

        event(new SendMessageEvent($data, $obj->id));
        event(new UpdateListMessageEvent($obj, $data->to_user_id));

        return response()->json([
            'message' => $data,
            'list_message' => $obj,
        ]);
    }
}
