@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
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
        width: 10%;
    }

    td:nth-child(7) {
        width: 10%;
    }

    td:nth-child(8) {
        width: 10%;
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.incoming'/>
                </div>
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
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
                            @foreach ($documentTrackings as $documentTracking)
                            <tr>
                                <td><strong>{{ $documentTracking->documentDetail->document_code }}</strong></td>
                                <td>{!! $documentTracking->documentDetail->document_category_id != null?
                                    $documentTracking->documentDetail->document_category->category_name : "<b>Others:
                                    </b>" . $documentTracking->documentDetail->type
                                    !!}</td>
                                <td>{{ $documentTracking->documentDetail->name_of_client }} <br> {{ $documentTracking->documentDetail->contact != ''? '(' . $documentTracking->documentDetail->contact . ')':'' }}</td>

                                <td>{{ $documentTracking->documentDetail->description }}</td>
                                <td>
                                    {{ $documentTracking->user->is_admin? 'Admin' : strtoupper($documentTracking->user->terminal->terminal_name) }}<br>-
                                    {{ Str::ucfirst(strtolower($documentTracking->user->first_name)) }}
                                    {{ Str::ucfirst(strtolower(Str::substr($documentTracking->user->middle_name, 0, 1))) }}.
                                    {{ Str::ucfirst(strtolower($documentTracking->user->last_name)) }}
                                </td>
                                <td>{{ $documentTracking->documentDetail->created_at }}</td>
                                <td>{{ $documentTracking->remark->remarks }}</td>
                                <td class="d-flex gap-1">
                                    <button class="ri ri-eye-fill btn btn-warning" data-bs-toggle="modal"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Show"
                                        data-bs-target="#myModal-{{ $documentTracking->id }}"></button>
                                    <a class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Track"
                                        href="{{ route('web.find', 'query='.$documentTracking->documentDetail->document_code) }}"><i
                                            class="ri-route-line"></i></a>
                                    <button class="btn btn-success receivedBtn" data-bs-id="{{ $documentTracking->id }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Receive"><i
                                            class=" ri-mail-add-line"></i></button>
                                </td>
                                <div class="modal" id="myModal-{{ $documentTracking->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title secondary">Document Details</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('document.update' ,$documentTracking->id) }}"
                                                id="receivedForm_{{ $documentTracking->id }}" method="POST"
                                                enctype="multipart/form-data">
                                                @method('PATCH')
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <div class="mb-4">
                                                            <p class="form-label"><b>Type:</b>
                                                                {!!
                                                                isset($documentTracking->documentDetail->document_category)?
                                                                $documentTracking->documentDetail->document_category->category_name:"Others
                                                                - " . $documentTracking->documentDetail->type !!}</p>
                                                            <p class="form-label"> <b>Document Code:</b>
                                                                {{ $documentTracking->documentDetail->document_code }}
                                                            </p>
                                                            <p class="form-label"> <b>Name of Client:</b>
                                                                {{ $documentTracking->documentDetail->name_of_client }}
                                                            </p>
                                                            <p class="form-label"> <b>Contact No:</b>
                                                                {{ $documentTracking->documentDetail->contact }} </p>
                                                            <p class="form-label"> <b>Description:</b>
                                                                {{ $documentTracking->documentDetail->description }}</p>
                                                            <p class="form-label"> <b>Remarks:</b>
                                                                {{ $documentTracking->remark->remarks }} </p>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<script>
    $(document).ready(function () {
        $('.receivedBtn').on('click', function () {
            const id = $(this).data('bs-id');
            $(`#receivedForm_${id}`).submit();
        });
    });

</script>
@endsection
