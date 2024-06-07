@extends('layouts.app')

@section('content')

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
        width: 15%;
    }

    td:nth-child(8) {
        width: 5%;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.completed' />
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
                                <th>DATE/TIME COMPLETED</th>
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
                                <td>{{ $documentTracking->documentDetail->name_of_client }} <br>
                                    {{ $documentTracking->documentDetail->contact != ''? '(' . $documentTracking->documentDetail->contact . ')':'' }}
                                </td>
                                <td>{{ $documentTracking->documentDetail->description }}</td>
                                <td>
                                    {{ $documentTracking->user->is_admin? 'Admin' : strtoupper($documentTracking->user->terminal->terminal_name) }}<br>-
                                    {{ Str::ucfirst(strtolower($documentTracking->user->first_name)) }}
                                    {{ Str::ucfirst(strtolower(Str::substr($documentTracking->user->middle_name, 0, 1))) }}.
                                    {{ Str::ucfirst(strtolower($documentTracking->user->last_name)) }}
                                </td>
                                <td>{{ formatDateTime($documentTracking->documentDetail->updated_at) }}</td>
                                <td>{{ $documentTracking->remark->remarks }}</td>
                                <td class="d-flex gap-1">
                                    @if (auth()->user()->is_admin)
                                        <button type="button" class="btn btn-warning text-white forwardBtn" data-bs-toggle="modal" data-bs-id={{ $documentTracking->id }}
                                        data-bs-target="#forwardModal"><i
                                            class="ri-arrow-left-right-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Change"></i></button>
                                    @endif
                                    <a class="btn btn-info"
                                        href="{{ route('web.find', 'query='.$documentTracking->documentDetail->document_code) }}"><i
                                            class="ri-route-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Track"></i></a>
                                </td>
                            </tr>
                            {{-- <x-forward-modal :$documentTracking :$terminals routeName='document.undoActionComplete'/> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="forwardModal" tabindex="-1" aria-labelledby="forwardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forwardModalLabel">Document Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST"
                enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <p class="form-label"><b>Type:</b> <span id="category"> </span></p>
                        <p class="form-label"> <b>Document Code:</b> <span id="document_code"></span></p>
                        <p class="form-label"> <b>Name of Client:</b> <span id="name_of_client"></span></p>
                        <p class="form-label"> <b>Contact No:</b> <span id="contact"></span></p>
                        <p class="form-label"> <b>Description:</b> <span id="description"></span></p>
                    </div>

                    <div class="mb-4" id="secondary-select-container">
                        <label class="form-label"><b>Forward: </b></label>
                        <select name="terminal_id" class="form-control" required
                            id="terminal_id">
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
    window.addEventListener('load',
        function () {
            if (window.jQuery) {
                $(document).ready(function () {
                    $(document).on('click', '.forwardBtn', function (e) {
                        let id = $(this).data('bs-id');
                        $.ajax({
                            url: '/document/getDocument/' + id,
                            method: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                $("#forwardModal form").attr('action', '/document/undoActionComplete/'+id);

                                $('#document_code').text(data.documents.document_code);
                                $('#category').text(data.documents.document_category_id != null? data.documents.document_category.category_name:data.documents.type);
                                $('#name_of_client').text(data.documents.name_of_client);
                                $('#contact').text(data.documents.contact);
                                $('#description').text(data.documents.description);
                                $('#remarks').val(data.documents.document_tracking.remark.remarks);
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    });
                })
            }


            function validateForm(requiredFields) {
                let isValid = true;

                requiredFields.forEach(function (selector) {
                    if ($(selector).val() === '' || $(selector).val() === null) {
                        isValid = false;
                        $(selector).addClass(
                            'is-invalid'); // Add Bootstrap's invalid class to highlight empty fields
                    } else {
                        $(selector).removeClass(
                            'is-invalid'); // Remove the invalid class if the field is not empty
                    }
                });

                return isValid;
            }

        }, false);

</script>

@endsection
