{{-- Developer: Christian P. Daohog --}}
{{-- Module: Users Page --}}

@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
    td:nth-child(1) {
        width: 25%;
    }

    td:nth-child(2) {
        width: 25%;
    }

    td:nth-child(3) {
        width: 15%;
    }

    td:nth-child(4) {
        width: 25%;
    }

    td:nth-child(5) {
        width: 10%;
    }
</style>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h3 class="mb-sm-0">Users</h3>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DTS</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                        <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#myModal">Add
                            User</button>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th><strong>Name</strong></th>
                                <th><strong>Office</strong></th>
                                <th><strong>Role</strong></th>
                                <th><strong>Email</strong></th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Action"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="text-capitalize">{{ $user->first_name }}
                                    {{ mb_substr($user->middle_name, 0, 1) }}. {{ $user->last_name }}</td>
                                <td>{{ $user->office->office_name }}</td>
                                <td>{{ $user->is_admin == 1? 'Admin':($user->is_dm==1? 'Decision Maker':'Office Terminal') }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center d-flex gap-1">
                                    <button class="btn btn-info edit_btn" id="{{ $user->id }}"><i
                                            class="ri-edit-2-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit"></i></button>
                                    <button class="btn btn-danger deleteBtn" data-bs-id={{ $user->id }}>
                                        <i class="ri-delete-bin-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete"></i>
                                        <form id="delete_form_{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            enctype="multipart/form-data">
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
                    <p style="font-size: 25px">Delete the user?</p>
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
                <h5 class="modal-title secondary">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-4 d-flex flex-column gap-3">
                            <div class="form-check form-switch ps-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-check-label" for="can_create">Can create document</label>
                                    <input class="form-check-input" type="checkbox" name="can_create" id="can_create" style="width: 3.5em; height: 1.5em;">
                                </div>
                                @error('can_create')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check form-switch ps-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-check-label" for="can_view_all">Can view all document</label>
                                    <input class="form-check-input" type="checkbox" name="can_view_all" id="can_view_all" style="width: 3.5em; height: 1.5em;">
                                </div>
                                @error('can_view_all')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select" placeholder="Choose role" required>
                                    <option value="0" selected>Office Terminal</option>
                                    <option value="1">Decision Maker</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                    placeholder="Enter first name..." value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control"
                                    placeholder="Enter middle name..." value="{{ old('middle_name') }}">
                                @error('middle_name')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                    placeholder="Enter last name..." value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="office_id" class="form-label">Office/Unit</label>
                                <select name="office_id" id="office_id" class="form-select"
                                    placeholder="Choose Office/Unit" required>
                                    <option value="">Choose Office/Unit</option>
                                    @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ old('office_id') == $office->id? 'selected':'' }}>{{ $office->office_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('office_id')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter email..." value="{{ old('email') }}" required>
                                @error('email')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-group w-100">
                                    <label for="password" class="form-label">Password</label>
                                    <input name="password" type="password" id="password" class="form-control"
                                        placeholder="Enter password..." required>
                                    @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group w-100">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input name="password_confirmation" type="password" id="password_confirmation"
                                        class="form-control" placeholder="Enter password..." required>
                                    @error('password_confirmation')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <input type="text" id="userId" name="userId" value="{{ old('userId') }}" class="d-none">
                            <input type="text" id="isEdit" name="isEdit" value="{{ old('isEdit') }}" value="0"
                                class="d-none">
                        </div>
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

        $('#myModal').on('hidden.bs.modal', function () {
            $('#first_name').val('');
            $('#middle_name').val('');
            $('#last_name').val('');
            $('#office_id').val('');
            $('#email').val('');
            $('#password').val('');
            $('#password_confirmation').val('');
            $('#isEdit').val(0);
            $('#addUpdateBtn').text('Add');
            $('.modal-title').text('Add Office');
            $('#can_create').prop('checked', false);
            $('#can_view_all').prop('checked', false);
        });

        $('.deleteBtn').on('click', function (e) {
            var id = $(this).data('bs-id');
            $('#confirmModal').modal('show');
            $('#confirmDelete').on('click', function () {
                $('#delete_form_' + id).submit()
            });
        });

        $('.edit_btn').on('click', function (e) {
            var id = $(this).attr('id');
            $.ajax({
                url: "/admin/users/" + id,
                type: 'GET',
                success: function (data) {
                    $('#userId').val(data.user.id);
                    $('#first_name').val(data.user.first_name);
                    $('#middle_name').val(data.user.middle_name);
                    $('#last_name').val(data.user.last_name);
                    $('#office_id').val(data.user.office_id);
                    $('#email').val(data.user.email);
                    $('#can_create').prop('checked', data.user.can_create);
                    $('#can_view_all').prop('checked', data.user.can_view_all);
                    $('#isEdit').val(1);

                    $('#myModal').modal('toggle');
                    $('#addUpdateBtn').text('Update');
                    $('.modal-title').text('Edit Office');
                }
            });
        });
    });

</script>
@endsection
