<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use App\Models\SuperAreas;
use App\Models\SuperCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SuperSettingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function district_index(Request $request)
	{
		if ($request->ajax()) {

			$data = District::join('state','state.id','district.state_id')
				->select([
					'district.name',
					'district.id',
					'state.name AS state_name',
				])
				->where('district.user_id', Auth::user()->id)
				->get();

			return DataTables::of($data)
				->editColumn('Actions', function ($row) {
					$buttons = '';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Edit" onclick=getDistrict(this) class="fa-pencil pointer fa fs-22 py-2 mx-2  " type="button"></i>';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Delete" onclick=deleteDistrict(this) class="fa-trash pointer fa fs-22 py-2 mx-2 text-danger" type="button"></i>';
					return $buttons;
				})
				->rawColumns(['Actions', 'select_checkbox'])
				->make(true);
		}

		$states = State::where('user_id', Auth::user()->id)->get();

		return view('superadmin.supersettings.super_district_index', compact('states'));
	}

	public function get_district(Request $request)
	{
		if (!empty($request->id)) {
			$data = District::where('id', $request->id)->first()->toArray();
			return json_encode($data);
		}
	}

	public function district_store(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = District::find($request->id);
			if (empty($data)) {
				$data =  new District();
			}
		} else {
			$data =  new District();
		}
		$data->name = $request->name;
		$data->state_id = $request->state_id;
		$data->user_id = Auth::user()->id;
		$data->save();
	}

	public function destroy_district(Request $request)
	{
		if (!empty($request->id)) {
			$data = District::where('id', $request->id)->delete();
		}
	}

	public function states_index(Request $request)
	{
		if ($request->ajax()) {

			$data = State::where('user_id', Auth::user()->id)->get();

			return DataTables::of($data)
				->editColumn('Actions', function ($row) {
					$buttons = '';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Edit" onclick=getState(this) class="fa-pencil pointer fa fs-22 py-2 mx-2  " type="button"></i>';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Delete" onclick=deleteState(this) class="fa-trash pointer fa fs-22 py-2 mx-2 text-danger" type="button"></i>';
					return $buttons;
				})
				->rawColumns(['Actions', 'select_checkbox'])
				->make(true);
		}

		return view('superadmin.supersettings.super_state_index');
	}

	public function get_state(Request $request)
	{
		if (!empty($request->id)) {
			$data =  State::where('id', $request->id)->first()->toArray();
			return json_encode($data);
		}
	}

	public function state_store(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = State::find($request->id);
			if (empty($data)) {
				$data =  new State();

				$state = State::where('name',$request->name)->where('id','!=',$data->id)->where('user_id',Auth::user()->id)->first();

				if(!$state) {
					$data->name = $request->name;
					$data->save();
				}
			} else  {
				$data->name = $request->name;
				$data->save();
			}
		} else {
			$state = State::where('name',$request->name)->where('user_id',Auth::user()->id)->first();

			if(!$state) {
				$data =  new State();
				$data->user_id = Auth::user()->id;
				$data->name = $request->name;
				$data->save();
			}
		}
	}

	public function destroy_state(Request $request)
	{
		if (!empty($request->id)) {
			State::where('id', $request->id)->delete();
		}
	}

	public function cities_index(Request $request)
	{
		if ($request->ajax()) {

			$data = SuperCity::join('state', 'state.id', 'super_cities.state_id')
				->select([
					'super_cities.id',
					'super_cities.name',
					'state.name AS state_name',
				])->orderBy('super_cities.id', 'desc')->where('user_id', Auth::user()->id);

			if ($request->state_id > 0) {
				$data->where('state.id', $request->state_id);
			}

			return DataTables::of($data->get())
				->editColumn('select_checkbox', function ($row) {
					$abc = '<div class="form-check checkbox checkbox-primary mb-0">
				<input class="form-check-input table_checkbox" data-id="' . $row->id . '" name="select_row[]" id="checkbox-primary-' . $row->id . '" type="checkbox">
				<label class="form-check-label" for="checkbox-primary-' . $row->id . '"></label>
				  </div>';
					return $abc;
				})
				->editColumn('state_id', function ($row) {
					if (!empty($row->state_name)) {
						return $row->state_name;
					}
					return '';
				})
				->editColumn('Actions', function ($row) {
					$buttons = '';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Edit" onclick=getCity(this) class="fa-pencil pointer fa fs-22 py-2 mx-2  " type="button"></i>';
					$buttons =  $buttons . '<i role="button" data-id="' . $row->id . '" title="Delete" onclick=deleteCity(this) class="fa-trash pointer fa fs-22 py-2 mx-2 text-danger" type="button"></i>';
					return $buttons;
				})
				->rawColumns(['Actions', 'select_checkbox'])
				->make(true);
		}

		$states = State::orderBy('name')->where('user_id', Auth::user()->id)->get();

		return view('superadmin.supersettings.super_city_index', compact('states'));
	}

	public function get_city(Request $request)
	{
		if (!empty($request->id)) {
			$data =  SuperCity::where('id', $request->id)->first()->toArray();
			return json_encode($data);
		}
	}

	public function cities_destroy(Request $request)
	{
		if (!empty($request->id)) {
			$data = SuperCity::where('id', $request->id)->delete();
		}
		if (!empty($request->allids) && isset(json_decode($request->allids)[0])) {
			$data = SuperCity::whereIn('id', json_decode($request->allids))->delete();
		}
	}

	public function cities_store(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = SuperCity::find($request->id);
			if (empty($data)) {
				$data =  new SuperCity();
			}
		} else {
			$data =  new SuperCity();
		}
		$data->name = $request->name;
		$data->state_id = $request->state_id;
		$data->save();
	}

	public function area_index(Request $request)
	{
		if ($request->ajax()) {

			$data = SuperAreas::join('super_cities', 'super_cities.id', 'super_areas.super_city_id')
				->select([
					'super_areas.id',
					'super_areas.pincode',
					'super_areas.name',
					'state.name AS state_name',
					'super_cities.name AS city_name',
				])
				->join('state', 'state.id', 'super_areas.state_id')
				->orderBy('super_areas.id', 'desc')->where('user_id', Auth::user()->id);

			if ($request->state_id > 0) {
				$data->where('state.id', $request->state_id);
			}

			if ($request->city_id > 0) {
				$data->where('super_cities.id', $request->city_id);
			}

			return DataTables::of($data->get())
				->editColumn('select_checkbox', function ($row) {
					$abc = '<div class="form-check checkbox checkbox-primary mb-0">
				<input class="form-check-input table_checkbox" data-id="' . $row->id . '" name="select_row[]" id="checkbox-primary-' . $row->id . '" type="checkbox">
				<label class="form-check-label" for="checkbox-primary-' . $row->id . '"></label>
				  </div>';
					return $abc;
				})
				->editColumn('city', function ($row) {
					if (isset($row->city_name)) {
						return $row->city_name;
					}
					return '';
				})
				->editColumn('state', function ($row) {
					if (isset($row->state_name)) {
						return $row->state_name;
					}
					return '';
				})
				->editColumn('Actions', function ($row) {
					$buttons = '';
					$buttons =  $buttons . '<i role="button" title="Edit" data-id="' . $row->id . '" onclick=getArea(this) class="fa-pencil pointer fa fs-22 py-2 mx-2  " type="button"></i>';

					$buttons =  $buttons . '<i role="button" title="Delete" data-id="' . $row->id . '" onclick=deleteArea(this) class="fa-trash pointer fa fs-22 py-2 mx-2 text-danger" type="button"></i>';
					return $buttons;
				})
				->rawColumns(['Actions', 'select_checkbox'])
				->make(true);
		}
		$cities = SuperCity::orderBy('name')->get()->toArray();

		$states = State::with(['cities'])->where('user_id', Auth::user()->id)->get();

		return view('superadmin.supersettings.super_area_index', compact('cities', 'states'));
	}

	public function area_store(Request $request)
	{
		if (!empty($request->id) && $request->id != '') {
			$data = SuperAreas::find($request->id);
			if (empty($data)) {
				$exist = SuperAreas::where('name', $request->name)->where('super_city_id', $request->super_city_id)->first();
				if (!empty($exist)) {
					return;
				}
				$data =  new SuperAreas();
			}
		} else {
			$exist = SuperAreas::where('name', $request->name)->where('super_city_id', $request->super_city_id)->first();
			if (!empty($exist)) {
				return;
			}
			$data =  new SuperAreas();
		}
		$data->name = $request->name;
		$data->super_city_id = $request->super_city_id;
		$data->pincode = $request->pincode;
		$data->state_id = $request->state_id;
		$data->save();
	}



	public function get_area(Request $request)
	{
		if (!empty($request->id)) {
			$data = SuperAreas::where('id', $request->id)->first()->toArray();
			return json_encode($data);
		}
	}

	public function area_delete(Request $request)
	{
		if (!empty($request->id)) {
			$data = SuperAreas::where('id', $request->id)->delete();
		}
		if (!empty($request->allids) && isset(json_decode($request->allids)[0])) {
			$data = SuperAreas::whereIn('id', json_decode($request->allids))->delete();
		}
	}
}
