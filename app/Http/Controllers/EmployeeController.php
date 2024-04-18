<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Country;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\DepartmentListResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EmployeeListResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeResources;
use App\Http\Resources\RoleResource;
use App\Models\Address;
use App\Models\Department;
use App\Models\EmployeeAddress;
use App\Models\Role;

class EmployeeController extends Controller
{
    public $department;
    public function __construct()
    {
        $this->department = Department::get();
    }
    public function index(Request $request)
    {
        $employees = User::where('company_id', $this->companyId())->orderBy('role_id', 'asc')->whereNotIn('id', [Auth::user()->id]);
        if (!empty($request->q)) {
            $employees =
                $employees->where('first_name', 'like', "%$request->q%")
                ->orWhere('last_name', 'like', "%$request->q%");
        }
        if (!empty($request->status)) {
            $employees =
                $employees->where('status', $request->status);
        }
        return Inertia::render('Employee/Index', [
            'employees' => EmployeeListResource::collection($employees->paginate(10)->append($request->all())),
        ]);
    }

    public function create()
    {
        return Inertia::render('Employee/Create', [
            'departments' => DepartmentListResource::collection($this->department),
            'roles' => RoleResource::collection(Role::all())
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'date_of_joining' => 'required',
            'emergency_number' => 'required|integer',
            'pan_number' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}/',
            'father_name' => 'required',
            'salary' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        $user = User::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role_id' => $request->role,
            'password' => Hash::make($request->password),
            'company_id' => $this->companyId(),
        ]);
        $employee =  Employee::create([
            'code' => 'ARS' . date('Y') . $user->id,
            'date_of_joining' => $request->date_of_joining,
            'emergency_number' => $request->emergency_number,
            'pan_number' => $request->pan_number,
            'father_name' => $request->father_name,
            'salary' => $request->salary,
            'last_working_day' => $request->last_working_day,
            'department_id' => $request->department,
            'user_id' => $user->id,
        ]);
        if ($employee) {
            return redirect("/employees")->with('flash', ['message' => 'Employee created successfully.']);
        }
        return redirect()->back()->withErrors(['message' => "Opps something went wrong"]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'date_of_joining' => 'required',
            'number' => 'required|numeric',
            'qualification' => 'required',
            'emergency_number' => 'required|integer',
            'pan_number' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}/',
            'father_name' => 'required',
            'formalities' => 'required|integer',
            'salary' => 'required',
            'offer_acceptance' => 'required|integer',
            'probation_period' => 'required',
            'date_of_confirmation' => 'required',
            'department' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        $employee = Employee::where('company_id', $this->companyId())->find($id);

