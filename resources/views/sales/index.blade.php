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
                                            <button class="btn btn-sm btn-primary toggle-payment"
                                                data-id="{{ $sale->id }}">Pay</button>
                                        </td>
                                    </tr>

                                    <!-- Expandable payment row -->
                                    <tr id="payment-row-{{ $sale->id }}" class="d-none">
                                        <td colspan="11">
                                            <form action="{{ route('sale.payment', $sale->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $sale->user_id }}">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Paid Amount</label>
                                                        <input type="number" name="paid_amount" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Payment Method</label>
                                                        <select name="payment_method" class="form-control" required>
                                                            <option value="">Select</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="bank">Bank</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Date</label>
                                                        <input type="date" name="date" class="form-control"
                                                            value="{{ now()->toDateString() }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Note</label>
                                                        <input type="text" name="note" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-success">Submit Payment</button>
                                                </div>
                                            </form>
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
    <script>
        document.querySelectorAll('.toggle-payment').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = document.getElementById('payment-row-' + id);
                row.classList.toggle('d-none');
            });
        });
    </script>
@endsection
