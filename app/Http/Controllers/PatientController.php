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
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'patient']);
    }

    public function dashboard()
    {
        $patient = Auth::user();

        $data = [
            'upcomingAppointments' => Appointment::where('patient_id', $patient->id)
                ->where('date', '>=', now())
                ->count(),
            'recentPrescriptions' => Prescription::where('patient_id', $patient->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
            'medicalRecords' => MedicalRecord::where('patient_id', $patient->id)->count(),
            'pendingBills' => Billing::where('patient_id', $patient->id)
                ->where('status', 'pending')
                ->count(),
            'recentAppointments' => Appointment::where('patient_id', $patient->id)
                ->with(['doctor', 'department'])
                ->latest()
                ->take(5)
                ->get(),
            'recentMedicalRecords' => MedicalRecord::where('patient_id', $patient->id)
                ->with('doctor')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('patient.dashboard', $data);
    }

    public function appointments()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with(['doctor', 'department'])
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function showAppointment(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('patient.appointments.show', compact('appointment'));
    }

    public function createAppointment()
    {
        $departments = Department::all();
        $doctors = Doctor::all();
        return view('patient.appointments.create', compact('departments', 'doctors'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'department_id' => 'required|exists:departments,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $validated['doctor_id'],
            'department_id' => $validated['department_id'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully!');
    }

    public function medicalRecords()
    {
        $records = MedicalRecord::where('patient_id', Auth::id())
            ->with('doctor')
            ->latest()
            ->paginate(10);

        return view('patient.medical-records.index', compact('records'));
    }

    public function showMedicalRecord(MedicalRecord $record)
    {
        $this->authorize('view', $record);
        return view('patient.medical-records.show', compact('record'));
    }

    public function prescriptions()
    {
        $prescriptions = Prescription::where('patient_id', Auth::id())
            ->with('doctor')
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function showPrescription(Prescription $prescription)
    {
        $this->authorize('view', $prescription);
        return view('patient.prescriptions.show', compact('prescription'));
    }

    public function billing()
    {
        $bills = Billing::where('patient_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('patient.billing.index', compact('bills'));
    }

    public function showBill(Billing $bill)
    {
        $this->authorize('view', $bill);
        return view('patient.billing.show', compact('bill'));
    }

    public function profile()
    {
        return view('patient.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'required|string|max:5',
        ]);

        $user->update($validated);

        return redirect()
            ->route('patient.profile')
            ->with('success', 'Profile updated successfully!');
    }

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
