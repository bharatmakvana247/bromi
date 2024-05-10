<?php

namespace App\Http\Controllers\Superadmin;

use App\Constants\Statuses;
use App\Http\Controllers\Controller;
use App\Models\BromiEnquiry;
use App\Models\Subplans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BromiEnquiryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $data = BromiEnquiry::where('user_id', $user->id)->get();
            return DataTables::of($data)
            ->editColumn('created_at', function ($row) {
                if (!empty($row->created_at)) {
                    return date('d M, Y', strtotime($row->created_at));
                }
            })
            ->editColumn('status', function ($row) {
                if (!empty($row->status)) {
                    if ($row->status == 'pending') {
                        return '<span class="bg-warning rounded-pill px-2 py-1">' . $row->status . '</span>';
                    } else {
                        return '<span class="bg-success rounded-pill px-2 py-1">' . $row->status . '</span>';
                    }
                }
            })

            ->editColumn('Actions', function ($row) {
                $buttons = '';
                $buttons =  $buttons . '<i data-id="' . $row->id . '" onclick=getBromiEnq(this) class="fs-22 py-2 mx-2 fa-pencil pointer fa" type="button"></i>';
                return $buttons;
            })
            ->rawColumns(['status', 'Actions'])
            ->make(true);
        }
        return view('superadmin.requests.index');
    }

    public function superadminList(Request $request)
    {
        if ($request->ajax()) {
            $data = BromiEnquiry::get();
            return DataTables::of($data)
                ->editColumn('enquiry', function ($row) {
                    if (!empty($row->enquiry)) {
                        return substr($row->enquiry, 0, 100) . '...';
                    }
                })
                ->editColumn('email', function ($row) {
                    if (!empty($row->email)) {
                        return '<span style="text-transform:none !important">'.$row->email.'</span>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    if (!empty($row->created_at)) {
                        return date('d/m/Y', strtotime($row->created_at));
                    }
                })
                ->editColumn('status', function ($row) {
                    if (!empty($row->status)) {
                        if ($row->status == Statuses::PENDING) {
                            return '<span style="font-size:13px;" class="bg-danger rounded-pill px-2 py-1">'. $row->status .'</span>';
                        } else if ($row->status == Statuses::READ) {
                            return '<spa style="font-size:13px;"n class="bg-warning rounded-pill px-2 py-1">'. $row->status .'</span>';
                        } else if ($row->status == Statuses::IN_PROGRESS) {
                            return '<span style="font-size:13px;" class="bg-success rounded-pill px-2 py-1">'. $row->status .'</span>';
                        } else if ($row->status == Statuses::CLOSED) {
                            return '<span style="font-size:13px;" class="bg-secondary rounded-pill px-2 py-1">'. $row->status .'</span>';
                        }
                    }
                })

                ->editColumn('Actions', function ($row) {
                    $buttons = '';
                    $buttons =  $buttons . '<span data-id="' . $row->id . '" onclick=getBromiEnq(this) style="cursor:pointer"><i class="fa fa-pencil fs-5"></i></span>';
                    $buttons =  $buttons . '<span class="ms-3" data-id="' . $row->id . '" onclick=updateStatusForm(this) style="cursor:pointer"><i class="fa fa-bars fs-5 text-warning"></i></span>';
                    return $buttons;
                })
                ->rawColumns(['enquiry', 'status', 'Actions','email'])
                ->make(true);
        }

        return view('superadmin.requests.super-admin-index')->with([
            'plans' => Subplans::all(),
        ]);
    }

    /**
     * change enquiry status.
     */
    public function saveProgress(Request $request)
    {
        $bromi_enquiry = BromiEnquiry::find($request->id);

        $dateTime = $request->followup_date . ' ' . $request->time . ':00';

        $bromi_enquiry->fill([
            'status' => $request->status,
            'followup_date' => Carbon::parse($dateTime)->format('Y-m-d H:i:s'),
            'enquiry' => $request->enquiry,
        ])->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bromi_enquiry = BromiEnquiry::find($request->id) ?? new BromiEnquiry();

        $bromi_enquiry->fill([
            'user_id' => Auth::user()->id,
            'super_admin_id' => Auth::user()->parent_id,
            'user_name' => $request->user_name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'company' => $request->company,
            'lead_type' => $request->lead_type,
            'email' => $request->email,
            'plan_interested_in' => $request->plan_interested_in,
            'enquiry' => $request->enquiry,
        ])->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BromiEnquiry  $bromiEnquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!empty($request->id)) {

            $bromEnq = BromiEnquiry::where('id', $request->id)->first()->toArray();
            $data = [
                'brom_enq' => $bromEnq,
            ];

            return response()->json($data);
        }
    }
}
