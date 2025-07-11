@extends('layouts.app')

@section('title')
    Product Index
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
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                Add Product
            </a>
        </div>

        <!-- USER TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Users</h6>
                </div>
                <!-- TABLE DATA -->
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Brand Name</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Purchase Price</th>
                                    <th scope="col">Sale Price</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Unit</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($products->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No users found</td>
                                    </tr>
                                @else
                                    @foreach ($products as $products)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $products->brand ? $products->brand->brand_name : 'N/A' }}</td>
                                            <td>{{ $products->name }}</td>
                                            <td>{{ $products->purchase_price }}</td>
                                            <td>{{ $products->sale_price }}</td>
                                            <td>{{ $products->stock }}</td>
                                            <td>{{ $products->unit }}</td>
                                            <td>{{ $products->discount }}</td>
                                            <td>
                                                <!-- DELETE USER BUTTON -->
                                                <form action="{{ route('products.destroy', $products->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>

                                                <!-- EDIT USER BUTTON -->
                                                <a href="{{ route('products.edit', $products->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-pen-to-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
