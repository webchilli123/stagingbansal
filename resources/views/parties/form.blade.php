@csrf
<section class="row">
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Account Type</label>
        <select name="type" id="" class="form-control" required>
            @if(isset($party))
                @foreach($types as $key => $type)
                <option value="{{ $key }}" {{ $party->type == $key ? 'selected': '' }}>{{ $type }}</option>
                @endforeach
            @else
                <option selected value="">Choose...</option>
                @foreach($types as $key => $type)
                <option value="{{ $key }}" {{ old('type') == $key ? 'selected': '' }}>{{ $type }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $party->name ?? old('name') }}" required>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Opening Balance</label>
        <div class="input-group">
            <input type="number" class="form-control bg-white" name="opening_balance" 
            value="{{ $party->opening_balance ?? old('opening_balance') }}" {{ $mode == 'create' ? 'required' : 'disabled' }}>
            <div style="min-width: 7rem;">
                <select name="drcr" class="form-control" {{ $mode == 'create' ? 'required' : 'disabled' }}>
                    <option value="" selected>Choose...</option>
                    <option value="DR">DR</option>
                    <option value="CR">CR</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">City</label>
        <select name="city_id" id="city" class="form-control">
            @if(isset($party))
                @foreach($cities as $id => $city)
                <option value="{{ $id }}" {{ $party->city_id == $id ? 'selected' : '' }}>
                    {{ $city }}
                </option>
                @endforeach
            @else
                <option selected value="">Choose...</option>
                @foreach($cities as $id => $city)
                <option {{ old('city_id') == $id ? 'selected' : '' }} value="{{ $id }}">
                    {{ $city }}
                </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Address</label>
        <input type="text" class="form-control" name="address" value="{{ $party->address ?? old('address') }}">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Phone No.</label>
        <input type="tel" class="form-control" name="phone" value="{{ $party->phone ?? old('phone') }}">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Mobile No.</label>
        <input type="tel" class="form-control" name="mobile" value="{{ $party->mobile ?? old('mobile') }}">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Email</label>
        <input type="text" class="form-control" name="email" value="{{ $party->email ?? old('email') }}">
    </div>

    <div class="col-lg-3 mb-3">
        <label for="" class="form-label">Fax</label>
        <input type="text" class="form-control" name="fax" value="{{ $party->fax ?? old('fax') }}">
    </div>
    
    <div class="col-lg-3 mb-3">
        <label for="" class="form-label">TIN No.</label>
        <input type="text" class="form-control" name="tin_number" 
        value="{{ $party->tin_number ?? old('tin_number') }}">
    </div>


    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Category</label>
        <select name="category_id" id="category" class="form-control" required>
            @if(isset($party))
                @foreach($categories as $id => $category)
                <option value="{{ $id }}" {{ $party->category_id == $id ? 'selected' : '' }} >
                    {{ $category }}
                </option>
                @endforeach
            @else
                <option selected value="">Choose...</option>
                @foreach($categories as $id => $category)
                   <option {{ old('category_id') == $id ? 'selected' : '' }} value="{{ $id }}">
                    {{ $category }}
                   </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Website (URL)</label>
        <input type="text" class="form-control" name="url" value="{{ $party->url ?? old('url') }}">
    </div>

    
    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">User</label>
        <select name="user_id" id="user_id" class="form-control" required>
            <option selected value="">Choose...</option>
            @if(isset($party))
                @foreach($users as $id => $user)
                <option value="{{ $id }}" {{ $party->user_id == $id ? 'selected' : '' }} >
                    {{ $user }}
                </option>
                @endforeach
            @else
                @foreach($users as $id => $user)
                   <option {{ old('user_id') == $id ? 'selected' : '' }} value="{{ $id }}">
                    {{ $user }}
                   </option>
                @endforeach
            @endif
        </select>
    </div>

</section>

<div class="mb-3">
    <label for="" class="form-label">Note</label>
    <textarea name="note" id="note" cols="30" rows="5" 
    class="form-control">{{ $party->note ?? old('note') }}</textarea>
</div>

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>

@push('scripts')

<script>
    $(document).ready(()=>{
      $('select').selectize();
   });
</script>

@endpush