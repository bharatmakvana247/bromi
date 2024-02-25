<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Api\Properties;
use App\Models\Enquiries;
use App\Models\Projects;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function loginAsUser($userId)
	{
		$userToLogin = User::findOrFail($userId);

		$userToLogin->fill(['plan_id' => 1])->save();
		Auth::login($userToLogin);

		Session::put('plan_id', 4);

		return redirect('/admin');
	}

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = User::whereNotNull('vendor_id')->with('Plan')->get();
			return DataTables::of($data)
				->editColumn('plan', function ($row) {
					if (!empty($row->Plan->name)) {
						return $row->Plan->name;
					}
				})
				->editColumn('users', function ($row) {
					return User::where('parent_id', $row->id)->whereNull('vendor_id')->get()->count();
				})

				->editColumn('Actions', function ($row) {
					$buttons = '';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" onclick=getUser(this) class="fa fa-pencil pointer fa fs-22 py-2 mx-2"></i>';

					if ($row->role_id != 3) {
						if ($row->status) {
							$buttons =  $buttons . ' <button data-id="' . $row->id . '" onclick=userActivate(this,0) class="btn" style="border-radius: 5px !important;background-color: red !important;color: white !important;" type="button">Deactivate</button>';
						} else {
							$buttons =  $buttons . ' <button data-id="' . $row->id . '" onclick=userActivate(this,1) class="btn" style="border-radius: 5px !important;background-color: green !important;color: white !important;" type="button">Activate</button>';
						}
					}
					
					return $buttons;
				})
				->rawColumns(['Actions'])
				->make(true);
		}

		// dd($url);
		$roles =  Role::where('user_id')->get();
		return view('superadmin.users.index', compact('roles'));
	}

    public function membersList(Request $request)
	{
		if ($request->ajax()) {
			$data = User::where('role_id', '3')->where('id','!=',6)->with('Plan')->get();
			return DataTables::of($data)
				->editColumn('birth_date', function ($row) {
					if (!empty($row->birth_date)) {
						return date('d M, Y', strtotime($row->birth_date));
					}
				})

				->editColumn('Actions', function ($row) {
					$buttons = '';
					
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" onclick=getUser(this) class="fa-pencil pointer fa fs-22 py-2 mx-2" type="button"></i>';
					return $buttons;
				})
				->rawColumns(['Actions'])
				->make(true);
		}

		$roles =  Role::where('user_id')->get();
		return view('superadmin.members.index', compact('roles'));
	}

	public function changeStatus(Request $request)
	{
		if (!empty($request->id)) {
			$data = User::find($request->id);
			if (!empty($data)) {
				$data->status =  $request->status;
				$data->save();
			}
		}
	}

	public function saveUser(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = User::find($request->id);
			$data->first_name = $request->first_name;
			$data->last_name = $request->last_name;
			$data->email = $request->email;
			if (!empty($request->password)) {
				$data->password = Hash::make($request->password);
			}
			$data->status = 1;
			$data->role_id = 1;
			$data->save();
		} else {
			$data =  new User();
			$data->first_name = $request->first_name;
			$data->last_name = $request->last_name;
			$data->email = $request->email;
			if (!empty($request->password)) {
				$data->password = Hash::make($request->password);
			}
			$data->status = 1;
			$data->role_id = 1;
			$data->vendor_id =  Str::random(10);
			$data->save();
			$role =  new Role();
			$role->name = 'Admin_---_' . $data->id;
			$role->user_id = $data->id;
			$role->save();
			$role->syncPermissions(Permission::where('guard_name', 'web')->get()->pluck('id')->all());
			$data->syncRoles([]);
			$data->assignRole($role->name);
		}
	}

    public function saveMember(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = User::find($request->id);
			$data->first_name = $request->first_name;
			$data->last_name = $request->last_name;
			$data->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
			$data->email = $request->email;
			if (!empty($request->password)) {
				$data->password = Hash::make($request->password);
			}
			$data->status = 1;
			$data->role_id = 3;
			$data->permissions = json_encode($request->permissions);
            // $role->syncPermissions(Permission::where('guard_name', 'web')->get()->pluck('id')->all());
			$data->save();
		} else {
			$data =  new User();
			$data->first_name = $request->first_name;
			$data->last_name = $request->last_name;
            $data->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
			$data->email = $request->email;
			if (!empty($request->password)) {
				$data->password = Hash::make($request->password);
			}
			$data->role_id = 3;
			$data->status = 1;
			$data->plan_id = 1;
			$data->total_user_limit = 1;
            $data->permissions = json_encode($request->permissions);
			$data->save();
			$role =  Role::find(3);
			$role->syncPermissions(Permission::where('guard_name', 'web')->get()->pluck('id')->all());
			$data->syncRoles([]);
			$data->assignRole($role->name);
		}
	}

	public function getSpecificUser(Request $request)
	{
		if (!empty($request->id)) {

			$sub_users = User::where('parent_id', $request->id)->whereNull('vendor_id')->get();

			$users_id = [intval($request->id)];

			foreach ($sub_users as $user) {
				array_push($users_id, $user['id']);
			}

			$property_count = Properties::whereIn('user_id', $users_id)->get()->count();
			$project_count = Projects::whereIn('user_id', $users_id)->get()->count();
			$enquiry_count = Enquiries::whereIn('user_id', $users_id)->get()->count();

			$data = [
				'main_user' => User::where('id', $request->id)->first()->toArray(),
				'sub_user' => $sub_users,
				'total_property' => $property_count,
				'total_project' => $project_count,
				'total_enquiry' => $enquiry_count,
			];

			return response()->json($data);
		}
	}

    public function getSpecificMember(Request $request)
	{
		if (!empty($request->id)) {

			$sub_users = User::where('parent_id', $request->id)->whereNull('vendor_id')->get();

			$users_id = [intval($request->id)];

			foreach ($sub_users as $user) {
				array_push($users_id, $user['id']);
			}

			$property_count = Properties::whereIn('user_id', $users_id)->get()->count();
			$project_count = Projects::whereIn('user_id', $users_id)->get()->count();
			$enquiry_count = Enquiries::whereIn('user_id', $users_id)->get()->count();

			$data = [
				'main_user' => User::where('id', $request->id)->first()->toArray(),
				'sub_user' => $sub_users,
				'total_property' => $property_count,
				'total_project' => $project_count,
				'total_enquiry' => $enquiry_count,
			];

			return response()->json($data);
		}
	}

	public function destroy(Request $request)
	{
		if (!empty($request->id)) {
			$data = User::where('id', $request->id)->delete();
		}
	}
	
	public function destroyMember(Request $request)
	{
		if (!empty($request->id)) {
			$data = User::where('id', $request->id)->delete();
		}
	}
	
	public function loginActivity(Request $request)
	{
		if ($request->ajax()) {

			$data = DB::table('login_activities')->join('users','login_activities.user_id','users.id')
				->select([
					'login_activities.ip_address',
					'users.first_name AS username',
					'login_activities.date_time'
				])->where('user_id','!=',6)->get();

			return DataTables::of($data)->make(true);
		}

		return view('superadmin.users.login_activity');
	}
}
