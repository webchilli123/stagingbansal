<style>
    /* Hide the dropdown arrow */
.select-single {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
    background-repeat: no-repeat;
    background-position: right center;
    background-size: 24px;
}

/* Remove the scrollbar */
.select-single::-webkit-scrollbar {
    display: none;
}

/* Style the select options */
.select-single option {
    background-color: #fff;
    color: #000;
}

    
</style>
@csrf
<section class="row">

<div class="col-md-12 col-lg-6 mb-3">
    <label for="party" class="form-label">Party</label>
    <div class="input-group">
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
<div class="col-md-12 col-lg-6 mb-3">
    <label for="orders" class="form-label">Orders</label>
    <div class="input-group">
        <select name="order_id[]" id="orders" class="form-control select-single" required>
            <option value="" disabled selected>-- Select Order --</option>
            <!-- Single option to show initially -->
        </select>
    </div>
</div>


    
    
</section>
{{-- order items --}}
@include('orders.bill-order-items')

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

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Update' }}</button>


@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

  $(document).ready(function () {
        // Event delegation to handle change on dynamically added rows
        $(document).on('change', 'select.section', function () {
            // Reset previous error messages
            // $(this).closest('.error-container').find('.error-message').remove();

            // $('.error-message').remove();

            // Get all selected values
            var selectedValues = $('select.section').map(function () {
                return $(this).val();
            }).get();

            // Check for duplicate values
            var hasDuplicates = new Set(selectedValues).size !== selectedValues.length;

            if (hasDuplicates) {
                alert("Duplicate values are not allowed.");

                // $(this).closest('.error-container').append('<div class="error-message">Duplicate values are not allowed.</div>');
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        // Handle change event on party select
        $('#party').change(function(){
            $(this).removeClass('select-single');
            var partyId = $(this).val();
            if(partyId !== ''){
                // Fetch orders for the selected party via AJAX
                $.ajax({
                    url: '{{ route('get.order') }}',
                    type: 'GET',
                    data: { partyId: partyId }, // Send partyId as data
                    success: function(response){
                        $('#orders').find('option').not(':first').remove();

    // Append options for each order
    $.each(response.orders, function (key, value) {
        $("#orders").append('<option value="' + value.id + '">' + value.order_number + '</option>');
    });
                    },
                    error: function(xhr){
                        console.log(xhr.responseText);
                    }
                });
            } else {
                // Reset orders select when no party is selected
                $('#orders').empty().prop('disabled', true);
            }
        });
    });

    $(document).ready(function(){
    // Function to fetch item details based on selected order number
    function fetchItemDetails(orderNumber) {
        $.ajax({
            url: '{{ route('fetch.item.details') }}',
            type: 'GET',
            data: { orderNumber: orderNumber },
            success: function(response){
                // Check if response contains items
                if(response.items.length > 0) {
                        updateTableWithItemDetails(response.items);
                    } else {
                        // Display a message if no items found
                        $('#order tbody').html('<tr><td colspan="5">No items found for this order.</td></tr>');
                    }
                },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    }

    // Event listener for order selection
    $(document).on('change', '#orders', function(){
        var selectedOrderNumber = $(this).val();
        if(selectedOrderNumber) {
            fetchItemDetails(selectedOrderNumber);
        }
    });
});

function updateTableWithItemDetails(items) {
    var tableBody = $('#order tbody');
    tableBody.empty(); // Clear existing table rows

    // Loop through each item and add a row to the table
    $.each(items, function(index, item) {
        var row = '<tr>' +
                      '<td>' + item.name + '</td>' +
                      '<td><input type="number" class="form-control received-quantity" value="' + item.received_quantity + '"></td>' +
                      '<td>' + item.total_quantity + '</td>' +
                      '<td>' + item.rate + '</td>' +
                      '<td>' + item.total_price + '</td>' +
                  '</tr>';
        tableBody.append(row);
    });
}
</script>



@endpush
