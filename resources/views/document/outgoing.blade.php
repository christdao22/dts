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
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <th>NAME OF CLIENT</th>
                                <th>DESCRIPTION</th>
                                <th>FORWARDED BY</th>
                                <th>TO</th>
                                <th>DATE CREATED</th>
                                <th>DATE FORWARDED</th>
                                <th>REMARKS</th>
                                <th><i class=" ri-settings-2-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Action"></i></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $latestCodes = [];
                            @endphp
                            @foreach ($documentTrackings as $documentTracking)
                            @php
                                $isLatest = !isset($latestCodes[$documentTracking->documentDetail->document_code]);
                                if ($isLatest) {
                                    $latestCodes[$documentTracking->documentDetail->document_code] = $documentTracking;
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $documentTracking->documentDetail->document_code }}</strong></td>
                                <td>{!! $documentTracking->documentDetail->document_category_id != null?
                                    $documentTracking->documentDetail->document_category->category_name : "<b>Others: </b>" . $documentTracking->documentDetail->type
                                    !!}</td>
                                <td>{{ $documentTracking->documentDetail->name_of_client }} <br> {{ $documentTracking->documentDetail->contact != ''? '(' . $documentTracking->documentDetail->contact . ')':'' }}</td>
                                <td>{{ $documentTracking->documentDetail->description }}</td>
                                <td>{{ strtoupper($documentTracking->user->terminal->terminal_name) }}<br>-
                                    {{ Str::ucfirst(strtolower($documentTracking->user->first_name)) }}
                                    {{ Str::ucfirst(strtolower(Str::substr($documentTracking->user->middle_name, 0, 1))) }}.
                                    {{ Str::ucfirst(strtolower($documentTracking->user->last_name)) }}</td>
                                <td>{{ strtoupper($documentTracking->terminal->terminal_name) }}</td>
                                <td>{{ formatDateTime($documentTracking->documentDetail->created_at) }}</i></td>
                                <td>{{ formatDateTime($documentTracking->created_at) }}</i></td>
                                <td>{{ $documentTracking->remark->remarks }}</i></td>
                                <td class="d-flex gap-2">
                                     {{-- && $documentTrackings->first()->id == $documentTracking->id --}}
                                    @if ($documentTracking->documentDetail->documentTracking->status == 'incoming' && $isLatest)
                                        <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal"
                                        data-bs-target="#forwardModal-{{ $documentTracking->id }}"><i
                                            class="ri-arrow-left-right-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Change"></i></button>
                                    @endif
                                    <a class="btn btn-info"
                                        href="{{ route('web.find', 'query='.$documentTracking->documentDetail->document_code) }}"><i
                                            class="ri-route-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Track"></i></a>
                                </td>
                            </tr>
                            <x-forward-modal :$documentTracking :$terminals routeName='document.changeForward'/>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection




