@extends('layouts.dashboard')
@section('content')

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
                <td>{{ $order->order_number }}</td>
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
<p class="p-3 border mb-4">{{$bills['0']->narration}} </p>

<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Whatsaap Narration
</h6>
<p class="p-3 border mb-4">{{$bills['0']->whats_app_narration}}</p>


</section>
@endsection


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
