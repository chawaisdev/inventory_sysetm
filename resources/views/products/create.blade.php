@extends('layouts.app')

@section('title')
    Item Index
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Purchase Price</label>
                                <input type="number" name="purchase_price" class="form-control"
                                    value="{{ old('purchase_price') }}" required>
                                @error('purchase_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Sale Price</label>
                                <input type="text" name="sale_price" class="form-control"
                                    value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Stock</label>
                                <input type="text" name="stock" class="form-control" value="{{ old('stock') }}">
                                @error('stock')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Unit</label>
                                <input type="text" name="unit" class="form-control" value="{{ old('unit') }}">
                                @error('unit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Product</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
