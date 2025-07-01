@extends('layouts.app')

@section('title')
    Purchase Details
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Details</li>
                </ol>
            </nav>
        </div>
        <!-- PAGE HEADER -->
        <h3>Supplier: {{ $supplierName }}</h3>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-primary-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M18,6h-2c0-2.21-1.79-4-4-4S8,3.79,8,6H6C4.9,6,4,6.9,4,8v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8C20,6.9,19.1,6,18,6z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col-9 px-0">
                                <div class="mb-2">Total Sales</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ number_format($totalSales, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-secondary-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2L19.5,3.5z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col-9 px-0">
                                <div class="mb-2">Paid Amount</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ number_format($paidAmount, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-warning-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col-9 px-0">
                                <div class="mb-2">Due Amount</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ number_format($dueAmount, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 d-flex align-items-center justify-content-center px-0">
                                <span class="rounded p-3 bg-success-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="#000000"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 6c1.1 1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col-9 px-0">
                                <div class="mb-2">Total Orders</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ $totalOrders }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PURCHASE TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <!-- TABLE DATA -->
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Supplier Name</th>
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
                                <tr>
                                    <td>1</td>
                                    <td>{{ $purchases->invoice_no }}</td>
                                    <td>{{ $purchases->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($purchases->total_amount, 2) }}</td>
                                    <td>{{ number_format($purchases->paid_amount, 2) }}</td>
                                    <td>{{ number_format($purchases->due_amount, 2) }}</td>
                                    <td>{{ ucfirst($purchases->payment_method) }}</td>
                                    <td>{{ $purchases->date->format('Y-m-d') }}</td>
                                    <td>{{ $purchases->note }}</td>
                                    <td>
                                        <a href="{{ route('purchase.edit', $purchases->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('purchase.destroy', $purchases->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure?')"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
