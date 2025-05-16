<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::query();

        if ($request->user()->role->slug === 'doctor') {
            $query->where('doctor_id', $request->user()->id);
        } elseif ($request->user()->role->slug === 'patient') {
            $query->where('patient_id', $request->user()->id);
        }

        $prescriptions = $query->with(['patient', 'doctor', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($prescriptions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'medications' => 'required|string',
            'instructions' => 'required|string',
            'notes' => 'nullable|string',
            'next_visit' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        // Check if the user is the doctor of this appointment
        if ($request->user()->id !== $appointment->doctor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $prescription = Prescription::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'diagnosis' => $request->diagnosis,
            'medications' => $request->medications,
            'instructions' => $request->instructions,
            'notes' => $request->notes,
            'next_visit' => $request->next_visit
        ]);

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        return response()->json($prescription->load(['patient', 'doctor', 'appointment']), 201);
    }

    public function show($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'appointment'])
            ->findOrFail($id);

        return response()->json($prescription);
    }

    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);

        // Check if the user is the doctor who created this prescription
        if ($request->user()->id !== $prescription->doctor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'diagnosis' => 'required|string',
            'medications' => 'required|string',
            'instructions' => 'required|string',
            'notes' => 'nullable|string',
            'next_visit' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $prescription->update($request->all());

        return response()->json($prescription->load(['patient', 'doctor', 'appointment']));
    }
}
