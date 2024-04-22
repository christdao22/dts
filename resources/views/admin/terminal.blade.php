{{-- Developer: Christian P. Daohog --}}
{{-- Module: Terminal Page --}}

@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
    td:nth-child(1) {
        width: 30%;
    }

    td:nth-child(2) {
        width: 30%;
    }

    td:nth-child(3) {
        width: 30%;
    }

    td:nth-child(4) {
        width: 10%;
    }

</style>
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h3 class="mb-sm-0">Terminals</h3>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DTS</a></li>
                        <li class="breadcrumb-item active">Terminals</li>
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
                            Terminal</button>
                    </div>
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th><strong>Terminal Name</strong></th>
                                <th><strong>Terminal Officer</strong></th>
                                <th><strong>Offices</strong></th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Action"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terminals as $terminal)
                            <tr>
                                <td class="text-capitalize">{{ $terminal->terminal_name }}</td>
                                <td>{{ ucwords($terminal->user->first_name) }}
                                    {{ mb_substr($terminal->user->middle_name, 0, 1) }}.
                                    {{ $terminal->user->last_name }}</td>
                                <td>
                                    <ul>
                                        @foreach ($terminal->office_terminal as $office_terminal)
                                        <li>{{ $office_terminal->office->office_name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center d-flex gap-1">
                                    <button class="btn btn-info edit_btn" id="{{ $terminal->id }}"><i
                                            class="ri-edit-2-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Edit"></i></button>
                                    <button class="btn btn-danger deleteBtn" data-bs-id={{ $terminal->id }}>
                                        <i class="ri-delete-bin-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete"></i>
                                        <form id="delete_form_{{ $terminal->id }}"
                                            action="{{ route('terminals.destroy', $terminal->id) }}" method="POST"
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
                    <p style="font-size: 25px">Delete the terminal?</p>
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
                <h5 class="modal-title secondary">Add Terminal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('terminals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-4 d-flex flex-column gap-3">
                            <x-form.input :$errors data="{
                                'input_name'  : 'terminal_name',
                                'label'       : 'Terminal Name',
                                'type'        : 'text',
                                'is_required' : true }" />

                            <div class="form-group">
                                <label class="form-label">Officer</label>
                                <select name="officer" id="officer" class="form-select" required="true">
                                    <option value="" disabled selected>Select officer...</option>
                                    @foreach ($officers as $officer)
                                    <option value="{{ $officer->id }}"
                                        {{ old('officer') == $officer->id? 'selected':'' }} >{{ $officer->first_name }}
                                        {{ mb_substr($officer->middle_name, 0, 1) }}. {{ $officer->last_name }}</option>
                                    @endforeach
                                </select>
                                @error('officer')
                                    <div class="alert alert-danger mt-2 mb-0">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="#">Select Offices</label>
                                <div class="row">
                                    @foreach ($offices as $office)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_offices[]"
                                                value="{{ $office->id }}" id="office-{{ $office->id }}">
                                            <label class="form-check-label" for="office-{{ $office->id }}">
                                                {{ $office->office_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('selected_offices')
                                <div class="alert alert-danger mt-2 mb-0">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="text" id="terminalId" name="terminalId" value="{{ old('terminalId') }}"
                                class="d-none">
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
            $('#terminal_name').val('');
            $('#officer').val('');
            $('#selected_offices').val('');
            $('#addUpdateBtn').text('Add');
            $('.modal-title').text('Add Terminal');
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
                url: "/admin/terminals/" + id,
                type: 'GET',
                success: function (data) {
                    $('#terminal_name').val(data.terminal.terminal_name);
                    $('#officer').val(data.terminal.user_id);
                    data.terminal.office_terminal.forEach(element => {
                        $(`#office-${element.office.id}`).prop('checked', true);
                    });
                    $('#terminalId').val(data.terminal.id);
                    $('#isEdit').val(data.terminal.isEdit);
                }
            });

            $('#myModal').modal('toggle');
            $('#addUpdateBtn').text('Update');
            $('.modal-title').text('Edit Terminal');
        });
    });

</script>
@endsection
