@extends('layouts.app')

@section('content')
{{-- <div class="container-fluid">

   <h1>This is admin</h1>

</div> --}}
<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
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
        width: 10%;
    }

    td:nth-child(5) {
        width: 10%;
    }

    td:nth-child(6) {
        width: 10%;
    }

    td:nth-child(7) {
        width: 10%;
    }

    td:nth-child(8) {
        width: 10%;
    }

    td:nth-child(9) {
        width: 10%;
    }

    td:nth-child(5) {
        width: 10%;
    }

</style>
<div class="container-fluid">
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.outgoing'/>
                </div>
                <div class="card-body">
                    <table id="document-datatable" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <th>NAME OF CLIENT</th>
                                <th>DESCRIPTION</th>
                                <th>TO</th>
                                <th>DATE CREATED</th>
                                <th>DATE FORWARDED</th>
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

<div class="modal fade" id="forwardModal" tabindex="-1"  aria-labelledby="forwardModalLabel" aria-hidden="true">
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
                                    id="category" class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Document Code:</strong> <span id="code"
                                    class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Name of Client:</strong> <span
                                    id="name_of_client" class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Contact No:</strong> <span id="contact"
                                    class="col-8"></span> </p>
                            <p class="form-label row"><strong class="col-4">Description:</strong> <span
                                    id="description" class="col-8"></span> </p>
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
                            class="form-control"></textarea>
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


<script defer>
    window.addEventListener('load', function () {
        initJQuery(function () {

            initDtServerSide({
                selector: "#document-datatable",
                route: "{{ route('document.dtOutgoing') }}",
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
                        name: 'NAME OF CLIENT'
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
                        name: 'DATE CREATED'
                    },
                    {
                        data: 'forwarded_at',
                        name: 'DATE FORWARDED'
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

            initClick('.forwardBtn', function () {
                var id = $(this).data('bs-id');

                $.ajax({
                    url: '/document/getDocument/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var docs = data.documents;
                        $('#code').text(docs.document_code);
                        $('#name_of_client').text(docs.name_of_client);
                        $('#contact').text(docs.contact !=
                            null ? docs.contact : 'N/A');
                        $('#description').text(docs.description);
                        $('#category').text(docs.document_category_id !== null ?
                            docs.document_category.category_name :
                            'Others - ' + docs.type);
                        $('#remarks').val(docs.document_tracking.remark.remarks);

                        $("#forwardModal form").attr("action",
                            `/document/changeForward/${docs.id}`);

                        $('#forwardModal').modal('show');
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
@endsection




