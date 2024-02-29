@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"
    integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #reader {
        width: 500px;
    }

    #result {
        text-align: center;
        font-size: 1.5rem;
    }

</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('web.find') }}" method="GET">
                        <div class="col-xl-4">
                            <div class="mb-3">
                                <label for="">Enter tracking number</label>
                                <div class="d-flex justify-content-start">
                                    <input type="text" class="form-control" name="query" placeholder="Search here....."
                                        value="">
                                    <button type="submit" id="my-button" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if (isset($documentTraces))
                    <div style="margin-top: 25px;">
                        Document Type: <b>{!! $documentDetail->document_category_id != null? $documentDetail->document_category->category_name : "Others - " . $documentDetail->type !!}</b>
                        <br>
                        Name of Client: <b>{{ $documentDetail->name_of_client }}</b>
                        <br>
                        Description: <b>{{ $documentDetail->description }}</b>
                    </div>

                    <section id="cd-timeline" class="cd-container">
                        @foreach ($documentTraces as $documentTrace)
                        <div class="cd-timeline-block">
                            <div class="cd-timeline-img cd-success {{ bgColorStatus($documentTrace->status) }}">
                                <i class="mdi mdi-adjust"></i>
                            </div> <!-- cd-timeline-img -->
                            <div class="cd-timeline-content">
                                <h3>{{ strtoupper($documentTrace->status) }}</h3>
                                <p class="mb-0 text-muted font-14">at
                                    {{ strtoupper(isset($documentTrace->user->terminal->terminal_name)? $documentTrace->user->terminal->terminal_name:'')  }}
                                </p>
                                <p class="mb-0 text-muted font-14">by
                                    {{ Str::ucfirst($documentTrace->user->first_name) }}
                                    {{ strtoupper(substr($documentTrace->user->middle_name,0,1)) }}.
                                    {{ ucfirst($documentTrace->user->last_name) }} </p>
                                <p class="mt-3">{!! isset($documentTrace->remark->remarks) ? '<b>Remarks: </b>' .
                                    $documentTrace->remark->remarks : '' !!}</p>
                                <span class="cd-date">{{ $documentTrace->created_at }}</span>
                            </div> <!-- cd-timeline-content -->
                        </div> <!-- cd-timeline-block -->
                        @endforeach

                        @if (isset($documentTracking))
                        @if ($documentTracking->status == "rejected")
                        <div class="cd-timeline-block" style="border: 10px solid blue;">
                            <div class="cd-timeline-img cd-danger">
                                <i class="mdi mdi-adjust"></i>
                            </div> <!-- cd-timeline-img -->
                            <div class="cd-timeline-content">
                                <h3>{{ strtoupper($documentTracking->status) }}</h3>
                                <p class="m-b-20 text-muted font-14">at
                                    {{ strtoupper(isset($documentTrace->user->terminal->terminal_name)? $documentTrace->user->terminal->terminal_name:'')  }}
                                </p>
                                <p class="mb-0 text-muted font-14">by
                                    {{ Str::ucfirst($documentTracking->user->first_name) }}
                                    {{ strtoupper(substr($documentTracking->user->middle_name,0,1)) }}.
                                    {{ ucfirst($documentTracking->user->last_name) }} </p>
                                <span class="cd-date">{{ $documentTracking->updated_at }}</span>
                            </div> <!-- cd-timeline-content -->
                        </div> <!-- cd-timeline-block -->
                        @elseif ($documentTracking->status == "incoming")
                        <div class="cd-timeline-block">
                            <div class="cd-timeline-img cd-danger {{ bgColorStatus($documentTracking->status) }}">
                                <i class="mdi mdi-adjust"></i>
                            </div> <!-- cd-timeline-img -->
                            <div class="cd-timeline-content">
                                <h3>{{ strtoupper($documentTracking->status) }}</h3>
                                <p class="m-b-20 text-muted font-14">at
                                    {{ strtoupper(isset($documentTracking->terminal->terminal_name)? $documentTracking->terminal->terminal_name:'')  }}
                                </p>
                                <span class="cd-date">{{ $documentTracking->updated_at }}</span>
                            </div> <!-- cd-timeline-content -->
                        </div> <!-- cd-timeline-block -->
                        @endif
                        @endif
                    </section> <!-- cd-timeline -->
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div> <!-- container-fluid -->

@endsection
