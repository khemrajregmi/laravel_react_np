<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $preference = Preference::with('category')->where('user_id', $user->id)->first();
        return response()->json($preference);
    }

    public function getPreferences($id): JsonResponse
    {
        // Assuming the preferences are stored in a "preferences" table and each preference has a "user_id" column
        $preference = Preference::with('category')->where('user_id', $id)->first();

        return response()->json($preference);
    }

    public function store(PreferenceRequest $request): JsonResponse
    {
        $validatedData = $request->all();

        $userId = Auth::id();

        Preference::where('user_id', $userId)->delete();

        $validatedData['user_id'] = $userId;

        $preference = Preference::create($validatedData);

        return response()->json(['message' => 'Preference saved successfully', 'preference' => $preference], 200);
    }
}
