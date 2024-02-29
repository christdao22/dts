{{-- Developer: Christian P. Daohog --}}
{{-- Module: Document Category Setting --}}

@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    td:nth-child(1) {
        width: 90%;
    }

    td:nth-child(2) {
        width: 10%;
    }

</style>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h3 class="mb-sm-0">Document Category</h3>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DTS</a></li>
                        <li class="breadcrumb-item active">Document Category</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#myModal">Add Document Category</button>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th><strong>Category Name</strong></th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top" title="Action"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td class="text-uppercase">{{ $category->category_name }}</td>
                                <td class="text-center d-flex gap-1">
                                    <button class="btn btn-info edit-category" id="{{ $category->id }}"><i
                                            class="ri-edit-2-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit"></i></button>
                                    <button class="btn btn-danger deleteBtn" data-bs-id={{ $category->id }}><i class="ri-delete-bin-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete"></i>
                                        <form id="delete_form_{{ $category->id }}" action="{{ route('document_category.destroy', $category->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>

{{-- Confirm Delete --}}
<div class="modal" id="confirmModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center ">
                    <i class="ri-error-warning-line text-danger" style="font-size: 100px;"></i>
                    <p style="font-size: 25px">Delete document category?</p>
                </div>
                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title secondary">Add Document Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('document_category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-4">
                            <div class="form-check form-switch ps-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-check-label" for="is_direct">Proceed Directly</label>
                                    <input class="form-check-input" type="checkbox" name="is_direct" id="is_direct" style="width: 3.5em; height: 1.5em;">
                                </div>
                                @error('is_direct')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input name="category_name" id="category_name" class="form-control"
                                placeholder="Enter document category name" required></input>
                            @error('category_name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="text" id="categoryId" name="categoryId" class="d-none">
                        <input type="text" id="isEdit" name="isEdit" value="0" class="d-none">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModalBtn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light" id="addUpdateBtn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        @if(count($errors) > 0)
        $('#myModal').modal('show');
        @endif

        $('#myModal').on('hidden.bs.modal', function() {
            $('#category_name').val('');
            $('#categoryId').val('');
            $('#isEdit').val(0);
            $('#is_direct').val(0);
            $('#addUpdateBtn').text('Add');
            $('.modal-title').text('Add Document Category');
        });

        $('.deleteBtn').on('click', function (e) {
            var id = $(this).data('bs-id');
            $('#confirmModal').modal('show');
            $('#confirmDelete').on('click', function () {
                $('#delete_form_' + id).submit()
            });
        });

        $('.edit-category').on('click', function (e) {
            var id = $(this).attr('id');
            $.ajax({
                url: "/document/document_category/" + id,
                type: 'GET',
                success: function(data) {
                    console.log(data.category.is_direct);
                    $('#category_name').val(data.category.category_name);
                    $('#categoryId').val(data.category.id);
                    $('#is_direct').prop('checked', data.category.is_direct);
                    $('#isEdit').val(1);

                }
            });

            $('#myModal').modal('toggle');
            $('#addUpdateBtn').text('Update');
            $('.modal-title').text('Edit Document Category');
        });
    });



</script>
@endsection