        if ($employee) {
            if ($request->image_id) {
                User::where('id', $employee->user_id)->update([
                    'email' => $request->email,

                ]);
            }
            $employee = Employee::where(['id' => $employee->id])->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_joining' => $request->date_of_joining,
                'number' => $request->number,
                'qualification' => $request->qualification,
                'emergency_number' => $request->emergency_number,
                'pan_number' => $request->pan_number,
                'father_name' => $request->father_name,
                'formalities' => $request->formalities,
                'salary' => $request->salary,
                'offer_acceptance' => $request->offer_acceptance,
                'probation_period' => $request->probation_period,
                'date_of_confirmation' => $request->date_of_confirmation,
                'department_id' => $request->department,
                'user_id' => $employee->user->id,
                'image_id' => $request->image_id,

            ]);
            if ($employee) {
                if ($request->page) {
                    return redirect("/employee/$id")->with('flash', ['message' => 'Employee updated successfully.']);
                }
                return redirect("/employees")->with('flash', ['message' => 'Employee updated successfully.']);
            }
            return redirect()->back()->withErrors(['message' => "Opps something went wrong"]);
        }
        return redirect()->back()->withErrors(['message' => "Don't Have Access"]);
    }

    public function address($id)
    {
        $user = User::find($id);
        $countries = Country::get();
        if ($user) {
            return Inertia::render('Employee/Address', [
                'address' => $user->address ? new AddressResource($user->address) : [],
                'employee' => new EmployeeResource($user),
                'countries' => CountryResource::collection($countries),
            ]);
        }
        return redirect()->back();
    }
    public function updateAddress(Request $request, $id)
    {
        $address = [];
        $validator =  Validator::make($request->all(), [
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        if (Employee::where(['company_id' => $this->companyId()])->first()) {
            if ($address = EmployeeAddress::where(['employee_id' => $id])->first()) {
                $address = Address::where(['id' => $address->address_id])->update([
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country_id' => $request->country,
                    'pincode' => $request->pincode,
                ]);
                if ($address) {
                    return redirect("/employee/$id/address")->with('flash', ['message' => 'Address updated successfully.']);
                }
            } else {
                $address = Address::create([
                    'address_line_1' => $request->address_line_1,
                    'address_line_2' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country_id' => $request->country,
                    'pincode' => $request->pincode,
                ]);

                $empAddress = EmployeeAddress::create([
                    'employee_id' => $id,
                    'address_id' => $address->id,
                ]);
                if ($empAddress) {
                    return redirect("/employee/$id/address")->with('flash', ['message' => 'Address created successfully.']);
                }
            }
            return redirect()->back()->withErrors(['message' => 'Opps something went wrong!']);
        }
        return redirect()->back();
    }

    public function overview($id)
    {
        $user = User::find($id);
        if ($user->employee->addresss) {
            return Inertia::render('Employee/Overview', [
                'employee' => new EmployeeResource($user),
                'departments' => DepartmentListResource::collection($this->department),
                'address' => new AddressResource($user->employee->address),

            ]);
        }
        return Inertia::render('Employee/Overview', [
            'employee' => new EmployeeResource($user),
            'departments' => DepartmentListResource::collection($this->department),
        ]);
        return redirect()->back();
    }
    public function emailUpdate(Request $request, $id)
    {
        $employee = Employee::where('user_id', $id)->first();
        if ($employee) {
            $validator =  Validator::make($request->all(), [
                'email' => 'required|unique:users,email',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first(), 'success' => false]);
            }
            if ($employee) {
                $user = User::where('id', $id)->update([
                    'email' => $request->email,
                ]);
                if ($user) {
                    return response()->json(['message' => 'Email updated successfully.', 'success' => true]);
                }
            }
        }
        return response()->json(['message' => 'Opps something went wrong!', 'success' => false]);
    }

    public function changePassword(Request $request, $id)
    {
        $validator =  Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $employee = Employee::where('user_id', $id)->first();
        if ($employee) {
            User::where('id', $id)->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json(['message' => 'Password updated successfully.', 'success' => true]);
        }
        return response()->json(['message' => 'Opps something went wrong!', 'success' => false]);
    }
    public function deactivate($id)
    {
        $employee = User::where('id', $id)->first();
        if ($employee) {
            User::where('id', $id)->update(['status' => 0]);
            return response()->json(['success' => true, 'message' => 'User has been deactivated.', 'success' => true]);
        }
        return response()->json(['message' => 'Opps something went wrong!', 'success' => false]);
    }
    public function setting($id)
    {
        $user = User::find($id);
        if ($user) {
            return Inertia::render('Employee/Setting', [
                'employee' => new EmployeeResource($user),
            ]);
        }
        return redirect()->back();
    }

    public function attendance($id)
    {
        $employee = $this->employee($id);
        if ($employee) {
            return Inertia::render('Employee/Attendance', [
                'employee' => new EmployeeResources($employee),
                'user' => $this->employeeHeader($id),
                'address' => new AddressResource($employee?->address),


            ]);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $employee = Employee::where('company_id', $this->companyId())->find($id);
        if ($employee->delete()) {
            return response()->json(['success' => true, 'message' => 'Employee has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!'], 400);
    }
    public function selectDelete(Request $request)
    {
        $employee = Employee::whereIn('id', $request->ids)->delete();

        if ($employee) {
            return response()->json(['success' => true, 'message' => 'Employee has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!'], 400);
    }
}
