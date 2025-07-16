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
            <a href="{{ route('purchase.create') }}" class="btn btn-primary btn-sm">
                Add Purchase
            </a>
        </div>

        <!-- USER TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Purchase</h6>
                </div>
                <!-- TABLE DATA -->
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Product Name</th> <!-- Fixed -->
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Return Amount</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returns as $index => $return)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $return->purchase->user->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-info">{{ $return->product_name }}</span></td>
                                        <td>{{ number_format($return->price, 2) }}</td>
                                        <td>{{ number_format($return->quantity, 2) }}</td>
                                        <td>{{ number_format($return->return_amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}</td>
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
