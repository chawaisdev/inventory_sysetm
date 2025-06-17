@extends('layouts.app')

@section('title', 'Brand Index')

@section('body')
<div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">Brands</li>
            </ol>
        </nav>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#brandModal" onclick="openCreateModal()">Add Brand</button>
    </div>

    <div class="col-xl-12">
        <div class="card custom-card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>All Brands</h6>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="example" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->description }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editBrand({{ $brand->id }})">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No brands found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="brandForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <input type="hidden" id="brand_id" name="brand_id">

                    <div class="form-group mb-2">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="brand_name" required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="brand_description"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        $('#brandForm').attr('action', '{{ route('brands.store') }}');
        $('#_method').val('POST');
        $('#brand_name').val('');
        $('#brand_description').val('');
        $('#brand_id').val('');
        $('.modal-title').text('Add Brand');
        $('#brandModal').modal('show');
    }

    function editBrand(id) {
        $.get("{{ url('brands') }}/" + id, function(data) {
            $('#brandForm').attr('action', '{{ url('brands') }}/' + id);
            $('#_method').val('PUT');
            $('#brand_name').val(data.name);
            $('#brand_description').val(data.description);
            $('#brand_id').val(id);
            $('.modal-title').text('Edit Brand');
            $('#brandModal').modal('show');
        });
    }
</script>
@endsection
