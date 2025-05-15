<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\LabTest;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_datetime' => 'required|date|after:now',
            'appointment_type' => 'required|string',
            'reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        // Check if doctor is available at the requested time
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_datetime', $request->appointment_datetime)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingAppointment) {
            return $this->error('Doctor is not available at the selected time', 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $request->user()->id,
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'appointment_datetime' => $request->appointment_datetime,
            'appointment_type' => $request->appointment_type,
            'reason' => $request->reason,
            'status' => 'scheduled',
            'created_by' => $request->user()->id
        ]);

        return $this->success(['appointment' => $appointment], 'Appointment booked successfully');
    }

    public function getAppointments(Request $request)
    {
        $query = Appointment::where('patient_id', $request->user()->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->with(['doctor', 'department'])
            ->orderBy('appointment_datetime', 'desc')
            ->paginate(10);

        return $this->success(['appointments' => $appointments]);
    }

    public function getMedicalRecords(Request $request)
    {
        $medicalRecords = MedicalRecord::where('patient_id', $request->user()->id)
            ->with(['doctor', 'prescriptions', 'labTests'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success(['medical_records' => $medicalRecords]);
    }

    public function getPrescriptions(Request $request)
    {
        $prescriptions = Prescription::where('patient_id', $request->user()->id)
            ->with(['doctor', 'medicalRecord'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success(['prescriptions' => $prescriptions]);
    }

    public function getLabTests(Request $request)
    {
        $labTests = LabTest::where('patient_id', $request->user()->id)
            ->with(['doctor', 'technician'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success(['lab_tests' => $labTests]);
    }

    public function getBillings(Request $request)
    {
        $billings = Billing::where('patient_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success(['billings' => $billings]);
    }

    public function getDoctors(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('slug', 'doctor');
        });

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('specialization', 'like', "%{$request->search}%");
            });
        }

        $doctors = $query->with(['department'])
            ->where('is_active', true)
            ->paginate(10);

        return $this->success(['doctors' => $doctors]);
    }

    public function getDashboardStats(Request $request)
    {
        $stats = [
            'total_appointments' => Appointment::where('patient_id', $request->user()->id)->count(),
            'upcoming_appointments' => Appointment::where('patient_id', $request->user()->id)
                ->where('appointment_datetime', '>', now())
                ->where('status', '!=', 'cancelled')
                ->count(),
            'total_prescriptions' => Prescription::where('patient_id', $request->user()->id)->count(),
            'pending_lab_tests' => LabTest::where('patient_id', $request->user()->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'unpaid_bills' => Billing::where('patient_id', $request->user()->id)
                ->whereIn('status', ['pending', 'partial'])
                ->sum('total_amount')
        ];

        return $this->success(['stats' => $stats]);
    }
}
