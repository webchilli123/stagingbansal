@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Account Voucher <span class="bg-primary text-white px-1 rounded h6 ms-1">Multiple</span></h5>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('multiple.account-vouchers.store') }}" method="POST"  
   onsubmit="return confirm('Are you sure?');">
    @csrf
    
    <div class="row">
        <div class="col-lg-6 mb-3">
            <label for="" class="form-label">Voucher Date</label>
            <input type="date" class="form-control" name="transaction_date" value="{{ old('transaction_date') }}" required>
        </div>

        <div class="col-lg-4 mb-3">
            <label for="" class="form-label">Account Name</label>
            <select name="account_id" id="account_id" class="form-control" required>
                <option selected value="">Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('account_id') == $key ? 'selected' : '' }} value="{{ $key }}">
                    {{ $party }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-lg-2">
            <label for="" class="form-label">DR/CR</label>
            <select name="drcr" class="form-control">
                <option value="" selected>Choose...</option>
                <option value="DR">DR</option>
                <option value="CR">CR</option>
            </select>
        </div>

    </div>


    <section class="table-responsive-lg rounded mt-lg-3">
        <table class="table table-bordered" id="party" style="min-width: 50rem;">
            <thead>
                <tr>
                    <th>Party Name</th>
                    <th>Amount</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>       
                <tr>
                    <td style="min-width: 18rem;">
                        <select name="parties[]" id="section" class="form-control" required>
                            <option value="" selected>Choose...</option>
                            @foreach ($parties as $key => $party)
                            <option value="{{ $key }}">{{ $party }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="amounts[]" step=".001" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="narrations[]" class="form-control">
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

        
    <footer class="d-flex justify-content-between mt-3 mt-lg-0 mb-4">
        <button class="btn btn-primary" id="add-row">
            <span class="fa fa-plus"></span> 
        </button>
        <button class="btn btn-danger" id="remove-row">
            <i class="fa fa-times"></i> 
        </button>
    </footer>

   <button type="submit" class="btn btn-primary mb-5">Save</button>

</form>
@endsection


@push('scripts')

<script>
    function enableSelectize(){
       $('table#party tbody').find('select').selectize({ sortField: 'text' });
    }  
  
  $(document).ready(()=>{
   
      $('select').selectize();

      $('#add-row').click(function(e){
            
         e.preventDefault();

        $('table#party tbody tr:last').find('select').each(function (el) {
            let value = $(this).val();
            $(this)[0].selectize.destroy();
            $(this).val(value);
        });

        $('table#party tbody tr:last').clone().appendTo('table#party tbody').find('[name]').val('');
            
         enableSelectize();
    }); 


     // remove last row
     $('#remove-row').on('click', (e)=>{
        e.preventDefault();
        $('table#party tbody tr:last').remove();
    });


  });

  
</script>

@endpush