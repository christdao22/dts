@extends('layouts.app')

@section('content')
<style>
    td:nth-child(1) {
        width: 10%;
    }

    td:nth-child(2) {
        width: 10%;
    }

    td:nth-child(3) {
        width: 15%;
    }

    td:nth-child(4) {
        width: 15%;
    }

    td:nth-child(5) {
        width: 10%;
    }

    td:nth-child(6) {
        width: 15%;
    }

    td:nth-child(7) {
        width: 10%;
    }

    td:nth-child(8) {
        width: 15%;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.create' />
                </div>
                <div class="card-body">
                    @if (Auth::user()->is_admin == 1 || Auth::user()->can_create == 1)
                    <div class="d-flex flex-row-reverse gap-2">
                        <button class="btn btn-info mb-3 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal"><i class="ri-add-box-line"></i> Create Documents</button>
                        <form action="{{ route('document.generateCode') }}" method="get">
                            @csrf
                            <button title="This button generates a code for you to obtain first and then add it when you're ready to create a document."
                                class="btn btn-warning mb-3 d-flex align-items-center gap-2 text-light"><i class="ri-dashboard-line"></i>
                                Generate Code</button>
                        </form>
                        <button class="btn btn-success mb-3 d-flex align-items-center gap-2" data-bs-toggle="modal"
                            data-bs-target="#guestDocumentModal"><i class="ri-user-add-line"></i> Document Code</button>
                    </div>
                    <div class="modal" id="createModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title secondary">Create Tracking</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('document.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <!-- Dropdown Field -->
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="mb-4 d-flex flex-column gap-3">
                                                <x-form.input :$errors data="{
                                                    'input_name'  : 'document_code',
                                                    'label'       : 'Document Code',
                                                    'type'        : 'text',
                                                    'is_required' : false,
                                                    'placeholder' : '******',
                                                    'small'        : 'Leave empty if you dont have document code'}" />

                                                <div class="form-group">
                                                    <label class="form-label" for="category_id"><b>Document Type
                                                        </b></label>
                                                    <select name="category_id" id="category_id" class="form-select"
                                                        required>
                                                        <option value="" disabled selected>Select document type</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id') == $category->id? 'selected':'' }}>
                                                            {{ $category->category_name }}</option>
                                                        @endforeach
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>

                                                <x-form.input :$errors data="{
                                                    'input_name'  : 'type',
                                                    'label'       : 'Other Document Type',
                                                    'type'        : 'text',
                                                    'is_required' : false,
                                                    'is_hidden'   : true }" />

                                                <x-form.input :$errors data="{
                                                    'input_name'  : 'name_of_client',
                                                    'label'       : 'Name Of Client',
                                                    'type'        : 'text',
                                                    'is_required' : true }" />

                                                <x-form.input :$errors data="{
                                                    'input_name'  : 'contact',
                                                    'label'       : 'Contact No.',
                                                    'type'        : 'text',
                                                    'is_required' : false,
                                                    'placeholder' : '09*********'}" />

                                                <div class="form-group">
                                                    <label class="form-label" for="terminal"><b>Recipient </b></label>
                                                    <select name="terminal" id="terminal" class="form-select" required>
                                                        <option value="" disabled selected>Select recipient
                                                        </option>
                                                        @foreach ($terminals as $terminal)
                                                        <option value="{{ $terminal->id }}"
                                                            {{ old('terminal') == $terminal->id? 'selected':'' }}>
                                                            {!! strtoupper($terminal->terminal_name) !!} - {!!
                                                            ucfirst(strtolower($terminal->user->first_name)) !!} {!!
                                                            ucfirst(strtolower($terminal->user->last_name)) !!}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" class="form-control"
                                                        cols="30" rows="4" value="{{ old('description') }}"></textarea>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label" for="remarks">Remarks</label>
                                                    <textarea name="remarks" id="remarks" cols="30" rows="5"
                                                        class="form-control">{{ old('remarks') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-info">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal " id="guestDocumentModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title secondary">Search Document Code</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <table id="datatable-codes"
                                        class="col-md-10 table table-striped table-bordered dt-responsive"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>CODE</th>
                                                <th>Client's Name</th>
                                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Action"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($document_codes as $code)
                                            <tr>
                                                <td><strong>{{ $code->document_code }}</strong></td>
                                                <td><strong class="text-uppercase">{{ $code->name_of_client }}</strong>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <a class="btn btn-primary editCodeModal"
                                                            data-bs-id='{{ $code->id }}' data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Edit"><i
                                                                class="ri-edit-line"></i></a>
                                                        <button class="btn btn-danger codeDeleteBtn"
                                                            data-bs-id={{ $code->id }}><i
                                                                class="ri-delete-bin-line"></i>
                                                            <form id="code_delete_form_{{ $code->id }}"
                                                                action="{{ route('document.deleteGuestCode', $code->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                        </button>
                                                        <a class="ri ri-printer-fill btn btn-warning"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Print"
                                                            href="{{ route('printPDF', $code->document_code ) }}"
                                                            target="_blank"></a>


                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="">
                        <table id="document-datatable"
                            class="col-md-10 table table-striped table-bordered dt-responsive"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>CODE</th>
                                    <th>TYPE</th>
                                    <th>FROM</th>
                                    <th>DESCRIPTION</th>
                                    <th>TO</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>
                                    <th><i class="ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Action"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="modal" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center ">
                        <i class="ri-error-warning-line text-danger" style="font-size: 100px;"></i>
                        <p style="font-size: 25px">Delete the document?</p>
                    </div>
                    <div class="d-flex flex-column gap-2 mt-4">
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title secondary">Document Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-group mb-3">
                                <label class="form-label" for="category_id"><b>Document Type </b></label>
                                <select name="category_id" id="editCategory_id" class="form-select" required>
                                    <option value="" disabled selected>Select document type</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id? 'selected':'' }}>
                                        {{ $category->category_name }}</option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Other Document Type</b></label>
                                <input type="text" name="type" id="editType" value="{{ old('type') }}"
                                    class="form-control hidden">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Document Code</label>
                                <input type="text" id="editCode" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name Of Client</label>
                                <input id="editName_of_client" type="text" class="form-control" required
                                    name="name_of_client" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contact No.</label>
                                <input id="editContact" type="text" class="form-control" placeholder="09*********"
                                    name="contact" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="editDescription" cols="30" rows="5" class="form-control"
                                    name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="editTerminal_id"><b>Recipient </b></label>
                                <select name="terminal_id" id="editTerminal_id" class="form-select" required>
                                    <option value="" disabled selected>Select recipient </option>
                                    @foreach ($terminals as $terminal)
                                    <option value="{{ $terminal->id }}"
                                        {{ old('terminal_id') == $terminal->id? 'selected':'' }}>
                                        {{ $terminal->terminal_name }} - {{ $terminal->user->first_name }}
                                        {{ $terminal->user->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" value="received" name="status" class="form-control" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="updateBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="addDocumentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title secondary">Document Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="codeEditForm" action="#" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">

                            <p>Document Code: <strong id="add_document_code"></strong></p>
                            <div class="mb-3">
                                <label class="form-label" for="add_name_of_client">Name Of Client</label>
                                <input id="add_name_of_client" name="add_name_of_client" type="text"
                                    class="form-control" required name="add_name_of_client" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="add_contact">Contact No.</label>
                                <input id="add_contact" type="text" class="form-control" name="add_contact" />
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="add_category_id"><b>Document Type </b></label>
                                <select name="add_category_id" id="add_category_id" class="form-select" required>
                                    <option value="" disabled selected>Select document type</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('add_category_id') == $category->id? 'selected':'' }}>
                                        {{ $category->category_name }}</option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label" for="add_type"><b>Other Document Type</b></label>
                                <input type="text" name="add_type" id="add_type" value="{{ old('add_type') }}"
                                    class="form-control hidden">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="add_terminal_id"><b>Recipient </b></label>
                                <select name="add_terminal_id" id="add_terminal_id" class="form-select" required>
                                    <option value="" disabled selected>Select recipient </option>
                                    @foreach ($terminals as $terminal)
                                    <option value="{{ $terminal->id }}"
                                        {{ old('add_terminal_id') == $terminal->id? 'selected':'' }}>
                                        {{ $terminal->terminal_name }} - {{ $terminal->user->first_name }}
                                        {{ $terminal->user->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="add_description">Description</label>
                                <textarea name="add_description" id="add_description" cols="30" rows="5"
                                    class="form-control">{{ old('add_description') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="add_remarks">Remarks</label>
                                <textarea name="add_remarks" id="add_remarks" cols="30" rows="5"
                                    class="form-control">{{ old('add_remarks') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="codeUpdateBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script defer>
    window.addEventListener('load', function () {
        initJQuery(function () {
            if ("{{ session('addDocumentModal') }}" == 'true') {
                $('#guestDocumentModal').modal('show')
            }

            var table = initializeDataTable('#datatable-codes');
            handleModalCategoryChange('#createModal', '#category_id', '#type');
            handleModalCategoryChange('#editModal', '#editCategory_id', '#editType');
            handleModalCategoryChange('#addDocumentModal', '#add_category_id', '#add_type');

            initDtServerSide({
                selector: "#document-datatable",
                route: "{{ route('document.dtAllDocuments') }}",
                columns: [{
                        data: 'document_code',
                        name: 'CODE'
                    },
                    {
                        data: 'category',
                        name: 'TYPE'
                    },
                    {
                        data: 'from',
                        name: 'FROM'
                    },
                    {
                        data: 'description',
                        name: 'DESCRIPTION'
                    },
                    {
                        data: 'terminal',
                        name: 'TO'
                    },
                    {
                        data: 'created_at',
                        name: 'DATE'
                    },
                    {
                        data: 'status',
                        name: 'STATUS'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                additionalData: function (d) {
                    d.type = $('#filterType').val();
                    d.date_from = $('#filterDateFrom').val();
                    d.date_to = $('#filterDateTo').val();
                    d.status = $('#filterStatus').val();
                }
            });

            initDtDelete('.deleteBtn', '#delete_form_')
            initDtDelete('.codeDeleteBtn', '#code_delete_form_')

            initClick('.editButton', function () {
                let id = $(this).data('bs-id');
                $.ajax({
                    url: '/document/getDocument/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#editCategory_id').val(data.documents
                            .document_category_id !== null ?
                            data.documents.document_category_id : 'others');
                        $('#editType').val(data.documents.type);
                        $('#editCode').val(data.documents.document_code);
                        $('#editName_of_client').val(data.documents
                            .name_of_client);
                        $('#editDescription').val(data.documents.description);
                        $('#editTerminal_id').val(data.documents.terminal_id);
                        $('#editContact').val(data.documents.contact);
                        $('#editType').parent().toggleClass('d-none', data
                            .documents
                            .document_category_id !== null);
                        $('#editModal').modal('show');
                        $("#editForm").attr("action",
                            `/document/update-edit/${id}`);

                        $('#updateBtn').off('click').on('click',
                            function () {
                                if (validateForm(['#add_name_of_client',
                                        '#add_category_id',
                                        '#add_terminal_id'
                                    ])) {

                                    $("#editForm").submit();
                                }
                            });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            })
            initClick('.editCodeModal', function (e) {

                let id = $(this).data('bs-id');
                $.ajax({
                    url: '/document/getDocument/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#add_document_code').text(data.documents
                            .document_code);
                        $('#add_name_of_client').val(data.documents
                            .name_of_client);
                        $('#add_contact').val(data.documents.contact);
                        $('#add_description').val(data.documents.description);
                        $('#guestDocumentModal').modal('hide');
                        $('#addDocumentModal').modal('show');
                        $("#codeEditForm").attr("action",
                            `/document/storeGuestCreate/${id}`);
                        $('#codeUpdateBtn').off('click').on('click',
                            function () {
                                if (validateForm(['#add_name_of_client',
                                        '#add_category_id',
                                        '#add_terminal_id'
                                    ])) {
                                    $("#codeEditForm").submit();
                                }
                            });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            })

            initExcerpt();
        });
    }, false);

</script>
@endsection
