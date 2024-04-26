<?php

namespace App\Http\Controllers;

use App\Models\SalaryVoucher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\EmployeeAttendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class SalaryVoucherController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

           $vouchers = SalaryVoucher::with('employee')->select();
  
            return DataTables::of($vouchers)
                  ->editColumn('voucher_number', function ($voucher) {
                    return 'SA-'.$voucher->voucher_number;
                    })
                  ->editColumn('voucher_date', function ($voucher) {
                        return $voucher->voucher_date->format('d M, Y');
                    })
                  ->addColumn('action', function ($voucher) {
                      return view('salary-vouchers.buttons')->with(['voucher' => $voucher]);
                  })->make(true);
        }

        return view('salary-vouchers.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $voucher_number = SalaryVoucher::voucherNumber();
        $employees = Employee::orderBy('name')->pluck('name', 'id');
        $attendances = null;
        $person = null;
        $total_time = 0;
        
        if($request->filled('month') && $request->filled('employee_id')){
            
            // validate month format
            $validator = Validator::make($request->only('month'), [
                'month' => 'date_format:Y-m'
            ]);

            if($validator->fails()) {
                return redirect()->route('salary-vouchers.create')
                            ->withErrors($validator)
                            ->withInput();
            }
          
          $data = explode('-', $request->month);
          $year = $data[0];
          $month = $data[1];

            $query = EmployeeAttendance::query()
                ->whereNull('salary_voucher_id')
                ->where('employee_id', $request->employee_id)
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date',  $month);

            $attendances  = $query->get();

            if (count($attendances) == 0) {
                return redirect()->route('salary-vouchers.create')
                        ->withErrors(['message' => "No Unpaid Employee Attendaces Found."]);
            }
            
            $person = Employee::find($request->employee_id);
            $total_time = $query->sum(DB::raw('TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600'));

        }

        return view('salary-vouchers.create', compact('voucher_number', 'employees', 'attendances', 'person', 'total_time'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendances_id' => 'required|array',
            'attendances_id.*' => 'required|distinct',
            'voucher_date' => 'required|date',
            'amount' => 'required',
            'employee_id' => 'required',
            'description' => 'nullable',
        ]);


        DB::transaction(function() use($request){

           $voucher_number = SalaryVoucher::voucherNumber();
        
           $voucher = SalaryVoucher::create([
                'voucher_number' => $voucher_number,
                'voucher_date' => $request->voucher_date,
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'description' => $request->description
            ]);
                    
           EmployeeAttendance::whereIn('id', $request->attendances_id)
                ->update(['salary_voucher_id' => $voucher->id ]);

        });

        return  redirect()->route('salary-vouchers.index')->with('success', 'Salary Voucher Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryVoucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryVoucher $voucher)
    {
        $voucher->load(['attendances', 'employee']);
        return view('salary-vouchers.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalaryVoucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryVoucher $voucher)
    {
        return view('salary-vouchers.edit', [ 'item' => $voucher ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalaryVoucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryVoucher $voucher)
    {
        $request->validate(['name' => 'required|max:50']);
       
        $voucher->update([ 
            'name' => $request->name,
         ]);

        return  redirect()->route('salary-vouchers.index')->with('success', 'Salary Voucher Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryVoucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryVoucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Salary Voucher Deleted');
    }
}
