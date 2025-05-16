<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalHistory::query();

        if ($request->user()->role->slug === 'doctor') {
            $query->where('patient_id', $request->patient_id);
        } elseif ($request->user()->role->slug === 'patient') {
            $query->where('patient_id', $request->user()->id);
        }

        $histories = $query->with('patient')
            ->orderBy('diagnosed_date', 'desc')
            ->paginate(10);

        return response()->json($histories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'condition' => 'required|string',
            'description' => 'required|string',
            'diagnosed_date' => 'required|date',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|array',
            'allergies' => 'nullable|array',
            'family_history' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the user is a doctor
        if ($request->user()->role->slug !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history = MedicalHistory::create($request->all());

        return response()->json($history->load('patient'), 201);
    }

    public function show($id)
    {
        $history = MedicalHistory::with('patient')
            ->findOrFail($id);

        return response()->json($history);
    }

    public function update(Request $request, $id)
    {
        $history = MedicalHistory::findOrFail($id);

        // Check if the user is a doctor
        if ($request->user()->role->slug !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'condition' => 'required|string',
            'description' => 'required|string',
            'diagnosed_date' => 'required|date',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|array',
            'allergies' => 'nullable|array',
            'family_history' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $history->update($request->all());

        return response()->json($history->load('patient'));
    }

    public function destroy(Request $request, $id)
    {
        $history = MedicalHistory::findOrFail($id);

        // Check if the user is a doctor
        if ($request->user()->role->slug !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history->delete();

        return response()->json(['message' => 'Medical history deleted successfully']);
    }
}
