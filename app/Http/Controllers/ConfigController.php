<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        Config::create($validated);
        return response()->json([
            'message' => 'Created config successfuly',
        ]);
    }

    public function get(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        $config = Config::query()->where('key', strtolower($validated['key']))->get()->first();
        if (!$config) {
            return response()->json([
                'message' => 'The config not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $config->value,
        ]);
    }
}
