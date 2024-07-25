@extends('layouts.app')

@section('content')

<style>
    td:nth-child(1) {
        width: 10%;
    }

    td:nth-child(2) {
        width: 15%;
    }

    td:nth-child(3) {
        width: 15%;
    }

    td:nth-child(4) {
        width: 15%;
    }

    td:nth-child(5) {
        width: 15%;
    }

    td:nth-child(6) {
        width: 15%;
    }

    td:nth-child(7) {
        width: 15%;
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.received' />
                </div>
                <div class="card-body">
                    <table id="document-datatable" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <th>FROM</th>
                                <th>DESCRIPTION</th>
                                <th>DATE/TIME</th>
                                <th>REMARKS</th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
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

<!-- Forward Modal -->
<div class="modal fade" id="forwardModal" tabindex="-1" aria-labelledby="forwardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forwardModalLabel">Document Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="mb-4">
                            <p class="form-label row"><strong class="col-4">Type: </strong> <span
                                    id="f_category" class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Document Code:</strong> <span id="f_code"
                                    class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Name of Client:</strong> <span
                                    id="f_name_of_client" class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Contact No:</strong> <span id="f_contact"
                                    class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Description:</strong> <span
                                    id="f_description" class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Remarks:</strong> <span
                                    id="f_remarks" class="col-8"></span> </p>
                        </div>
                    </div>
                    <div class="mb-4" id="secondary-select-container">
                        <label class="form-label"><b>Forward: </b></label>
                        <select name="terminal_id" class="form-control" required id="terminal_id">
                            <option value="" selected="true" disabled>Select... </option>
                            @foreach ($terminals as $terminal)
                            <option value="{{ $terminal->id }}">
                                {!! strtoupper($terminal->terminal_name) !!} - {!!
                                ucfirst(strtolower($terminal->user->first_name)) !!} {!!
                                ucfirst(strtolower($terminal->user->last_name)) !!}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" cols="30" rows="5"
                            class="form-control">{{ old('remarks') }}</textarea>
                    </div>
                    <input type="text" value="incoming" name="status" class="form-control" hidden>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Forward</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="completeModal" tabindex="-1"
    aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="completeModalLabel">Complete this process?
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST"
                enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <p class="form-label row"><strong class="col-4">Type: </strong> <span
                                id="c_category" class="col-8"></span> </p>
                        <p class="form-label row"><strong class="col-4">Document Code:</strong> <span id="c_code"
                                class="col-8"></span> </p>
                        <p class="form-label row"><strong class="col-4">Name of Client:</strong> <span
                                id="c_name_of_client" class="col-8"></span> </p>
                        <p class="form-label row"><strong class="col-4">Contact No:</strong> <span id="c_contact"
                                class="col-8"></span> </p>
                        <p class="form-label row"><strong class="col-4">Description:</strong> <span
                                id="c_description" class="col-8"></span> </p>
                        <p class="form-label row"><strong class="col-4">Remarks:</strong> <span
                                id="c_remarks" class="col-8"></span> </p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" cols="30" rows="5"
                            class="form-control">{{ old('remarks') }}</textarea>
                    </div>
                    <input type="text" value="completed" name="status" class="form-control" hidden>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Complete/Release</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script defer>
    window.addEventListener('load', function () {
        initJQuery(function () {

            initDtServerSide({
                selector: "#document-datatable",
                route: "{{ route('document.dtReceived') }}",
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
                        data: 'date_time',
                        name: 'DATE/TIME'
                    },
                    {
                        data: 'remarks',
                        name: 'REMARKS'
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
                    d.user = $('#filterUser').val();
                }
            });

            initClick('.forwardBtn, .completeBtn', function () {
                var id = $(this).data('bs-id');
                var indi = $(this).hasClass('forwardBtn')? '#f':'#c'
                var modalSel = $(this).hasClass('forwardBtn')? '#forwardModal' : '#completeModal'

                $.ajax({
                    url: '/document/getDocument/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $(indi + '_code').text(data.documents.document_code);
                        $(indi + '_name_of_client').text(data.documents.name_of_client);
                        $(indi + '_contact').text(data.documents.contact !=
                            null ? data.documents.contact : 'N/A');
                        $(indi + '_description').text(data.documents
                            .description);
                        $(indi + '_category').text(data.documents
                            .document_category_id !== null ?
                            data.documents.document_category.category_name :
                            'Others - ' + data.documents.type);
                        $(indi + '_remarks').text(data.documents
                            .document_tracking.remark.remarks);

                        $(modalSel + " form").attr("action",
                            `/document/update/${data.documents.id}`);

                        $(modalSel).modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });

            initExcerpt();
        });
    }, false);

</script>

</script>
@endsection
