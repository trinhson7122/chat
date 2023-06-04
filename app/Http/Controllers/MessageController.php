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
            'to_user_id' => 'nullable|exists:users,id',
            'to_group_id' => 'nullable|exists:group_messages,id',
        ]);

        $data = null;
        if ($request->has('to_user_id')) {
            $data = Message::query()->where('from_user_id', $validated['from_user_id'])
                ->where('to_user_id', $validated['to_user_id'])
                ->orWhere('from_user_id', $validated['to_user_id'])
                ->where('to_user_id', $validated['from_user_id'])
                ->with('fromUser')
                ->get();
        } else {
            $data = Message::query()
                ->where('to_group_id', $validated['to_group_id'])
                ->with('fromUser')
                ->get();
        }

        $arrData = [];
        foreach ($data as $item) {
            $arr = $item->toArrWithCreatedAtForHumans();
            $arr['from_user'] = $item->fromUser->getUserWithShortName();
            $arrData[] = $arr;
        }
        return response()->json($arrData);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'nullable|exists:users,id',
            'to_group_id' => 'nullable|exists:group_messages,id',
            'list_message_id' => 'required|exists:list_message_with_mes,id',
            'message' => 'required',
        ]);

        if ($request->has('to_group_id')) {
            $validated['is_group'] = 1;
        }
        $data = Message::create($validated);
        //$data = Message::query()->find($data->id)->with('fromUser')->first();
        $obj = null;
        $arr = [];

        if ($request->has('to_user_id')) {
            $obj = ListMessageWithMe::query()->with('fromUser', 'toUser')->find($validated['list_message_id']);
            $obj->update([
                'last_message' => $validated['message'],
                'last_user_id_send' => $validated['from_user_id'],
            ]);
            $arr = $obj->toArray();
            $arr['to_user'] = $obj->toUser->getUserWithShortName();
            $arr['from_user'] = $obj->fromUser->getUserWithShortName();

            event(new SendMessageEvent($data->toArrWithCreatedAtForHumans(), $obj->id));
            event(new UpdateListMessageEvent($arr, $data->to_user_id));
        } else {
            $list = ListMessageWithMe::query()
                ->where('to_group_id', $validated['to_group_id'])
                ->with('fromUser', 'toGroup')
                ->get();
            //$obj = ListMessageWithMe::query()->with('fromUser', 'toGroup')->find($validated['list_message_id']);

            foreach ($list as $obj) {
                $obj->update([
                    'last_message' => $validated['message'],
                    'last_user_id_send' => $validated['from_user_id'],
                ]);
                $arr = $obj->toArray();
                $arr['to_group'] = $obj->toGroup->getGroupWithShortName();
                $arr['from_user'] = $obj->fromUser->getUserWithShortName();

                $temp = $data->toArrWithCreatedAtForHumans();
                $temp['from_user'] = $data->fromUser->getUserWithShortName();

                event(new SendMessageEvent($temp, $obj->id));
                event(new UpdateListMessageEvent($arr, $obj->from_user_id));
            }
        }


        $this->setUserOnline($request->user());
        //event(new SendMessageEvent($data->toArrWithCreatedAtForHumans(), $obj->id));
        //event(new UpdateListMessageEvent($arr, $data->to_user_id));
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

        // if ($obj->is_group) {
        //     $lwms = ListMessageWithMe::query()
        //         ->where('to_group_id', $obj->to_group_id)
        //         ->with('fromUser', 'toGroup')
        //         ->get();

        //     foreach ($lwms as $item) {
        //         $item->update([
        //            'last_user_id_send' => $request->user()->id,
        //            'last_message' => 'Thu hồi một tin nhắn'
        //         ]);
        //         $arr = $item->toArray();
        //         $arr['to_group'] = $item->toGroup->getGroupWithShortName();
        //         $arr['from_user'] = $item->fromUser->getUserWithShortName();

        //         event(new UpdateListMessageEvent($arr, $item->from_user_id));
        //         event(new RemoveMessageEvent($obj->toArrWithCreatedAtForHumans(), $item->id));
        //     }
        // }
        // else {
        //     event(new RemoveMessageEvent($obj->toArrWithCreatedAtForHumans(), $obj->list_message_id));
        // }

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
        $mimeType = $request->file('file')->getMimeType();
        $mime = explode('/', $mimeType)[0];

        if ($disk->missing($file_path)) {
            $result = $disk->put($file_path, $request->file('file')->getContent());
        }

        $arrCreate = [
            'to_user_id' => $validated['to_user_id'],
            'from_user_id' => $validated['from_user_id'],
            'list_message_id' => $validated['list_message_id'],
        ];
        if ($mime == 'image') {
            $arrCreate['is_image'] = 1;
            $arrCreate['message'] = $disk->url($file_path);
        } else {
            $arrCreate['is_file'] = 1;
            $arrCreate['message'] = $request->file('file')->getClientOriginalName() . '|' . formatBytes($request->file('file')->getSize());
        }

        $data = Message::create($arrCreate);
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
        $headers = [
            'Content-Length: ' . $disk->size("$list_message_id/$path"),
        ];
        return $disk->download("$list_message_id/$path", $path, $headers);
        //return response()->download("$list_message_id/$path", $path, $headers);
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
