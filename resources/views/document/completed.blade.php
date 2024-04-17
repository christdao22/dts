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
                    <x-filter :$filters route='document.completed'/>
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
                                <th><i class=" ri-settings-2-line"  data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Action"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($documentTrackings as $documentTracking)
                            <tr>
                                <td><strong>{{ $documentTracking->documentDetail->document_code }}</strong></td>
                                <td>{!! $documentTracking->documentDetail->document_category_id != null?
                                    $documentTracking->documentDetail->document_category->category_name : "<b>Others: </b>" . $documentTracking->documentDetail->type
                                    !!}</td>
                                <td>{{ $documentTracking->documentDetail->name_of_client }} <br> {{ $documentTracking->documentDetail->contact != ''? '(' . $documentTracking->documentDetail->contact . ')':'' }}</td>
                                <td>{{ $documentTracking->documentDetail->description }}</td>
                                <td>
                                    {{ $documentTracking->user->is_admin? 'Admin' : strtoupper($documentTracking->user->terminal->terminal_name) }}<br>-
                                    {{ Str::ucfirst(strtolower($documentTracking->user->first_name)) }}
                                    {{ Str::ucfirst(strtolower(Str::substr($documentTracking->user->middle_name, 0, 1))) }}.
                                    {{ Str::ucfirst(strtolower($documentTracking->user->last_name)) }}</td>
                                <td>{{ $documentTracking->documentDetail->updated_at }}</td>
                                <td>{{ $documentTracking->remark->remarks }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('web.find', 'query='.$documentTracking->documentDetail->document_code) }}"><i class="ri-route-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Track"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

</div>
@endsection
