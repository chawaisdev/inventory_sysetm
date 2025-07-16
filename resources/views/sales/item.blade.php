@extends('layouts.app')

@section('title', 'All Purchase Items')

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Items</li>
                </ol>
            </nav>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Purchase</h6>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-hover text-nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sr #</th>
                                <th>Invoice #</th>
                                <th>Customer Name</th>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Discount (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sale as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->sale->invoice_no ?? 'N/A' }}</td>
                                    <td>{{ $item->sale->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->total_price, 2) }}</td>
                                    <td>{{ $item->discount ?? 0 }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No sale items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
