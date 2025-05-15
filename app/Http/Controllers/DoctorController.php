<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function getAppointments(Request $request)
    {
        $query = Appointment::where('doctor_id', $request->user()->id);

        if ($request->date) {
            $query->whereDate('appointment_datetime', $request->date);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->with(['patient'])->orderBy('appointment_datetime')->paginate(10);

        return $this->success(['appointments' => $appointments]);
    }

    public function updateAppointment(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:confirmed,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $appointment->update($request->only(['status', 'notes']));

        return $this->success(['appointment' => $appointment], 'Appointment updated successfully');
    }

    public function createMedicalRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'test_results' => 'nullable|array',
            'attachments' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $medicalRecord = MedicalRecord::create(array_merge(
            $request->all(),
            ['doctor_id' => $request->user()->id]
        ));

        return $this->success(['medical_record' => $medicalRecord], 'Medical record created successfully');
    }

    public function getPatientHistory(Request $request, User $patient)
    {
        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor', 'prescriptions', 'labTests'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success(['medical_records' => $medicalRecords]);
    }

    public function createPrescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'required|exists:medical_records,id',
            'patient_id' => 'required|exists:users,id',
            'medications' => 'required|array',
            'medications.*.name' => 'required|string',
            'medications.*.dosage' => 'required|string',
            'medications.*.frequency' => 'required|string',
            'medications.*.duration' => 'required|string',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $prescription = Prescription::create(array_merge(
            $request->all(),
            [
                'doctor_id' => $request->user()->id,
                'prescription_date' => now(),
                'status' => 'pending'
            ]
        ));

        return $this->success(['prescription' => $prescription], 'Prescription created successfully');
    }

    public function getDashboardStats(Request $request)
    {
        $today = now()->format('Y-m-d');

        $stats = [
            'today_appointments' => Appointment::where('doctor_id', $request->user()->id)
                ->whereDate('appointment_datetime', $today)
                ->count(),
            'pending_appointments' => Appointment::where('doctor_id', $request->user()->id)
                ->where('status', 'scheduled')
                ->count(),
            'total_patients' => MedicalRecord::where('doctor_id', $request->user()->id)
                ->distinct('patient_id')
                ->count('patient_id'),
            'recent_medical_records' => MedicalRecord::where('doctor_id', $request->user()->id)
                ->with(['patient'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return $this->success(['stats' => $stats]);
    }
}
