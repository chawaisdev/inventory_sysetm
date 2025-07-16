@extends('layouts.app')

@section('title')
    User Index
@endsection

@section('body')
    <div class="container-fluid">
        <!-- PAGE HEADER AND ADD BUTTON -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Index</li>
                </ol>
            </nav>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                Add sale
            </a>
        </div>

        <!-- USER TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All sale</h6>
                </div>
                <!-- TABLE DATA -->
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Products</th> <!-- ✅ Added -->
                                    <th scope="col">Total Amount</th>
                                    <th scope="col">Paid Amount</th>
                                    <th scope="col">Due Amount</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $sale->invoice_no }}</td>
                                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @foreach ($sale->saleItems as $item)
                                                <span class="badge bg-secondary">{{ $item->product->name ?? 'N/A' }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($sale->total_amount, 2) }}</td>
                                        <td>{{ number_format($sale->paid_amount, 2) }}</td>
                                        <td>{{ number_format($sale->due_amount, 2) }}</td>
                                        <td><span class="badge bg-danger">{{ ucfirst($sale->payment_method) }}</span></td>
                                        <td>{{ $sale->date->format('Y-m-d') }}</td>
                                        <td>{{ $sale->note }}</td>
                                        <td>
                                            <!-- your action buttons here -->
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
