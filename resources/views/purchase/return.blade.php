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
        <form action="{{ route('purchase.return.store') }}" method="POST">
            @csrf
            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">

            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Return Date</label>
                <input type="date" name="return_date" value="{{ date('Y-m-d') }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-danger">Return Product</button>
        </form>
    </div>
@endsection
