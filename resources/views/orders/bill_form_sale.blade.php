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
<div id="container-placeholder" >

@csrf

<section class="row">

<div class="col-md-12 col-lg-6 mb-3">
    <label for="party" class="form-label">Party</label>
    <div class="input-group">
        <select name="party_id" id="party" class="form-control select-single" required>
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
    <label class="form-label">Orders</label>
    <select name="order_id[]" id="orders" class="form-control mb-1" multiple required>
        <!-- Options will be added dynamically via JavaScript -->
    </select>
</div>



    
    
</section>
{{-- order items --}}
@include('orders.bill-order-items')

    <!-- <footer class="d-flex justify-content-between mt-3 mt-lg-0 mb-4">
        <button class="btn btn-primary" id="add-row">
            <span class="fa fa-plus"></span>
        </button>
        <button class="btn btn-danger" id="remove-row">
            <i class="fa fa-times"></i>
        </button>

    </footer> -->

<div class="mb-3">
    <label for="" class="form-label">Narration</label>
    <textarea name="narration" id="narration" cols="30" rows="5" class="form-control">{{ $order->narration ?? old('narration') }}</textarea>
</div>
<!-- <div class="mb-3">
    <label for="" class="form-label">whatsApp Narration</label>
    <textarea name="whats_app_narration" id="wa_narration" cols="30" rows="5" class="form-control">{{ $order->wa_narration ?? old('wa_narration') }}</textarea>
</div> -->

<button type="submit" class="btn btn-primary mb-5">{{ $mode == 'create' ? 'Save' : 'Update' }}</button>

</div>
@push('scripts')
<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for party dropdown
        $('#party').select2({
            placeholder: "Select a party",
        });

        // Initialize Select2 for orders dropdown
       
    });
