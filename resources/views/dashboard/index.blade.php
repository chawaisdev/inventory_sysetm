@extends('layouts.app')
@section('title', 'Dashboard')

@section('body')
    <div class="container-fluid">
        <div class="row mb-4">
            {{-- Total Sales --}}
            <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-primary-transparent">
                                    <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                                </span>
                            </div>
                            <div class="col-8 px-0">
                                <div class="mb-2">Total Sales</div>
                                <div class="text-muted fs-12">
                                    <span class="text-dark fw-semibold fs-20">{{ number_format($totalSalesAmount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Purchase --}}
            <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-success-transparent">
                                    <i class="fas fa-shopping-bag fa-2x text-success"></i>
                                </span>
                            </div>
                            <div class="col-8 px-0">
                                <div class="mb-2">Total Purchases</div>
                                <div class="text-muted fs-12">
                                    <span
                                        class="text-dark fw-semibold fs-20">{{ number_format($totalPurchaseAmount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Suppliers --}}
            <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-warning-transparent">
                                    <i class="fas fa-user-tie fa-2x text-warning"></i>
                                </span>
                            </div>
                            <div class="col-8 px-0">
                                <div class="mb-2">Total Suppliers</div>
                                <div class="text-muted fs-12">
                                    <span class="text-dark fw-semibold fs-20">{{ $totalSuppliers }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Customers --}}
            <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-info-transparent">
                                    <i class="fas fa-users fa-2x text-info"></i>
                                </span>
                            </div>
                            <div class="col-8 px-0">
                                <div class="mb-2">Total Customers</div>
                                <div class="text-muted fs-12">
                                    <span class="text-dark fw-semibold fs-20">{{ $totalCustomers }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            {{-- Low Stock Products --}}
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title">Low Stock Products (&lt; 25)</h5>
                    </div>
                    <div class="card-body">
                        @if ($lowStockProducts->isEmpty())
                            <p class="text-muted">All products are sufficiently stocked.</p>
                        @else
                            <ul class="list-group mb-3">
                                @foreach ($lowStockProducts as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $product->name }}
                                        <span class="badge bg-danger rounded-pill">{{ $product->stock }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-center">
                                {{ $lowStockProducts->appends(['sold' => request('sold')])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sold Products --}}
            <div class="col-md-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title">Sold Products</h5>
                    </div>
                    <div class="card-body">
                        @if ($soldProducts->isEmpty())
                            <p class="text-muted">No products sold yet.</p>
                        @else
                            <ul class="list-group mb-3">
                                @foreach ($soldProducts as $saleItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $saleItem->product->name ?? 'N/A' }}
                                        <span class="badge bg-success rounded-pill">Qty: {{ $saleItem->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-center">
                                {{ $soldProducts->appends(['lowstock' => request('lowstock')])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
