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
                        @method('POST')

                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="age" class="form-label">Age</label>
                                <input type="age" name="age" class="form-control" placeholder="Enter age"
                                    value="{{ old('age', $user->age) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="profile_pic" class="form-label">Profile Pic</label>
                                <input type="file" name="profile_pic" class="form-control" placeholder="Enter profile_pic"
                                    value="{{ old('profile_pic', $user->profile_pic) }}" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="password" class="form-label">Password (optional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
