@csrf
<section class="row">
    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Voucher Date</label>
        <input type="date" class="form-control" name="transaction_date" 
        value="{{ $transaction->transaction_date ?? old('transaction_date') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">To Party</label>
        <div class="input-group">
            <span class="input-group-text">DR</span>
            <select name="dr_party" id="to_party" class="form-control" required>
                @if(isset($transaction))
                @foreach($parties as $key => $party)
                <option value="{{ $key }}" {{ $debitor_id == $key ? 'selected' : '' }}>
                    {{ $party }}
                </option>
                @endforeach
                @else
                <option selected value="">Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('dr_party') == $key ? 'selected' : '' }} value="{{ $key}}">
                    {{ $party}}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">By Party</label>
        <div class="input-group">
            <span class="input-group-text">CR</span>
            <select name="cr_party" id="by_party" class="form-control" required>
                @if(isset($transaction))
                @foreach($parties as $key => $party)
                <option value="{{ $key}}" {{ $creditor_id == $key ? 'selected' : '' }}>
                    {{ $party }}
                </option>
                @endforeach
                @else
                <option selected value="">Choose...</option>
                @foreach($parties as $key => $party)
                <option {{ old('cr_party') == $key? 'selected' : '' }} value="{{ $key }}">
                    {{ $party }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="" class="form-label">Item</label>
        <select name="item_id" id="item_id" class="form-control mb-1" required>
            <option selected value="">Choose...</option>
            @foreach($items as $id => $item)
                <option value="{{ $id }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
   

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Amount (Quantity)</label>
        <input type="number" step="0.001" class="form-control" name="amount" value="{{  $amount ?? old('amount') }}" required>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="" class="form-label">Rate</label>
        <input type="number" step="0.001" class="form-control" name="rate" value="{{  $rate ?? old('rate') }}" required>
    </div>

</section>

<div class="mb-3">
    <label for="" class="form-label">Narration</label>
    <textarea name="narration" id="narration" cols="30" rows="5"
        class="form-control">{{ $transaction->narration ?? old('narration') }}</textarea>
</div>

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Edit' }}</button>

@push('scripts')

<script>
    $(document).ready(()=>{
       
       $('select').selectize();

       let selfStore = '{{ App\Models\Party::SELF_STORE }}';
  
       // check stock on sale
       $('[name=amount]').on('change', function(){
                    
            let quantity = $(this).val();
            let byParty = $('#by_party').val();
            let item = $('#item_id').val();

            if(byParty == selfStore && quantity.length > 0){

                if(byParty.length == 0 || item.length == 0 ){
                    $(this).val('');
                    return alert('Select Party & Item.');
                }
               
                $.ajax({
                    url : `{{ route('stock.check') }}`,
                    dataType : 'json',
                    data : { item_id : item, current_quantity :quantity }
                }).done((data)=>{
                    if(parseFloat(data.total_quantity) < parseFloat(quantity)){
                        alert(`Stock of ${ data.item.name } is ${ data.total_quantity} in Self Store.`);
                        $(this).val('');
                        return;
                    }

                }).fail((error)=> console.log(error));

            }
        });

   });
</script>

@endpush