@extends('layouts.app')
@section('title', 'Dashboard')

@section('body')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-user fs-1 me-3"></i>
                    <div>
                        <h6>Total Suppliers</h6>
                        <h3>{{ $totalSuppliers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-users fs-1 me-3"></i>
                    <div>
                        <h6>Total Customers</h6>
                        <h3>{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-package fs-1 me-3"></i>
                    <div>
                        <h6>Total Products</h6>
                        <h3>{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-coin fs-1 me-3"></i>
                    <div>
                        <h6>Total Purchase</h6>
                        <h3>Rs. {{ number_format($totalPurchaseAmount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-chart-bar fs-1 me-3"></i>
                    {{-- <div>
                        <h6>Last 7 Days Sale</h6>
                        <h3>Rs. {{ number_format($last7DaysSale) }}</h3>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="ti ti-calendar fs-1 me-3"></i>
                    {{-- <div>
                        <h6>This Month Sale</h6>
                        <h3>Rs. {{ number_format($thisMonthSale) }}</h3>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    @if($lowStockProducts->count())
    <div class="row">
        <div class="col-md-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white d-flex justify-content-between">
                    <span><i class="ti ti-alert-triangle me-2"></i>Low Stock Alert</span>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-danger fw-bold">{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <script>
                        setTimeout(function () {
                            alert("⚠️ Low stock detected! Please restock.");
                        }, 500);
                    </script>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
