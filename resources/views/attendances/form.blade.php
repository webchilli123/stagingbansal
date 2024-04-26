@csrf
<div class="row">
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
            <option {{ old('employee_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $employee }}</option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="mb-3 col-md-6">
        <label for="" class="form-label">Attendance Date</label>
        <input type="date" class="form-control" name="attendance_date" 
        value="{{  isset($attendance) ? $attendance->attendance_date->format('Y-m-d') : date('Y-m-d') }}">
    </div>
    <div class="mb-3 col-md-6">
        <label for="" class="form-label">Start Time</label>
        <input type="time" class="form-control" name="start_time" 
        value="{{ isset($attendance) ? $attendance->start_time->format('H:i') : old('start_time') }}">
    </div>
    <div class="mb-3 col-md-6">
        <label for="" class="form-label">End Time</label>
        <input type="time" class="form-control" name="end_time" 
        value="{{  isset($attendance) ? $attendance->end_time->format('H:i')  : old('end_time') }}" >
    </div>
    
</div>
    
<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>

@push('scripts')

<script>
    $(document).ready(()=>{
       $('select').selectize();
   });
</script>

@endpush