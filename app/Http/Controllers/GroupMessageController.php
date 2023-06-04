<?php

namespace App\Http\Controllers;

use App\Models\GroupMessage;
use App\Models\ListMessageWithMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'avatar' => 'nullable|image',
            'user_id' => 'required|exists:users,id',
            'listUserId' => 'nullable',
        ]);

        $obj = GroupMessage::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
        ]);

        if (isset($validated['listUserId'])) {
            foreach ($validated['listUserId'] as $item) {
                ListMessageWithMe::create([
                    'from_user_id' => $item,
                    'to_group_id' => $obj->id,
                ]);
            }
        }

        $messageWithMe = ListMessageWithMe::create([
            'from_user_id' => $validated['user_id'],
            'to_group_id' => $obj->id,
        ]);
        $messageWithMe = ListMessageWithMe::query()->with(['fromUser', 'toGroup'])->find($messageWithMe->id);
        $arr = $messageWithMe->toArray();
        $arr['to_group'] = $messageWithMe->toGroup->getGroupWithShortName();
        $arr['from_user'] = $messageWithMe->fromUser->getUserWithShortName();

        if (isset($validated['avatar'])) {
            $disk = Storage::disk('google');
            $content = $request->file('avatar')->getContent();
            $extension = $request->file('avatar')->getExtension();
            $filename = "group_" . $obj->id . '/' . time() . '.' . $extension;

            $disk->put($filename, $content);
            $obj->avatar = $disk->url($filename);
            $obj->save();
        }

        return response()->json($arr);
    }
}
