@csrf
<section class="row mb-4">
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Voucher No.</label>
        <div class="form-control">{{ $voucher_number }}</div>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Voucher Date</label>
        <input type="date" name="voucher_date" value="{{ date('Y-m-d') }}" class="form-control" required>
    </div>

    
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Month</label>
        <input type="month" name="month" value="{{ request('month') ?? date('Y-m') }}" class="form-control" required>
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Employee</label>
        <select name="employee_id" id="" class="form-control" required>
            @if(isset($attendance))
            @foreach($employees as $key => $employee)
            <option value="{{ $key }}" {{ $attendance->employee_id == $key ? 'selected' : '' }}>
                {{ $employee }}
            </option>
            @endforeach
            @else
            <option selected value="">Choose...</option>
            @foreach($employees as $key => $employee)
            <option {{ request('employee_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $employee }}</option>
            @endforeach
            @endif
        </select>
    </div>

    @isset($person)
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Designation</label>
        <div class="form-control">{{ $person->designation->name }}</div>
    </div>
    <div class="col-lg-3 mb-3">
        <label for="" class="form-label">Staff Type</label>
        <div class="form-control">{{ $person->staffType->name }}</div>
    </div>
    <div class="col-lg-3 mb-3">
        <label for="" class="form-label">Rate</label>
        <div class="form-control">{{ $person->rate }}</div>
    </div>
    @endisset


</section>

@isset($attendances)
<section class="table-responsive mb-2">
    <table class="table table-bordered" style="min-width: 60rem;" id="attendances">
        <thead>
            <tr>
                <th>Attendace Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total Time (Hours)</th>
                <th>
                    <input type="checkbox" class="form-check-input" id="select-all">
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->attendance_date->format('d M, Y') }}</td>
                    <td>{{ $attendance->start_time->format('H:i A') }}</td>
                    <td>{{ $attendance->end_time->format('H:i A') }}</td>
                    {{-- total duration --}}
                    <td>{{ $attendance->end_time->diff($attendance->start_time)->format('%H:%I') }}</td>
                    <td>
                       <input type="checkbox" class="form-check-input" name="attendances_id[]" value="{{ $attendance->id }}">
                    </td>
                </tr>
                @if ($loop->last)
                    <tr>
                        <td colspan="2"></td>
                        <td class="fw-bold">Total Time</td>
                        <td>{{ round($total_time, 2) }}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</section>


<div class="mb-3">
    <label for="" class="form-label">Amount</label>
    <input type="number" name="amount" value="{{ old('amount') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label for="" class="form-label">Description</label>
    <textarea name="description" id="description" maxlength="250" cols="30" rows="5"
        class="form-control">{{ old('descriptin') }}</textarea>
</div>

@endisset


<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>



@push('scripts')

<script>
    
$(document).ready(()=>{

    const url  = `{{ route('salary-vouchers.create') }}`;   

    $('select').selectize();

    $('select[name=employee_id]').on('change', function(){
            
      window.location = `${url}?month=${$('input[name=month]').val()}&employee_id=${$(this).val()}`;

    }); 


    $('input#select-all').click(function(){
        
        if($(this).is(':checked')){
            $('table#attendances tbody input:checkbox').prop('checked', true);
 
        }else{
            $('table#attendances tbody input:checkbox').prop('checked', false);
        }

  });


});
</script>

@endpush