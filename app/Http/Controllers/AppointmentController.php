<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query();

        if ($request->user()->role->slug === 'doctor') {
            $query->where('doctor_id', $request->user()->id);
        } elseif ($request->user()->role->slug === 'patient') {
            $query->where('patient_id', $request->user()->id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->with(['patient', 'doctor', 'prescription'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'symptoms' => 'required|string',
            'appointment_type' => 'required|in:in-person,video'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check doctor availability
        $doctor = DoctorProfile::where('user_id', $request->doctor_id)
            ->where('is_available', true)
            ->firstOrFail();

        // Check if the time slot is available
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingAppointment) {
            return response()->json([
                'message' => 'This time slot is already booked'
            ], 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $request->user()->id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'symptoms' => $request->symptoms,
            'appointment_type' => $request->appointment_type,
            'status' => 'pending',
            'amount' => $doctor->consultation_fee
        ]);

        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'prescription'])
            ->findOrFail($id);

        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Only allow status updates
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment->update($request->only('status'));

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Appointment cancelled successfully']);
    }
}
