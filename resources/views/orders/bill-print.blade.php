

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
        <tr>
    <td></td>
    <td></td>
    <td><span class="badge alert-primary"></span></td>
    <td></td>
    <td></td>
    <td></td>
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
            
            <tr>
                <td>
                    
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
           
            <tr class="fw-bold">
                <td colspan="3"></td>
                <td></td>
                <td></td>
            </tr>
            
        </tbody>
    </table>
</section>



<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Narration
</h6>
<p class="p-3 border mb-4"></p>

<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Whatsaap Narration
</h6>
<p class="p-3 border mb-4"></p>

<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i>
    Sale
</h6>
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered align-middle" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Transaction No.</th>
                <th>Type</th>
                <th>Item</th>
                <th>Quantity </th>
                <th>Rate</th>
                <th>Total</th>
                <th> Date</th>
                <th>Transport</th>
            </tr>
        </thead>
        <tbody>
           
            <tr>
                    <td colspan='3'></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan='2'></td>
                </tr>
                

            <tr>
                
                    <td rowspan="4">
                        <input type="hidden" name="transaction_id" required value="">
                        <button data-transaction="" data-toggle='modal' data-narration="" data-target="#narrationModal" class="btn btn-sm btn-success text-white me-1 narrationModal">Send whatsApp</button>
                        <a target='_blank' href=""  class="btn btn-sm btn-success text-white me-1 ">Preview</a>
                        <a href="" class="btn btn-sm btn-secondary">Edit</a>
                    </td>
                
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="p-0" style="max-width:10rem;text-align:center;">
                    <i class='fa fa-folder openDetail' data-toggle='tooltip' data-placement='top'></i>
                    <table class="table mb-0" style='display:none;'>
                        <tbody>
                            <tr>
                                <th>Name--</th>
                                <td class="border-start"></td>
                            </tr>
                            <tr>
                                <th>Bilty No.</th>
                                <td class="border-start"><img src="/images/" width="100" height="50px"></td>
                            </tr>
                            <tr>
                                <th>Vehicle No.</th>
                                <td class="border-start"></td>
                            </tr>
                            <tr style="border-bottom-color: transparent;">
                                <th>Transport Date</th>
                                <td class="border-start"></td>
                            </tr>
                        </tbody>
                    </table>
                   
                </td>
                {{-- <td> --}}
                {{-- <form action="" method="POST">
                @csrf
                <input type="hidden" name="transcation_id" required value="">
                <button type="submit" class="btn btn-sm btn-success text-white me-1">Send whatsApp</button>
                </form> --}}


                {{-- </td> --}}
            </tr>

            <?php
            /* $val_single = (float)$transaction->rate * (float)(abs($transaction->quantity));
            $val_total_one += (float)$val_single;  */
            ?>
                <tr>
                <td colspan='3'></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan='2'></td>
                </tr>
            
        </tbody>
    </table>
</section>


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
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</section>

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