</script>
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
    // Initialize Select2
    $('#orders').select2({
        placeholder: "Select orders",
        multiple: true,
        templateResult: formatOrder,
        templateSelection: formatOrderSelection
    });

    // Handle change event on party select
    $('#party').change(function(){
        $(this).removeClass('select-single');
        var partyId = $(this).val();
        if(partyId !== ''){
            // Fetch orders for the selected party via AJAX
            $.ajax({
                url: '{{ route("get.order",["type" => "sale"]) }}',
                type: 'GET',
                data: { partyId: partyId }, // Send partyId as data
                success: function(response){
                    // Clear existing options
                    $('#orders').empty();
                    $('#order tbody').empty();
                    // Append options for each order
                    $.each(response.orders, function (key, value) {
                        $('#orders').append(
                            '<option value="' + value.id + '">' + value.order_number  + ' (' + value.order_date_formatted + ' )' + '</option>'
                        );
                    });

                    // Refresh Select2 to update options
                    $('#orders').select2({
                        placeholder: "Select orders",
                        multiple: true,
                        templateResult: formatOrder,
                        templateSelection: formatOrderSelection
                    });
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        } else {
            // Reset select when no party is selected
            $('#orders').empty();
        }
    });
});

// Function to format each option in Select2
function formatOrder (order) {
    if (!order.id) { return order.text; }
    var $order = $(
        '<span>' + order.text + '</span>'
    );
    return $order;
}

// Function to format the selected option in Select2
function formatOrderSelection (order) {
    return order.text;
}


    $(document).ready(function(){
    // Function to fetch item details based on selected order number
    function fetchItemDetails(orderNumber) {
    $.ajax({
        url: '{{ route('fetch.item.details') }}',
        type: 'GET',
        data: { orderNumber: orderNumber }, // Send single order number
        success: function(response){
        console.log(response);
        console.log(response);
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
        
        var selectedOrderNumbers = $(this).val();
    if(selectedOrderNumbers && selectedOrderNumbers.length > 0) {
        fetchItemDetails(selectedOrderNumbers);
    } else {
        // If no order is selected, clear the table
        $('#order tbody').empty();
    }
    });

    $(document).on('select2:unselecting', '#orders', function(e) {
    var removedOrderNumber = e.params.args.data.id;
    var selectedOrderNumbers = $(this).val();
    var index = selectedOrderNumbers.indexOf(removedOrderNumber);
    if (index !== -1) {
        selectedOrderNumbers.splice(index, 1);
    }
    if (selectedOrderNumbers && selectedOrderNumbers.length > 0) {
        fetchItemDetails(selectedOrderNumbers);
    } else {
        // If no order is selected, clear the table
        $('#order tbody').empty();
    }
});
});


function updateTableWithItemDetails(items) {
    var tableBody = $('#order tbody');
    console.log("Updating table with item details:", items);
    var existingRows = tableBody.find('tr');
    
    // Check if there are existing rows in the table
    if (existingRows.length > 0) {
        // Append new rows for the items to the existing table
        $.each(items, function(index, item) {
            var row = '<tr data-item-id="' + item.id + '" data-order-id="' + item.order_id + '">' +
            '<td>' + item.id + '</td>' + // Item ID
            '<td>' + item.order_id + '</td>' + // Order ID
            '<td>' + item.item.name + '</td>' +
            '<td><input type="number" class="form-control received-quantity" value=""></td>' +
            '<td>' + item.rate_formatted + '</td>' +
            '<td class="order-price"></td>' + // Leave order price cell empty initially
            '<td>' + item.ordered_quantity + '</td>' +
            '<td>' + item.total_price_formatted + '</td>' +
            '<td><i class="fas fa-trash-alt text-danger remove-item"></i></td>' +
            '</tr>';
            tableBody.append(row);
        });
    } else {
        // If no existing rows, replace the table with new rows
        var rows = ''; // String to hold HTML rows for all items
        $.each(items, function(index, item) {
            var row = '<tr data-item-id="' + item.id + '" data-order-id="' + item.order_id + '">' +
            '<td>' + item.id + '</td>' + // Item ID
            '<td>' + item.order_id + '</td>' + // Order ID
            '<td>' + item.item.name + '</td>' +
            '<td><input type="number" class="form-control received-quantity" value=""></td>' +
            '<td>' + item.rate_formatted + '</td>' +
            '<td class="order-price"></td>' + // Leave order price cell empty initially
            '<td>' + item.ordered_quantity + '</td>' +
            '<td>' + item.total_price_formatted + '</td>' +
            '<td><i class="fas fa-trash-alt text-danger remove-item"></i></td>' +
            '</tr>';
            rows += row; // Append current row to the rows string
        });
        tableBody.html(rows);
    }

    // Attach event listener to dynamically calculate order price
    tableBody.on('input', '.received-quantity', function() {
        var receivedQuantity = parseFloat($(this).val());
        var rate = parseFloat($(this).closest('tr').find('td:eq(4)').text()); // Assuming rate is always in the fifth column (index 4)
        var orderPrice = receivedQuantity * rate;
        $(this).closest('tr').find('.order-price').text(orderPrice.toFixed(2));
    });
}

$(document).on('click', '.remove-item', function(){
    $(this).closest('tr').remove(); // Remove the closest row when the remove button is clicked
});
</script>

<script>
    $(document).ready(function() {
        // Event listener for form submission
        $('#bill-submit').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Extract data from the form
            var formData = $(this).serialize();

            // Extract table data and append it to the form data
            var tableData = [];
            $('#order tbody tr').each(function(index) {
                var rowData = {};
                $(this).find('td').each(function() {
                    var fieldName = $(this).index();
                    var fieldValue = $(this).text();
                    // Check if the field is an input element
                    if ($(this).find('input').length > 0) {
                        fieldValue = $(this).find('input').val(); // Get input field value
                    }
                    rowData[fieldName] = fieldValue;
                });
                tableData.push(rowData);
            });

        formData += '&tableData=' + JSON.stringify(tableData);

            // Now you can send the data to the controller using AJAX
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    // Handle success response
                    $('#container-placeholder').html(response.html);
                    console.log(response.html);                    // Optionally, you can redirect the user to another page
                    // window.location.href = '/success-page';
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Error sending data:', error);
                }
            });
        });
    });
</script>


<script>
        $(document).ready(()=>{
        $('select').selectize({
            plugins: ["remove_button"],
        });
    });
    </script>

@endpush
