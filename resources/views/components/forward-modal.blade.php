<div class="modal fade" id="forwardModal-{{ $documentTracking->id }}" tabindex="-1"
    data-bs-id="{{ $documentTracking->id }}" aria-labelledby="forwardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forwardModalLabel">Document Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('document.undoActionComplete', $documentTracking->documentDetail->id) }}" method="POST"
                enctype="multipart/form-data">
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
                            class="form-control" value="{{ $documentTracking->remark->remarks }}">{{ $documentTracking->remark->remarks }}</textarea>
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
