<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    i.fa.fa-folder.openDetail.greencolo {
        color: #d8c510;
    }

    i.fa.fa-folder.openDetail:hover {
        color: #d8c510;
    }
</style>
<header class="d-flex justify-content-between align-items-center mb-4">
    <h5> </h5>
    <div>
        <button class="btn btn-sm btn-success text-white me-1" onclick="return window.print();">Print</button>
    </div>
</header>

<section class="table-responsive mb-4">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Type</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Due Date</th>
                <th>Party Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ ucfirst($order->type) }}</td>
                <td><span class="badge alert-primary">{{ ucwords($order->status) }}</span></td>
                <td>{{ optional($order->order_date)->format('d M, Y') }}</td>
                <td>{{ optional($order->due_date)->format('d M, Y') }}</td>
                <td>{{ $order->party->name ?? '' }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</section>

<h6 class="mb-3 fw-bold">
    <i class="fa fa-circle text-success me-1"></i> Order Items
</h6>
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity (Ordered)</th>
                <th>Quantity Sent</th>
                <th>Rate</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $bill)
            <tr>
                <td>{{$bill->item_name}}</td>
                <td>{{$bill->total_quantity}}</td>
                <td>{{$bill->sent_quantity}}</td>
                <td>{{$bill->rate}}</td>
                <td>{{$bill->total_price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>



<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Narration
</h6>
<p class="p-3 border mb-4">{{$bills[0]->narration ?? ''}}</p>

<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Whatsaap Narration
</h6>
<p class="p-3 border mb-4">{{$bills[0]->whats_app_narration ?? ''}}</p>

@if($bills->count() > 0)
<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i>
    {{ $bills[0]->bill_type == 'sale' ? 'Sales' : 'Purchases' }}
</h6>
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered align-middle" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Transaction No.</th>
                <th>Type</th>
                <th>Item</th>
                <th>Quantity ({{ $bills['0']->bill_type == 'sale' ? 'Sent' : 'Received' }})</th>
                <th>Rate</th>
                <th>Total</th>
                <th>{{ $bills['0']->bill_type == 'sale' ? 'Sale' : 'Purchase' }} Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($bills as $index => $bill)
    <tr>
    <td>{!! $index === 0 ? '<input type="hidden" name="transaction_id" required value="">
                        <button data-transaction="" data-toggle="modal" data-narration="" data-target="" class="btn btn-sm btn-success text-white me-1 narrationModal">Send WhatsApp</button>
                        <a target="_blank" href=""  class="btn btn-sm btn-success text-white me-1">Preview</a>' : '' !!}
</td>

        <td>{{ucfirst($bill->bill_type)}}</td>
        <td>{{$bill->item_name}}</td>
        <td>{{$bill->sent_quantity}}</td>
        <td>{{$bill->rate}}</td>
        <td>{{$bill->total_price}}</td>
        <td>{{ optional($bill->created_at)->format('d M, Y') }}</td>
    </tr>
@endforeach


        </tbody>
    </table>
</section>

@endif

<div class="modal" id="narrationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="POST" class='pdf_form'>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Narration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" class="transaction_id" name="transaction_id" >
                    <input type="hidden" class="show_preview" name="show_preview" >
                    <div class="form-group">
                        <textarea class="form-control narration_input" rows="8" placeholder="Enter Narration" name="narration"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($totals)
<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Account Vouchers
</h6>
<section class="table-responsive rounded mb-5">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Voucher Date</th>
                <th>Amount</th>
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totals as $total)
            <tr>
                <td></td>
                <td>{{number_format($total->total_product_amount,2) ?? 0}}</td>
                <td>Order No. {{ $total->order_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

@endif
@if(count($totals)>1)
<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Total Amount
</h6>
<section class="table-responsive rounded mb-5">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_amount = 0;
            foreach($totals as $total):
                $total_amount += $total->total_product_amount;
            endforeach;
            ?>
            <tr>
                <td>{{ number_format($total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
</section>
@endif

@push('scripts')
<script>
    $(document).ready(() => {
        $(document).on('click', '.openDetail', function() {
            $(this).parent().find('table').toggle();
            $(this).toggleClass('greencolo');
        });
        $(document).on('click', '.narrationModal', function() {
            var trasaction = $(this).data('transaction');
            var narration = $(this).data('narration');
            $('.transaction_id').val(trasaction);
            $('.narration_input').val(narration);
            $('.show_preview').val(false);
        });
        $(document).on('click', '.previewpdf', function() {
            var trasaction = $(this).data('transaction');
            var narration = $(this).data('narration');
            $('.transaction_id').val(trasaction);
            $('.narration_input').val(narration);
            $('.show_preview').val(true);
            $('.pdf_form').submit();
        });

    });
</script>
@endpush
