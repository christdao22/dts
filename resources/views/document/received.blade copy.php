@extends('layouts.app')

@section('content')

<style>
    td:nth-child(1) {
        width: 10%;
    }

    td:nth-child(2) {
        width: 20%;
    }

    td:nth-child(3) {
        width: 20%;
    }

    td:nth-child(4) {
        width: 20%;
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

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header w-100">
                    <x-filter :$filters route='document.received' />
                </div>
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <th>NAME OF CLIENT</th>
                                <th>DESCRIPTION</th>
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
                                <td>{{ $documentTracking->documentDetail->name_of_client }} <br>
                                    {{ $documentTracking->documentDetail->contact != ''? '(' . $documentTracking->documentDetail->contact . ')':'' }}
                                </td>
                                <td>{{ $documentTracking->documentDetail->description }}</td>
                                <td>{{ formatDateTime($documentTracking->documentDetail->created_at) }}</td>
                                <td>{{ $documentTracking->remark->remarks }}</td>
                                <td class="d-flex gap-1">


                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#forwardModal-{{ $documentTracking->id }}"><i
                                            class="ri ri-eye-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Show"></i></button>
                                    <a class="btn btn-info"
                                        href="{{ route('web.find', 'query='.$documentTracking->documentDetail->document_code) }}"><i
                                            class="ri-route-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Track"></i></a>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#completeModal-{{ $documentTracking->id }}"><i
                                            class=" ri-check-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Complete"></i></button>
                                </td>
                                <!-- Forward Modal -->
                                <div class="modal fade" id="forwardModal-{{ $documentTracking->id }}" tabindex="-1"
                                    data-bs-id="{{ $documentTracking->id }}" aria-labelledby="forwardModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="forwardModalLabel">Document Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('document.update' ,$documentTracking->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @method('PATCH')
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <p class="form-label"><b>Type:</b>
                                                            {!! $documentTracking->documentDetail->document_category_id
                                                            != null?
                                                            $documentTracking->documentDetail->document_category->category_name
                                                            : "<b>Others - </b>" .
                                                            $documentTracking->documentDetail->type
                                                            !!}</p>
                                                        <p class="form-label"> <b>Document Code:</b>
                                                            {{ $documentTracking->documentDetail->document_code }}</p>
                                                        <p class="form-label"> <b>Name of Client:</b>
                                                            {{ $documentTracking->documentDetail->name_of_client }} </p>
                                                        <p class="form-label"> <b>Contact No:</b>
                                                            {{ $documentTracking->documentDetail->contact }}</p>
                                                        <p class="form-label"> <b>Description:</b>
                                                            {{ $documentTracking->documentDetail->description }}</p>
                                                        <p class="form-label"> <b>Remarks:</b>
                                                            {{ $documentTracking->remark->remarks }} </p>
                                                    </div>

                                                    <div class="mb-4" id="secondary-select-container">
                                                        <label class="form-label"><b>Forward: </b></label>
                                                        <select name="terminal_id" class="form-control" required
                                                            id="terminal_{{ $documentTracking->id }}">
                                                            <option value="" selected="true" disabled>Select...
                                                            </option>
                                                            @foreach ($terminals as $terminal)
                                                            <option value="{{ $terminal->id }}">
                                                                {{ $terminal->terminal_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label" for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" cols="30" rows="5"
                                                            class="form-control">{{ old('remarks') }}</textarea>
                                                    </div>
                                                    <input type="text" value="incoming" name="status"
                                                        class="form-control" hidden>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Forward</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Confirm Complete Modal --}}
                                <div class="modal fade" id="completeModal-{{ $documentTracking->id }}" tabindex="-1"
                                    aria-labelledby="completeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="completeModalLabel">Complete this process?
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('document.update' ,$documentTracking->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @method('PATCH')
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <p class="form-label"><b>Type:</b>
                                                            {{ $documentTracking->documentDetail->type }}</p>
                                                        <p class="form-label"> <b>Document Code:</b>
                                                            {{ $documentTracking->documentDetail->document_code }}
                                                        </p>
                                                        <p class="form-label"> <b>Name of Client:</b>
                                                            {{ $documentTracking->documentDetail->name_of_client }}
                                                        </p>
                                                        <p class="form-label"> <b>Description:</b>
                                                            {{ $documentTracking->documentDetail->description }}</p>
                                                        <p class="form-label"> <b>Remarks:</b>
                                                            {{ $documentTracking->remark->remarks }} </p>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label" for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" cols="30" rows="5"
                                                            class="form-control">{{ old('remarks') }}</textarea>
                                                    </div>
                                                    <input type="text" value="completed" name="status"
                                                        class="form-control" hidden>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit"
                                                            class="btn btn-success">Complete/Release</button>
                                                    </div>
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
        $('.modal').on('shown.bs.modal', function () {
            const id = $(this).attr('data-bs-id');
            $(`#is_check_by_dm_${id}`).on('change', function () {
                let checked = $(this).prop("checked");
                $.ajax({
                    url: '/admin/getTerminals/' + checked,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $(`#terminal_${id}`).empty().append(
                            '<option value="" disabled selected>Select recipient</option>'
                        )
                        $.each(data.terminal, function (index, item) {
                            $(`#terminal_${id}`).append(
                                `<option value="${item.id}">${item.terminal_name}</option>`
                            );
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        })
    });

</script>
@endsection
