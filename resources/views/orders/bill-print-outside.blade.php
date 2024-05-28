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
                <th>Order Date</th>
                <th>Party Name</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $bills->bill_id }}</td>
            <td>{{ ucfirst($bills->bill_type) }}</td>
            <td>{{ date('d M, Y', strtotime($bills->created_at)) }}</td>
            <td>{{ $bills->party ? $bills->party->name : '' }}</td>
        </tr>

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
            @foreach($bills->billItems as $item)
            <tr>
                <td>{{$item->item}}</td>
                <td>{{$item->order_quantity}}</td>
                <td>{{$item->sent_quantity}}</td>
                <td>{{$item->rate}}</td>
                <td>{{$item->price}}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td><b>{{ number_format($totals, 2) }}</b></td>
            </tr>
        </tbody>
    </table>
</section>



<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Narration
</h6>
<p class="p-3 border mb-4">{{$bills->narration ?? ''}} </p>




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
