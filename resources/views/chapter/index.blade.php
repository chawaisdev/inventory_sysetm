@extends('layouts.app')

@section('title', 'Chapter Index')

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active">Chapters</li>
                </ol>
            </nav>
            {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#chapterModal" onclick="openCreateModal()">Add Chapter</button> --}}
        </div>

        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>All Chapters</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($chapters as $chapter)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $chapter->name }}</td>
                                        <td>{{ $chapter->slug }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning"
                                                onclick="editChapter({{ $chapter->id }})">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No chapters found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $chapters->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="chapterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="chapterForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Chapter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" id="_method" value="POST">
                        <input type="hidden" id="chapter_id" name="chapter_id">

                        <div class="form-group mb-2">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" id="chapter_name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label>XP</label>
                            <input type="number" class="form-control" name="xp" id="chapter_xp" required>
                        </div>

                        <div class="form-group mb-2">
                            <label>Cash</label>
                            <input type="number" class="form-control" name="cash" id="chapter_cash" required>
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
            $('#chapterForm').attr('action', '{{ route('chapter.store') }}');
            $('#_method').val('POST');
            $('#chapter_name').val('');
            $('#chapter_id').val('');
            $('.modal-title').text('Add Chapter');
        }

        function editChapter(id) {
            $.get("{{ url('chapter/edit') }}/" + id, function(data) {
                $('#chapterForm').attr('action', '{{ url('chapter/update') }}/' + id);
                $('#_method').val('POST');
                $('#chapter_name').val(data.name);
                $('#chapter_id').val(id);
                $('.modal-title').text('Edit Chapter');
                $('#chapterModal').modal('show');
            });
        }
    </script>
@endsection
