@extends('layouts.app')

@section('title')
    Edit Item
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Item Edit</li>
                </ol>
            </nav>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white shadow p-4">
                    <form action="{{ route('adduser.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone"
                                    value="{{ old('phone', $user->phone) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Enter address"
                                    value="{{ old('address', $user->address) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="user_type" class="form-label">User Type</label>
                                <select name="user_type" class="form-select" required>
                                    <option value="">Select User Type</option>
                                    <option value="customer" {{ old('user_type', $user->user_type) == 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="supplier" {{ old('user_type', $user->user_type) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                </select>    
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
