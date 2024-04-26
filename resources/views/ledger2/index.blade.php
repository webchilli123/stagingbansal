@extends('layouts.dashboard')
@section('content')


<header class="d-flex justify-content-between align-items-center mb-4 d-none-print">
    <h5>Ledger <span class="badge bg-primary ms-1 fw-normal">lazy</span></h5>
    <div>
        <button onclick="return window.print();" class="btn btn-sm btn-success text-white me-1">Print</button>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
</header>

@include('ledger2.accordion')
{{-- account ledger --}}
<section class="table-responsive mb-lg-5 pb-lg-5">
    <table class="table table-bordered d-none" style="min-width: 40rem;" id="account-transactions">
        <thead>
            <tr>
                <th colspan="7" class="text-center">
                    <i class="fa fa-circle text-success me-1"></i> Account Ledger
                </th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Particlar</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>DR/CR</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    {{-- stock ledger --}}
    <table class="table table-bordered d-none" style="min-width: 40rem;" id="stock-transactions">
        <thead>
            <tr>
                <th colspan="7" class="text-center">
                    <i class="fa fa-circle text-success me-1"></i> Stock Ledger
                </th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Particlar</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>DR/CR</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        
    <div class="text-center d-none pt-2 pb-5 mb-5" id="spinner">
        <div class="spinner-border text-primary mb-2"></div>
        <p class="text-muted mb-5">Please Wait...</p>
    </div>
    <article class="alert alert-primary text-center d-none" id="no-more">No More Transactions</article>
</section>
@endsection


@push('scripts')
<script>
    let balance = 0, 
        url = '{{ route('ledger2.index') }}', 
        pageUrl = url;

    const accountsTable = $('table#account-transactions'),
          stocksTable = $('table#stock-transactions'),
          noMoreAlert = $('article#no-more');

                
    $(document).ready(() => {

      $('select').selectize();
      
      $('#search').submit((e)=>{
           e.preventDefault();
           console.log($('select[name=party_id]').val());
           console.log($('select[name=type]').val());
           balance = 0;
           pageUrl = url;
           $('table#account-transactions tbody').html('');
           $('table#stock-transactions tbody').html('');
           noMoreAlert.addClass('d-none');
            getTransactions();
      });

    });   

    $(window).scroll(function(){

        if(!$('select[name=party_id]').val() && !$('select[name=type]').val()){
             console.log('no input');
             return;
        }

          if(pageUrl == null){
            console.log('no more data');
            if(noMoreAlert.hasClass('d-none')){ noMoreAlert.removeClass('d-none'); }
            return;
          }

        if($(this).scrollTop() == $(document).height() - $(this).height()){  
         console.log('load more...'); 
          setTimeout(()=>{ getTransactions();}, 2000)
        }
      });

    function getTransactions(){
       
        $.ajax({
             url : pageUrl,
             data : {
                party_id : $('select[name=party_id]').val(),
                type : $('select[name=type]').val(),
               },
             beforeSend : ()=> { $('#spinner').toggleClass('d-none'); },
             complete : ()=> { $('#spinner').toggleClass('d-none'); },
             success : (res) => { 
                console.log(res);
                pageUrl = res.next_page_url;
                if($('select[name=type]').val() == 'account' && accountsTable.hasClass('d-none')){ 
                    accountsTable.removeClass('d-none'); 
                    stocksTable.addClass('d-none'); 
                }
                if($('select[name=type]').val() == 'stock' && stocksTable.hasClass('d-none')){ 
                    stocksTable.removeClass('d-none');
                    accountsTable.addClass('d-none');
                }
                $('select[name=type]').val() == 'account' 
                 ? showAccountTransactions(res.data)
                 : showStockTransactions(res.data);
            },
            errror : (res) => { console.log(res) }
        });
    }

   function showAccountTransactions(transactions){

     let rows = '';  

     transactions.forEach(transaction =>{
                
        rows += `<tr>
                    <td>
                    ${ transaction.transaction_date }
                    </td>
                    <td>
                        ${ 
                        transaction.creditor_id.length == 0
                        ? '-' 
                        : '<a href="account-vouchers/'+transaction.id+'/edit" target="_blank" class="mb-1 d-block">'+transaction.party_name+'</a>' 
                            +'<p class="overflow-auto mb-0" style="max-width:22rem;">'+ transaction.narration +'</p>'
                        }      
                    </td>
                    <td>
                    ${ transaction.amt_debt > 0 ? transaction.amt_debt : '0' }
                    </td>
                    <td>
                    ${ transaction.amt_credit > 0 ? transaction.amt_credit : '0' }
                    </td>
                    <td>${ updateBalance(transaction.amt_credit, transaction.amt_debt) }
                    </td>
                    <td>
                    ${ balance > 0 ? 'Cr' : '' }
                    ${ balance < 0 ? 'Dr' : '' }
                    ${ balance == 0 ? '-' : '' }
                    </td>
                </tr>`;
            });

     $('table#account-transactions tbody').append(rows);

   }

   function showStockTransactions(transactions){

        let rows = '';  

        transactions.forEach(transaction =>{
                
        rows += `<tr>
                    <td>
                    ${ transaction.transaction_date }
                    </td>
                    <td>
                        ${ 
                        transaction.creditor_id.length == 0
                        ? '-' 
                        : '<a href="stock-vouchers/'+transaction.id+'/edit" target="_blank" class="mb-1 d-block">'+transaction.party_name+'</a>' 
                            +'<p class="overflow-auto mb-0" style="max-width:22rem;">'+ transaction.narration +'</p>'
                        }      
                    </td>
                    <td>
                    ${ transaction.stock_debt > 0 ? transaction.stock_debt : '0' }
                    </td>
                    <td>
                    ${ transaction.stock_credit > 0 ? transaction.stock_credit : '0' }
                    </td>
                    <td>${ updateBalance(transaction.stock_credit, transaction.stock_debt) }
                    </td>
                    <td>
                    ${ balance > 0 ? 'Cr' : '' }
                    ${ balance < 0 ? 'Dr' : '' }
                    ${ balance == 0 ? '-' : '' }
                    </td>
                </tr>`;
            });

        $('table#stock-transactions tbody').append(rows);

        }

   function updateBalance(credit, debt){
       
     balance = balance + credit - debt;
     return (Math.abs(balance)).toFixed(2);
   }

</script>
@endpush