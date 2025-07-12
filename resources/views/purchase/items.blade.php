@extends('layouts.app')

@section('title', 'All Purchase Items')

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
        </div>
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Purchase</h6>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Invoice #</th>
                                <th>Supplier Name</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Line Total</th>
                                <th>Purchase Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->purchase->invoice_no ?? 'N/A' }}</td>
                                    <td>{{ $item->purchase->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->line_total }}</td>
                                    <td>{{ $item->purchase->date ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No purchase items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
