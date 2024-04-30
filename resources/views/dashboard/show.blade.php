@extends('layouts.dashboard')
@section('content')    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
       /* body{
        font-family: var(--bs-font-sans-serif)  !important;
       } */

        .totl-leads {
            padding: 35px 0 35px 35px !important;
            text-align: center !important;
            background: rgb(198, 225, 185);
            color: rgb(21, 107, 21);
            border-radius: 15px;
            gap: 20px;
        }

        .totl-quot {
            padding: 35px 0 35px 35px !important;
            text-align: center !important;
            border-radius: 15px;
            background: rgb(241, 206, 160);
            color: rgb(213, 40, 46);
            gap: 20px;
        }

        .totl-tasks {
            padding: 35px 0 35px 35px !important;
            text-align: center !important;
            border-radius: 15px;
            background: rgb(211, 195, 225);
            color: #8a2be2;
            gap: 20px;

        }

        .totl-invoice {
            padding: 35px 0 35px 35px !important;
            text-align: center !important;
            border-radius: 15px;
            background: rgb(188, 215, 212);
            color: rgb(36, 135, 175);
            gap: 20px;

        }

        .text h6 {
            margin-bottom: 2px !important;
        }

        .text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .icon {
            width: 15%;
        }

        .icon span {
            font-size: 20px;
            padding: 4px 12px;
            background: #fff;
            border-radius: 50%;
        }

        .see-more-btn a {
            text-decoration: none;
            color: #000;
            font-size: 17px;
        }

        span.see-more-btn {
            display: flex;
            gap: 15px;
        }

        tr {
            height: 50px;
        }

        span.first-letter {
            background: yellow;
            padding: 5px 10px;
            border-radius: 50%;
        }

        span.complete {
            background: lightgreen;
            padding: 5px;
            color: green;
            border-radius: 7px;
        }

        /* img {
            width: 100%;
        } */

        @media screen and (max-width:767px) {
            .main-tabs,.main-appointment {
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row main-tabs mt-3">
            <div class="col-md-3">
                <div class="totl-leads d-flex ">
                    <div class="icon d-flex justify-content-center">
                        <span>
                            <i class="bi bi-calendar4"></i>
                        </span>
                    </div>
                    <div class="text">
                        <h6>50</h6>
                        <h6>Total Leads</h6>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="totl-quot d-flex">
                    <div class="icon  d-flex justify-content-center">
                        <span>
                            <i class="bi bi-journal-check"></i>
                        </span>
                    </div>
                    <div class="text">
                        <h6>50</h6>
                        <h6>
                            Total Quotations
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="totl-tasks d-flex ">
                    <div class="icon  d-flex justify-content-center">
                        <span>
                            <i class="bi bi-bell"></i>
                        </span>
                    </div>
                    <div class="text">
                        <h6>50</h6>
                        <h6>Total Tasks</h6>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="totl-invoice d-flex ">
                    <div class="icon  d-flex justify-content-center">
                        <span>
                            <i class="bi bi-layers"></i>
                        </span>
                    </div>
                    <div class="text">
                        <h6>50</h6>
                        <h6>Total Invoices</h6>
                    </div>

                </div>
            </div>
        </div>

        <div class="appointments-section">
            <div class="row main-appointment mt-3">
                <div class="col-md-8">
                    <div class="row justify-content-between">
                        <div class="col-md-3 col-sm-6">
                            <h6>Today's Follow-Up</h6>
                        </div>
                        <div class="col-md-3 col-sm-6 d-flex justify-content-end">
                            <span class="see-more-btn">
                                <a href="javascript:void(0)">See more</a>
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        </div>
                    </div>
                    <div class="table-main mt-3">
                        <table class="rounded" style="width:100%;border: 1px  solid lightblue;">
                           
                                <tr style="background: lightblue;">
                                    <td style="text-align: center;">Sr No</td>
                                    <td style="text-align: center;">Due Date</td>
                                    <td style="text-align: center;">Type</td>
                                    <td style="text-align: center;">Party Name</td>
                                    <td style="text-align: center; ">Action</td>
                                </tr>
                                <?php $i = 1; ?>
                                @foreach ($purchase_orders as $order) 
                                <tr>
                                    <td style="text-align: center;">{{$i}}</td>
                                    <td style="text-align: center;">{{ $order->due_date->format('d M, Y') }}</td>
                                    <td style="text-align: center;">
                                        <div class="cutomer-name">
                                            <span class="first-letter" style="margin-right: 10px;">{{ucfirst($order->type[0])}}</span>
                                            <span>{{ ucwords($order->type) }}</span>
                                        </div>
                                    </td>

                                    <td style="text-align: center;">{{ Str::limit($order->party->name, 20) }}</td>
                                    <td style="text-align: center;">
                                        <span class="complete">{{ ucwords($order->status) }}</span>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                                @endforeach
                            
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row justify-content-between">
                        <div class="col-md-12 col-sm-12">
                            <div class="missing-report p">
                                <h6 style="padding-left: 5px;">Missing Follow-Up Report</h6>
                                <div class="chart">
                                    <img src="{{ asset('assets/dashboard/images/pie.svg')}}" style="width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="payment-section">
            <div class="col-md-12 mt-3">
                <div class="line-chart">
                    <img src="{{ asset('assets/dashboard/images/line.svg') }}" style="width:100%">
                </div>
            </div>
        </div>
    </div>
    @endsection