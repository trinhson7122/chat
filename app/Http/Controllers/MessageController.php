<?php

namespace App\Http\Controllers;

use App\Events\RemoveMessageEvent;
use App\Events\SendMessageEvent;
use App\Events\UpdateListMessageEvent;
use App\Events\UpdateUserOnlineEvent;
use App\Models\ListMessageWithMe;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $arrData = [];
        foreach ($data as $item) {
            $arr = $item->toArrWithCreatedAtForHumans();
            $arrData[] = $arr;
        }
        return response()->json($arrData);
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
        $arr = $obj->toArray();
        $arr['to_user'] = $obj->toUser->getUserWithShortName();
        $arr['from_user'] = $obj->fromUser->getUserWithShortName();
        $this->setUserOnline($request->user());
        event(new SendMessageEvent($data->toArrWithCreatedAtForHumans(), $obj->id));
        event(new UpdateListMessageEvent($arr, $data->to_user_id));
        return response()->json([
            'message' => $data,
            'list_message' => $arr,
        ]);
    }

    public function removeMessage(Request $request)
    {
        $validated = $request->validate([
            'message_id' => 'required|exists:messages,id'
        ]);

        $obj = Message::find($validated['message_id']);
        $obj->is_removed = 1;
        $obj->save();

        event(new RemoveMessageEvent($obj->toArrWithCreatedAtForHumans(), $obj->list_message_id));
        return response()->json([
            'message' => "Thu hồi tin nhắn thành công",
        ], 200);
    }

    public function storeFile(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'from_user_id' => 'required|exists:users,id',
            'list_message_id' => 'required|exists:list_message_with_mes,id',
            'file' => 'required|file|mimes:txt,jpeg,png,jpg,gif,svg,rar,zip,docx,pdf,xlsx,pptx,doc|max:30720',
        ]);
        //dd($request->file('file'));
        $disk = Storage::disk('google');
        $path = $validated['list_message_id'] . '/';
        $file_path = $path . $request->file('file')->getClientOriginalName();
        $result = true;
        if ($disk->missing($file_path)) {
            $result = $disk->put($file_path, $request->file('file')->getContent());
        }

        $data = Message::create([
            'to_user_id' => $validated['to_user_id'],
            'from_user_id' => $validated['from_user_id'],
            'list_message_id' => $validated['list_message_id'],
            'message' => $request->file('file')->getClientOriginalName() . '|' . formatBytes($request->file('file')->getSize()),
            'is_file' => 1,
        ]);
        if ($result) {
            $list_message = ListMessageWithMe::find($data->list_message_id);
            $list_message->update([
                'last_message' => "Đã gửi một file đính kèm",
                'last_user_id_send' => $validated['from_user_id'],
            ]);
            $arr = $list_message->toArray();
            $arr['to_user'] = $list_message->toUser->getUserWithShortName();
            $arr['from_user'] = $list_message->fromUser->getUserWithShortName();

            event(new SendMessageEvent($data->toArrWithCreatedAtForHumans(), $data->list_message_id));
            event(new UpdateListMessageEvent($arr, $data->to_user_id));

            return response()->json($data);
        }
        return response()->json($data, 400);
    }

    public function downloadFile(Request $request, $list_message_id, $path)
    {
        $disk = Storage::disk('google');
        if ($disk->missing("$list_message_id/$path")) {
            return response()->json('File không tồn tại', 404);
        }
        return $disk->download("$list_message_id/$path");
    }

    public function setUserOnline($user)
    {
        $now = new Carbon();
        if ($now->diffInMinutes($user->last_online_at) > 50) {
            $user->last_online_at = $now->toDateTimeString();
            $user->save();
            event(new UpdateUserOnlineEvent($user->getUserWithShortName()));
        }
    }
}
