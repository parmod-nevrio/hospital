<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'specialization' => 'nullable|string',
            'qualification' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'specialization' => $request->specialization,
            'qualification' => $request->qualification,
            'is_active' => true
        ]);

        return $this->success(['user' => $user], 'User created successfully');
    }

    public function updateUser(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'string|max:20',
            'address' => 'string',
            'date_of_birth' => 'date',
            'gender' => 'in:male,female,other',
            'specialization' => 'nullable|string',
            'qualification' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user->update($request->only([
            'name',
            'email',
            'role_id',
            'department_id',
            'phone',
            'address',
            'date_of_birth',
            'gender',
            'specialization',
            'qualification',
            'is_active'
        ]));

        return $this->success(['user' => $user], 'User updated successfully');
    }

    public function listUsers(Request $request)
    {
        $query = User::query();

        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->with(['role', 'department'])->paginate(10);

        return $this->success(['users' => $users]);
    }

    public function createDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'department_head_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $department = Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'department_head_id' => $request->department_head_id,
            'is_active' => true
        ]);

        return $this->success(['department' => $department], 'Department created successfully');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'code' => 'string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'department_head_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $department->update($request->only([
            'name',
            'code',
            'description',
            'department_head_id',
            'is_active'
        ]));

        return $this->success(['department' => $department], 'Department updated successfully');
    }

    public function listDepartments(Request $request)
    {
        $query = Department::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $departments = $query->with(['head'])->paginate(10);

        return $this->success(['departments' => $departments]);
    }

    public function getDashboardStats()
    {
        $stats = [
            'total_doctors' => User::whereHas('role', function ($q) {
                $q->where('slug', 'doctor');
            })->count(),
            'total_patients' => User::whereHas('role', function ($q) {
                $q->where('slug', 'patient');
            })->count(),
            'total_departments' => Department::count(),
            'active_departments' => Department::where('is_active', true)->count(),
        ];

        return $this->success(['stats' => $stats]);
    }
}
