@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    td:nth-child(1) {
        width: 10%;
    }

    td:nth-child(2) {
        width: 5%;
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
        width: 20%;
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.incoming' />
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
                                <th>FORWARDED BY</th>
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

<div class="modal" id="showModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title secondary">Document Details</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="mb-4">
                            <p class="form-label"><strong>Type: </strong> <span id="category"></span> </p>
                            <p class="form-label"><strong>Document Code:</strong> <span id="code"></span> </p>
                            <p class="form-label"><strong>Name of Client:</strong> <span id="name_of_client"></span> </p>
                            <p class="form-label"><strong>Contact No:</strong> <span id="contact"></span> </p>
                            <p class="form-label"><strong>Description:</strong> <span id="description"></span> </p>
                            <p class="form-label"><strong>Remarks:</strong> <span id="remarks"></span> </p>
                        </div>
                        <div class="mb-4">
                            <input type="text" value="received" name="status"
                                class="form-control" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Received</button>
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
                route: "{{ route('document.dtIncoming') }}",
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
                        name: 'FORWARDED BY'
                    },
                    {
                        data: 'created_at',
                        name: 'DATE'
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

            initExcerpt();

            initClick('.viewBtn', function () {
                var id = $(this).data('bs-id');

                $.ajax({
                    url: '/document/getDocument/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#category').text(data.documents
                            .document_category_id !== null ?
                            data.documents.document_category.category_name : 'Others - ' + data.documents.type);
                        $('#code').text(data.documents.document_code);
                        $('#name_of_client').text(data.documents.name_of_client);
                        $('#contact').text(data.documents.contact);
                        $('#description').text(data.documents.description);
                        $('#remarks').text(data.documents.document_tracking.remark.remarks);

                        $("#showModal form").attr("action", `/document/update/${data.documents.id}`);

                        $('#showModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });

            initClick('.receivedBtn', function() {
                const id = $(this).data('bs-id');
                $("#showModal form").attr("action", `/document/update/${id}`)
                $("#showModal form").submit();
            });
        });
    }, false);
</script>
@endsection
