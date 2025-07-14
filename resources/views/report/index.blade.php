@extends('layouts.app')

@section('title')
    Report Index
@endsection

@section('body')
    <div class="container-fluid">
        <!-- PAGE HEADER AND ADD BUTTON -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Report Index</li>
                </ol>
            </nav>
        </div>

        <!-- USER TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Purchase Report</h6>
                </div>
                <!-- TABLE DATA -->
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Purchase Invoice</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                        <td>{{ $transaction->purchase->invoice_no ?? 'N/A' }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ ucfirst($transaction->payment_method) }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                                        <td>{{ $transaction->note }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info disabled">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
