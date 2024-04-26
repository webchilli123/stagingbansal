@csrf
<section class="row">

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" value="{{ $user->username ?? old('username') }}" required>
    </div>

	<div class="col-md-6 mb-3">
        <label for="" class="form-label">Role</label>
        <select name="role_id" id="" class="form-control" required>
            @if(isset($user))
            @foreach($roles as $id => $role)
            <option value="{{ $id }}" {{ $user->role_id == $id ? 'selected': '' }}>{{ $role }}</option>
            @endforeach
            @else
            <option selected value="">Choose...</option>
            @foreach($roles as $id => $role)
            <option value="{{ $id }}">{{ $role }}</option>
            @endforeach
            @endif
        </select>
    </div>

	<div class="col-md-6 mb-3">
		<label for="password" class="form-label">Password @if($mode == 'edit') (Optional) @endif </label>
		<input type="password" name="password" id="password" class="form-control" autocomplete="off">
	</div>
	
	<div class="col-md-6 mb-3">
		<label for="password_confirmation" class="form-label">Confirm Password</label>
		<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
		autocomplete="off">
	</div>


</section>
<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>

@push('scripts')

<script>
    $(document).ready(()=>{
      $('select').selectize();
   });
</script>

@endpush