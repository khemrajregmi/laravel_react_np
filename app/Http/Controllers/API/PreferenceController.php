<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        dd('you reach here');
        $user = $request->user();

        $preference = Preference::where('user_id', $user->id)->first();

        return response()->json($preference);
    }

    public function store(PreferenceRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $userId = Auth::id();

        Preference::where('user_id', $userId)->delete();

        $validatedData['user_id'] = $userId;

        $preference = Preference::create($validatedData);

        return response()->json(['message' => 'Preference saved successfully', 'preference' => $preference], 200);
    }
}
