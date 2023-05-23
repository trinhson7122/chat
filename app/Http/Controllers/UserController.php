<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUserOnline(Request $request)
    {
        $me = $request->user();
        $now = new Carbon();
        //$now->subMinutes(10);
        $now->subMinutes(60);
        $users = User::query()->where("last_online_at", '>=', $now->toDateTimeString())
            ->where('id', '<>', $me->id)
            ->limit(20)->get();

        $arrs = [];
        foreach ($users as $user) {
            $arrs[] = $user->getUserWithShortName();
        }
        return response()->json($arrs);
    }

    public function fetchUser(Request $request)
    {
        $users = User::query()
            ->orderByDesc('last_online_at')
            ->get();
        $arrs = [];
        foreach ($users as $user) {
            $arrs[] = $user->getUserWithShortName();
        }
        return response()->json($arrs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update($validated);

        return response()->json($user->getUserWithShortName());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
