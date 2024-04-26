@csrf
<div class="row">

    <div class="mb-3 col-lg-6">
        <label for="" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $employee->name ?? old('name') }}">
    </div>

    <div class="mb-3 col-lg-6">
        <label for="" class="form-label">Contact No.</label>
        <input type="tel" class="form-control" name="contact_number" value="{{ $employee->contact_number ?? old('contact_number') }}">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Designation</label>
        <select name="designation_id" id="designation_id" class="form-control" required>
            @if(isset($employee))
            @foreach($designations as $key => $designation)
            <option value="{{ $key }}" {{ $employee->designation_id == $key ? 'selected' : '' }}>
                {{ $designation }}
            </option>
            @endforeach
            @else
            <option selected value="">Choose...</option>
            @foreach($designations as $key => $designation)
            <option {{ old('designation_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $designation}}</option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Satff Type</label>
        <select name="staff_type_id" id="staff_type_id" class="form-control" required>
            @if(isset($employee))
            @foreach($staff_types as $key => $type)
            <option value="{{ $key }}" {{ $employee->staff_type_id == $key ? 'selected': '' }}>{{ $type }}</option>
            @endforeach
            @else
            <option selected value="">Choose...</option>
            @foreach($staff_types as $key => $type)
            <option {{ old('staf_type_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $type }}</option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="mb-3 col-lg-6">
        <label for="" class="form-label">Rate</label>
        <input type="number" class="form-control" name="rate" value="{{ $employee->rate ?? old('rate') }}">
    </div>

    <div class="mb-3 col-lg-6">
        <label for="" class="form-label">Address</label>
        <input type="text" class="form-control" name="address" value="{{ $employee->address ?? old('address') }}">
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