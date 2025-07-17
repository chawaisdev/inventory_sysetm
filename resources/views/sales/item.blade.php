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
                                <th>Action</th>
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
                                    <td>
                                        <!-- Return Button -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $item->id }}">
                                            Return
                                        </button>

                                        <!-- Return Modal -->
                                        <div class="modal fade" id="returnModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="returnModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('sale.return') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="sale_id" value="{{ $item->sale_id }}">
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $item->product_id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="returnModalLabel{{ $item->id }}">Return Product
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Product</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $item->product->name }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Price</label>
                                                                <input type="number" class="form-control"
                                                                    value="{{ $item->unit_price }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Quantity to Return</label>
                                                                <input type="number" name="quantity" class="form-control"
                                                                    min="1" max="{{ $item->quantity }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Return Date</label>
                                                                <input type="date" name="return_date"
                                                                    class="form-control" value="{{ date('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Submit
                                                                Return</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
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
