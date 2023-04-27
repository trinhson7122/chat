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
        $now->subMinutes(1000);
        $users = User::query()->where("last_online_at", '>=', $now->toDateTimeString())
            ->where('id', '<>', $me->id)
            ->limit(20)->get();

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
