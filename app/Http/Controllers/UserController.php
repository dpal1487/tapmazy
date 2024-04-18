<?php

namespace App\Http\Controllers;

use App\Exports\UserProjectReports;
use App\Http\Resources\PlanResource;
use App\Http\Resources\RespondentResource;
use App\Http\Resources\User\{UserListResource, UserResource, UserShowResource};
use App\Models\Respondent;
use App\Models\Role;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public $role;
    public function __construct()
    {
        $this->role['roles'] = Role::get();
    }
    public function index(Request $request)
    {
        $users = User::orderBy('first_name', 'asc');
        if ($request->q) {
            $users = $users->where('first_name', 'like', "%{$request->q}%");
        }
        $users = $users->paginate(20)->appends(request()->query());
        return Inertia::render('User/Index', [
            'users' => UserListResource::collection($users),
            'allowed_users' => auth()->user()->stripePlan,
        ]);
    }
    public function transfer()
    {
        $surveys = Survey::get();
        // return $surveys;
        foreach ($surveys as $survey) {
            Respondent::where(['id' => $survey->respondent_id])->update(['user_id' => $survey->user_id, 'project_id' => $survey->project_id, 'project_link_id' => $survey->project_link_id, 'starting_ip' => $survey->starting_ip, 'end_ip' => $survey->end_ip, 'client_browser' => $survey->client_browser, 'device' => $survey->device, 'status' => $survey->status]);
        }
    }
    public function create()
    {
        return Inertia::render('User/Form', [
            'role' => $this->role,
            'users' => count(User::get()),
            'allowed_users' => new PlanResource(auth()->user()->stripePlan),
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email',
            'status' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        if ($user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'status' => $request->status,
            'role_id'  => $request->role,
            'password' => Hash::make($request->password)
        ])) {

            $role = Role::where('slug', $request->role)->first();
            $user->roles()->attach($role);
            // UsersRole::create(['role_id' => $request->role, 'user_id' => $user->id]);
            return redirect('/users')->with('flash', ['message' => 'User added successfully.']);
        }
        return redirect('/users')->with('flash', ['message' => 'Opps! something went wrong.']);
    }

    public function edit($id)
    {
        $user = User::find($id);

        return Inertia::render('User/Form', [
            'user' => new UserResource($user),
            'role' => $this->role,
        ]);
    }
    public function show($id)
    {
        $user = User::find($id);
        return Inertia::render('User/Overview', [
            'user' => new UserShowResource($user),
            'role' => $this->role,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required',
            'status' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }

        $user = User::where('id', $id)->first();
        if ($id != 1) {
            if ($user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'role_id'  => $request->role
            ])) {
                // assignRole
                $role = Role::find($request->role);
                if (count($user->roles) > 0) {
                    $user->roles()->sync($role); // Update the role
                } else {
                    $user->roles()->attach($role);
                }

                if ($request->action == "user.overview") {
                    return redirect('/user/' . $id)->with('flash', ['message' => 'User updated successfully.']);
                } else {
                    return redirect('/users')->with('flash', ['message' => 'User updated successfully.']);
                }
            }
            return redirect('/users')->with('flash', ['message' => 'Opps! something went wrong.']);
        }
        return redirect('users')->withErrors(['message' => "Your can't be edit super admin."]);
    }
    public function destroy($id)
    {
        if (User::find($id) && implode('.', Auth::user()->roles->pluck('slug')->toArray()) == 'admin') {
            if (User::where('id', $id)->delete()) {
                return response()->json(['message' => 'Project link deleted successfully.', 'success' => true]);
            }
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }

    public function projects(Request $request, $id)
    {
        $surveys = Respondent::where('user_id', $id)->orderBy('created_at', 'desc');
        $user = User::find($id);
        if (!empty($request->q)) {
            $surveys = $surveys->where(function ($query) use ($request) {
                $query->whereHas('project', function ($projectQuery) use ($request) {
                    $projectQuery->where('project_name', 'like', "%$request->q%");
                })->orWhereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('first_name', 'like', "%$request->q%")->orWhere('last_name', 'like', "%$request->q%");
                });
            });
        }
        if ($request->status != 'all' && $request->status !== null) {
            $surveys = $surveys->where('status', $request->status);
        }
        return Inertia::render('User/Project', [
            'surveys' => RespondentResource::collection($surveys->paginate(20)->appends(request()->query())),
            'user' => new UserShowResource($user),
            'role' => $this->role,
        ]);
    }

    public function exportProjectIds(Request $request)
    {
        $user = User::find($request->user_id);
        return Excel::download(new UserProjectReports($request), ucwords($user->first_name . '_' . $user->last_name) . "_ProjectReport.xlsx");
    }
}
