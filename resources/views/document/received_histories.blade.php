@extends('layouts.app')

@section('content')
<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
    td:nth-child(1) {
        width: 9%;
    }

    td:nth-child(2) {
        width: 15%;
    }

    td:nth-child(3) {
        width: 15%;
    }

    td:nth-child(4) {
        width: 13%;
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
        width: 13%;
    }

    td:nth-child(9) {
        width: 5%;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.receivedHistory'/>
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
                                <th>DATE CREATED</th>
                                <th>DATE RECEIVED</th>
                                <th>REMARKS</th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Action"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<script defer>
    window.addEventListener('load', function () {
        initJQuery(function () {
            initDtServerSide({
                selector: "#document-datatable",
                route: "{{ route('document.dtReceivedHistory') }}",
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
                        data: 'created_at',
                        name: 'DATE'
                    },
                    {
                        data: 'received_at',
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
        });
    }, false);
</script>
@endsection
