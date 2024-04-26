@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
    <h5>Salary Voucher</h5>
    <a href="{{ route('salary-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<section class="table-responsive mb-4">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead> 
            <tr>
                <th>Salary Voucher No.</th>
                <th>Voucher Date</th>
                <th>Employee Name</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $voucher->voucher_number }}</td>
                <td>{{ $voucher->voucher_date->format('d M, Y') }}</td>
                <td>{{ $voucher->employee->name }}</td>
                <td>{{ $voucher->amount }}</td>
            </tr>
        </tbody>
    </table>
</section>



@if($voucher->attendances->count() > 0)
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Attendance Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($voucher->attendances as $attendance)
                <tr>
                    <td>{{ $attendance->attendance_date->format('d M, Y') }}</td>
                    <td>{{ $attendance->start_time->format('H:i A') }}</td>
                    <td>{{ $attendance->end_time->format('H:i A') }}</td>
                    <td>{{ $attendance->end_time->diff($attendance->start_time)->format('%H:%I') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endif

<h6 class="fw-bold mb-3">Description</h6>
<p class="p-3 border mb-4">{{ $voucher->description }}</p>
@endsection