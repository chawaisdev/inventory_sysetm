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
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Product Names</th> <!-- NEW -->
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
                                @foreach ($purchases as $index => $purchase)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $purchase->invoice_no }}</td>
                                        <td>{{ $purchase->user->name ?? 'N/A' }}</td>

                                        <!-- Product Names Column -->
                                        <td>
                                            @foreach ($purchase->items as $item)
                                                <span class="badge bg-info">{{ $item->product_name }}</span>
                                            @endforeach
                                        </td>

                                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                        <td>{{ number_format($purchase->paid_amount, 2) }}</td>
                                        <td>{{ number_format($purchase->due_amount, 2) }}</td>
                                        <td><span class="badge bg-success ">{{ ucfirst($purchase->payment_method) }}</span></td>
                                        <td>{{ $purchase->date->format('Y-m-d') }}</td>
                                        <td>{{ $purchase->note }}</td>
                                        <td>
                                            <a href="{{ route('purchase.show', $purchase) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#payModal{{ $purchase->id }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </button>

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
    @foreach ($purchases as $purchase)
        <!-- Payment Modal -->
        <div class="modal fade" id="payModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="payModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('purchase.payment', $purchase->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Make Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Total:</strong> {{ number_format($purchase->total_amount, 2) }}</p>
                            <p><strong>Paid:</strong> {{ number_format($purchase->paid_amount, 2) }}</p>
                            <p><strong>Due:</strong> {{ number_format($purchase->due_amount, 2) }}</p>

                            <input type="hidden" name="user_id" value="{{ $purchase->user_id }}">

                            <div class="mb-3">
                                <label>Payment Amount</label>
                                <input type="number" step="0.01" class="form-control" name="paid_amount" required
                                    max="{{ $purchase->due_amount }}">
                            </div>

                            <div class="mb-3">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" required
                                    value="{{ now()->toDateString() }}">
                            </div>

                            <div class="mb-3">
                                <label>Note</label>
                                <textarea name="note" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
