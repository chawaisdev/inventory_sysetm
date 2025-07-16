@extends('layouts.app')

@section('title')
    Brand Index
@endsection

@section('body')
    <div class="container-fluid">
        <!-- PAGE HEADER -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brand Index</li>
                </ol>
            </nav>
            <button class="btn btn-primary btn-sm" onclick="openAddModal()">Add Brand</button>
        </div>

        <!-- TABLE -->
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Brands</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Brand Name</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $index => $brand)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $brand->brand_name }}</td>
                                        <td>{{ $brand->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-warning"
                                                onclick="openEditModal({{ $brand }})">
                                                <i class="fa fa-pen-to-square"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                                @if ($brands->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No brands found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="brandForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="id" id="brand_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="brandModalLabel">Add Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="brand_name">Brand Name</label>
                            <input type="text" class="form-control" name="brand_name" id="brand_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openAddModal() {
            $('#brandForm').attr('action', '{{ route('brands.store') }}');
            $('#formMethod').val('POST');
            $('#brandModalLabel').text('Add Brand');
            $('#brand_name').val('');
            $('#brand_id').val('');
            $('#brandModal').modal('show');
        }

        function openEditModal(brand) {
            $('#brandForm').attr('action', '/brands/' + brand.id);
            $('#formMethod').val('PUT');
            $('#brandModalLabel').text('Edit Brand');
            $('#brand_name').val(brand.brand_name);
            $('#brand_id').val(brand.id);
            $('#brandModal').modal('show');
        }
    </script>
@endsection
