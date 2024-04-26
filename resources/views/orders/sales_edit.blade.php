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
    <h5><a href="{{route('orders.show', $transactiondata[0]->id)}}"> Order Detail </a> / Sales Edit</h5>
</header>


<section id="input-with-icons">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="{{route('orders.sales.submit')}}" method="post">
                            @csrf
                            @foreach($transactiondata as $data)

                            <div class="row">
                                <input type="hidden" name="ids[]" value="{{$data->id}}">
                                <div class="col-md-3">
                                    <label>Type</label>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control" id="iconLeft2" placeholder="Item" value="{{ucfirst($data->type)}}" readonly>
                                    </fieldset>
                                </div>
                                <div class="col-md-2">
                                    <label>Item</label>
                                    <fieldset class="form-group position-relative has-icon-right">
                                        <select name="items[]" class="form-control">
                                            <option value="{{$data->item->id}}">{{$data->item->name}}</option>
                                            @foreach($items as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-position">
                                            <i class="ft-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-3">
                                    <label>Quantity</label>
                                    <fieldset class="form-group position-relative has-icon-right">
                                        <input type="text" class="form-control" id="iconLeft2" placeholder="Quantity" value="{{abs($data->quantity)}}" name="quantities[]">
                                        <div class="form-control-position">
                                            <i class="ft-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2">
                                    <label>Rate</label>
                                    <fieldset class="form-group position-relative has-icon-right">
                                        <input type="text" class="form-control" id="iconLeft2" placeholder="Rate" value="{{abs($data->rate)}}" name="rates[]">
                                        <div class="form-control-position">
                                            <i class="ft-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2">
                                    <label>Start Date</label>
                                    <fieldset class="form-group position-relative has-icon-right">
                                        <input type="date" class="form-control" id="iconLeft2" placeholder="Start Date" value="{{date('Y-m-d', strtotime($data->transport_date))}}" name="dates[]" readonly>
                                        <div class="form-control-position">
                                            <i class="ft-file"></i>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            @endforeach
                            <div class="my-3">
                                <label for="" class="form-label">Narration</label>
                                <textarea name="narration" id="narration" cols="30" rows="5" class="form-control">{{$transactiondata[0]->transaction->narration ?? ''}}</textarea>
                            </div>
                            <button class="btn btn-primary mt-2" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('scripts')
@endpush