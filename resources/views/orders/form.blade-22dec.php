@csrf
<section class="row">
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Order No.</label>
        <div class="form-control">{{ isset($order) ? $order->order_number : $order_number }}</div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Order Type</label>
        <select name="type" id="" class="form-control" required>
            <option selected value="">Choose...</option>
        @if (isset($order))
            @foreach($types as $type)
            <option value="{{ $type }}" {{ $order->type == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
            @endforeach
        @else
            @foreach($types as $type)
               <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        @endif
        </select>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <label for="" class="form-label">Order Date</label>
        <input type="date" class="form-control" name="order_date"
         value="{{ isset($order) ? $order->order_date->format('Y-m-d') : date('Y-m-d') }}" required>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <label for="" class="form-label">Due Date</label>
        <input type="date" class="form-control" name="due_date" 
        value="{{ isset($order) ? $order->due_date->format('Y-m-d') : date('Y-m-d') }}" required>
    </div>

    <div class="col-md-12 col-lg-6 mb-3">
        <label for="" class="form-label">Party</label>
        <div class="input-group">
            <span class="input-group-text">DR | CR </span>
            <select name="party_id" id="party" class="form-control" required>  
                @if (isset($order))
                    @foreach($parties as $id => $party)
                        <option {{ $order->party_id == $id ? 'selected' : '' }} value="{{ $id }}">
                            {{ $party }}
                        </option>
                    @endforeach
                @else
                    <option selected value="">Choose...</option>
                    @foreach($parties as $id => $party)
                        <option {{ old('party_id') == $id ? 'selected' : '' }} value="{{ $id }}">
                            {{ $party }}
                        </option>
                    @endforeach
                @endif
            </select>    
        </div>
    </div>
</section>
{{-- order items --}}
@include('orders.order-items')

<footer class="d-flex justify-content-between mt-3 mt-lg-0 mb-4">
    <button class="btn btn-primary" id="add-row">
        <span class="fa fa-plus"></span> 
    </button>  
    <button class="btn btn-danger" id="remove-row">
        <i class="fa fa-times"></i> 
    </button>
   
</footer>
    
<div class="mb-3">
    <label for="" class="form-label">Narration</label>
    <textarea name="narration" id="narration" cols="30" rows="5" class="form-control">{{ $order->narration ?? old('narration') }}</textarea>
</div>
<div class="mb-3">
    <label for="" class="form-label">whatsApp Narration</label>
    <textarea name="wa_narration" id="wa_narration" cols="30" rows="5" class="form-control">{{ $order->wa_narration ?? old('wa_narration') }}</textarea>
</div>

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>


@push('scripts')

<script>
    function enableSelectize(){
       $('table#order tbody').find('select').selectize({ sortField: 'text' });
    }  
  
  $(document).ready(()=>{
   
      $('select').selectize();

      $('table#order tbody').on('input',`input[name='quantities[]']`,function(){

        let rateEl = $(this).parent().parent().find(`input[name='rates[]']`);

        rateEl.val() ?  rateEl.val() : rateEl.val(0);

        let total = $(this).val() * rateEl.val();

        $(this).parent().parent().find(`input[name='total_prices[]']`).val(total.toFixed(2));

        });


        $('table#order tbody').on('input',`input[name='rates[]']`,function(){

        let quantityEl = $(this).parent().parent().find(`input[name='quantities[]']`);

        quantityEl.val() ?  quantityEl.val() : quantityEl.val(0);

        let total = $(this).val() * quantityEl.val();

        $(this).parent().parent().find(`input[name='total_prices[]']`).val(total.toFixed(2));

        });

      $('#add-row').click(function(e){
            
         e.preventDefault();

        $('table#order tbody tr:last').find('select').each(function (el) {
            let value = $(this).val();
            $(this)[0].selectize.destroy();
            $(this).val(value);
        });

        $('table#order tbody tr:last').clone()
        .appendTo('table#order tbody')
        .find('[name]').val('');
        var remove_received = "{{!empty($order) ? '1' : '0' }}"; 
        if(remove_received == '1'){
            $('table#order tbody tr:last').find('td').eq(1).html('');
            $('table#order tbody tr:last').find('td').eq(2).find('input').attr('min',0);
        }
         enableSelectize();
    }); 


     // remove last row
     $('#remove-row').on('click', (e)=>{
        e.preventDefault(); 
        if($('table#order tbody tr:last').find('td').eq(2).find('input').attr('min') == 0){ 
            if( $('.existitem').length > 1){
                $('table#order tbody tr:last').remove();  
            }else{
                alert('Atleast one item is required');
            }
        }else{
            alert('You cannot remove item in process');
        } 
    });
    $(document).on('click','.remove-row-selected', function(e){
        e.preventDefault(); 
        if($(this).parent().parent().find('td').eq(2).find('input').attr('min') == 0){ 
            if( $('.existitem').length > 1){  
                $(this).parent().parent().parent().append('<input type="hidden" name="deleted_items[]" value="'+$(this).data('id')+'">');
                $(this).parent().parent().remove();
            }else{
                alert('Atleast one item is required');
            }
        }else{
            alert('You cannot remove item in process');
        } 
    });
    $(document).on('change','.checkmin', function(e){ 
        if(parseInt($(this).attr('min')) > parseInt($(this).val())){
            alert('You have to select atleast '+$(this).attr('min'));
            $(this).val('');
        } 
    });


  });

  
</script>

@endpush