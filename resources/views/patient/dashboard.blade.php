@extends('layouts.patient')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Overview Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Upcoming Appointments</h6>
                        <h2 class="mt-2 mb-0">{{ $upcomingAppointments }}</h2>
                    </div>
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Recent Prescriptions</h6>
                        <h2 class="mt-2 mb-0">{{ $recentPrescriptions }}</h2>
                    </div>
                    <i class="fas fa-prescription fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Medical Records</h6>
                        <h2 class="mt-2 mb-0">{{ $medicalRecords }}</h2>
                    </div>
                    <i class="fas fa-file-medical fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Pending Bills</h6>
                        <h2 class="mt-2 mb-0">{{ $pendingBills }}</h2>
                    </div>
                    <i class="fas fa-file-invoice-dollar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Appointments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->date->format('M d, Y') }}</td>
                                <td>Dr. {{ $appointment->doctor->name }}</td>
                                <td>{{ $appointment->department->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status_color }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Book New Appointment
                    </a>
                    <a href="{{ route('patient.prescriptions') }}" class="btn btn-outline-primary">
                        <i class="fas fa-prescription me-2"></i> View Prescriptions
                    </a>
                    <a href="{{ route('patient.medical-records') }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-medical me-2"></i> Medical Records
                    </a>
                    <a href="{{ route('patient.billing') }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-invoice-dollar me-2"></i> View Bills
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Medical Records -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Medical Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMedicalRecords as $record)
                            <tr>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>Dr. {{ $record->doctor->name }}</td>
                                <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                                <td>{{ Str::limit($record->treatment, 50) }}</td>
                                <td>
                                    <a href="{{ route('patient.medical-records.show', $record) }}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
