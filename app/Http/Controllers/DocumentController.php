<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use App\Models\DocumentDetail;
use App\Models\User;
use App\Models\DocumentTrace;
use App\Models\DocumentTracking;
use App\Models\Outgoing;
use App\Models\ReceivedHistory;
use App\Models\Remark;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Terminal;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function find(Request $request){
      try {
            $search_text = $request->input('query'); 

            $documentDetail = DocumentDetail::with('document_category')->where('document_code',$search_text)->first();
            if($documentDetail){
                $documentTraces = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user.terminal','documentDetail', 'remark')->get();
                $documentLatest = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user.terminal','documentDetail', 'remark')->latest()->first();
                // $data = $documentLatest->user->terminal->id;
                // throw new Exception('Something went wrong');
                $documentTracking = DocumentTracking::where('document_detail_id',$documentDetail->id)->with('user','documentDetail', 'terminal', 'remark')->first();
                // if($data == $documentTracking->terminal->id && $documentTracking->status == "incoming"){
                //     $documentTracking =null;
                // }
                // dd($documentTraces);
            }else {
                Alert::error('oppss', 'No record found...');
                return view('document.tracked');
            }
            // dd($documentTracking);
      } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('document.tracked');
      }
       return view('document.tracked',compact('documentTraces','documentTracking','documentDetail'));
    }

    function find2(Request $request){
          try {
                $search_text = $request->input('query');

                $documentDetail = DocumentDetail::where('document_code',$search_text)->first();

                if($documentDetail){
                    $documentTraces = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user.terminal','documentDetail')->get();
                    $documentLatest = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user.terminal','documentDetail')->latest()->first();
                    $data = $documentLatest->user->terminal->id;
                    // throw new Exception('Something went wrong');
                    $documentTracking = DocumentTracking::where('document_detail_id',$documentDetail->id)->with('user','documentDetail', 'terminal')->first();
                    if($data == $documentTracking->terminal->id && $documentTracking->status == "incoming"){
                        $documentTracking =null;
                    }
                }else {
                    Alert::error('oppss', 'No record found...');
                    return view('tracked');
                }
          } catch (\Exception $e) {
                Alert::error('Something went wrong', 'Please try again...');
                return view('tracked');
          }

        //   dd($documentTraces);
           return view('tracked',compact('documentTraces','documentTracking','documentDetail'));
        }


        public function dts(){
        return view('tracked');
    }

    public function createPDF(string $id){
        $data = DocumentDetail::where('id',$id)->first();

        return view('document.pdf_view',compact('data'));
        // $pdf = Pdf::loadView('document.pdf_view', compact('data'))->setPaper('a4', 'landscape')->setWarnings(false);

        // return $pdf->stream();
    }

    public function allDocuments(Request $request)
    {
       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();

        $documentTrackings = DocumentTracking::where('terminal_id', auth()->user()->office_id)
        ->where('status', 'received')
        ->with('user','documentDetail');

        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->get();

        return view('document.received',compact('documentTrackings', 'filters'));
    }

    public function received(Request $request)
    {
        $documentTrackings = [];
       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();

        $terminals = Terminal::whereHas('user', function($query) {
            return $query->where('is_active', 1);
        })->orderBy('terminal_name')->get();

        $terminal = Terminal::with('user')->where('user_id', auth()->user()->id)->first();
        if($terminal != null){
            $documentTrackings = DocumentTracking::where('terminal_id', $terminal->id)
            ->where('status', 'received')
            ->with('user','documentDetail', 'remark');

            $documentTrackings = $this->filter($request, $documentTrackings);
            $documentTrackings = $documentTrackings->orderBy('created_at', 'desc')->get();
        }

        return view('document.received',compact('documentTrackings', 'terminals', 'filters'));
    }

    public function incoming(Request $request)
    {
       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();
        $documentTrackings = [];
        $terminal = Terminal::where('user_id', auth()->user()->id)->first();

        if($terminal==null) return view('document.incoming',compact('documentTrackings'));

        $documentTrackings = DocumentTracking::where('terminal_id', $terminal->id)
            ->where('status', 'incoming')
            ->with('user','documentDetail.document_category', 'remark');

        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->get();

        

        return view('document.incoming',compact('documentTrackings', 'filters'));
    }

    public function receivedHistory(Request $request)
    {
       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();
        $receivedHistories = ReceivedHistory::with('user.terminal','documentDetail.document_category', 'remark');
        $receivedHistories = $this->filter($request, $receivedHistories);
        $receivedHistories = $receivedHistories->get();

        $receivedHistories = $receivedHistories->filter(function ($r){
            return $r->user->office_id == auth()->user()->office_id;
        });

        return view('document.received_histories',compact('receivedHistories', 'filters'));
    }

    public function outgoing(Request $request)
    {
       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();
        $documentTrackings = Outgoing::with('user.terminal','documentDetail', 'terminal', 'remark')->where('user_id', auth()->user()->id);
        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->orderBy('created_at', 'desc')->get();

        $documentTrackings->filter(function ($o){
            return $o->user->office_id == auth()->user()->office_id;
        });
        return view('document.outgoing',compact('documentTrackings', 'filters'));
    }

    public function completed(Request $request)
    {
        $documentTrackings = [];
        $terminals = Terminal::get();
        $terminal = Terminal::with('user')->where('user_id', auth()->user()->id)->first();
        if($terminal != null){
            $documentTrackings = DocumentTracking::where('terminal_id', $terminal->id)
            ->where('status', 'completed')
            ->with('remark', 'documentDetail', 'user');

            $documentTrackings = $this->filter($request, $documentTrackings);
            $documentTrackings = $documentTrackings->orderBy('updated_at', 'desc')->get();
        }

       // ** use to get the data for filters dropdown
        $filters = $this->getFilters();

        return view('document.completed',compact('documentTrackings', 'terminals', 'filters'));
    }

    public function tracked()
    {
        $documentTrackings = DocumentTracking::where('terminal_id', auth()->user()->office_id)
        ->where('status', 'rejected')
        ->with('user','documentDetail')
        ->get();
        return view('document.tracked',compact('documentTrackings'));
    }

    public function create(Request $request)
    {
        // ** use to get the data for filters dropdown
        $filters = $this->getFilters();

        $documents = DocumentDetail::with('terminal', 'documentTracking', 'document_category');

        if(!auth()->user()->can_view_all) $documents->where('user_id', auth()->user()->id);

        if(isset($request->type) && $request->type != '')
            $documents->where('document_category_id', '=', $request->type);

        if((isset($request->date_from) && isset($request->date_to)) && ($request->date_from != '' && $request->date_to != ''))
            $documents->whereDate('created_at', '>=', date($request->date_from))->whereDate('created_at', '<=', date($request->date_to));

        if(isset($request->user) && $request->user != '') {
            $documents->whereHas('documentTracking', function($q) use($request) {
                $q->where('user_id', '=', $request->user);
            });
        }

        $documents = $documents->orderBy('created_at', 'desc')->get();

        $terminals = Terminal::whereHas('user', function($query) {
            return $query->where('is_active', 1);
        })->orderBy('terminal_name')->get();

        $categories = DocumentCategory::get()->sortBy('category_name');

        return view('document.create',compact('documents', 'terminals', 'categories', 'filters'));
    }

    public function store(Request $request)
    {
       try {
            $document_code = $this->generateDocumentNumber();
            DB::transaction(function () use ($request,$document_code) {
                $documentDetail = DocumentDetail::create([
                    'user_id'           => auth()->user()->id,
                    'document_code'     => $document_code,
                    'type'              => $request->type,
                    'name_of_client'    => $request->name_of_client,
                    'description'       => $request->description,
                    'terminal_id'       => $request->terminal,
                    'document_category_id'  => $request->category_id == 'others'? null: $request->category_id,
                    'contact'           => $request->contact
                    // 'is_check_by_dm'    => $request->is_check_by_dm == 'on'
                    // 'transaction_type'  =>  1, // to identify if simple, complex, highly technical
                    // 'is_verified'       => false // used to check if na verified na ba ni DM
                ]);

                $remark = Remark::create([
                    'remarks'   => $request->remarks,
                ]);

                DocumentTracking::create([
                    'user_id'            => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'terminal_id'        => $request->terminal,
                    'remark_id'          => $remark->id,
                    'status'             => 'incoming',
                ]);

                DocumentTrace::create([
                    'user_id'            => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id'          => $remark->id
                ]);

                ReceivedHistory::create([
                    'user_id'            => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id'          => $remark->id
                ]);
            });
            $latestId = DocumentDetail::orderBy('created_at', 'desc')->pluck('id')->first();

            toast('Successfully created...','success');
            return redirect()->back()->with('success', $latestId);

       } catch (\Exception $e){
            Alert::error('oppss', 'Please try again...');
            return redirect()->back();
       }
    }

     function generateDocumentNumber(){
        $number = mt_rand(100000, 999999); // better than rand()

        // call the same function if the barcode exists already
        if ($this->documentNumberExists($number)) {
            return $this->generateRegistrationNumber();
        }

        // otherwise, it's valid and can be used
        return $number;
    }

    function documentNumberExists($code){
        return DocumentDetail::where('document_code',$code)->exists();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id){
                if($request->status === "incoming"){
                    $remark = Remark::create([
                        'remarks'        => $request->remarks,
                    ]);

                    $documentTracking              = DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id     = auth()->user()->id;
                    $documentTracking->status      = $request->status;
                    $documentTracking->terminal_id = $request->terminal_id;
                    $documentTracking->remark_id   = $remark->id;
                    $documentTracking->save();

                    $documentDetail = DocumentDetail::FindOrFail($id);
                    // $documentDetail->is_check_by_dm = $request->is_check_by_dm == 'on';
                    $documentDetail->save();

                    $documentTrace = DocumentTrace::where('document_detail_id', '=', $id)->latest()->first();
                    $documentTrace->remark_id = $remark->id;
                    $documentTrace->save();

                    Outgoing::create([
                        'document_detail_id' => $documentTracking->id,
                        'user_id'            => auth()->user()->id,
                        'terminal_id'        => $request->terminal_id,
                        'remark_id'          => $remark->id
                    ]);

                    Alert::success('Forwarded', '');

                }elseif($request->status === "received"){
                    $documentTracking =  DocumentTracking::with('documentDetail')->FindOrFail($id);
                    $documentTracking->status = $request->status;
                    $documentTracking->is_received = 1;

                    $documentTracking->save();
                    DocumentTrace::create([
                        'user_id'            => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                        // 'remark_id'          => $remark->id
                    ]);

                    ReceivedHistory::create([
                        'user_id'            => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                        'remark_id'          => $documentTracking->remark_id,
                    ]);

                    Alert::success('Received', '');
                }elseif($request->status === "completed"){
                    $remark = Remark::create([
                        'remarks'        => $request->remarks,
                    ]);

                    $documentTracking            =  DocumentTracking::with('documentDetail')->FindOrFail($id);
                    $documentTracking->status    = $request->status;
                    $documentTracking->remark_id = $remark->id;
                    $documentTracking->save();

                    DocumentTrace::create([
                        'user_id'            => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                        'status'             => $request->status,
                        'remark_id'          => $remark->id
                    ]);

                    Alert::success('Completed', '');
                }elseif($request->status === "rejected"){
                    $documentTracking =  DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id = auth()->user()->id;
                    $documentTracking->status = $request->status;
                    $documentTracking->save();
                }
            });
        } catch (\Exception $e){
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function updateEdit(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id){
                $documentDetail = DocumentDetail::find($id);
                $documentDetail->type           = $request->category_id=='others'? $request->type:'';
                $documentDetail->name_of_client = $request->name_of_client;
                $documentDetail->description    = $request->description;
                $documentDetail->terminal_id    = $request->terminal_id;
                $documentDetail->contact        = $request->contact;
                $documentDetail->document_category_id    = $request->category_id == 'others'? null: $request->category_id;
                $documentDetail->save();
            });
        } catch (\Exception $e){
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        Alert::success('Success', 'Successfully updated...');
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $detail = DocumentDetail::find($id);
        if($detail->is_verified) {
            toast('Document cannot be deleted when verified.','danger');
            return redirect()->back()->with('danger');
        }

        DocumentTracking::where('document_detail_id', $id)->delete();
        DocumentTrace::where('document_detail_id', $id)->delete();
        ReceivedHistory::where('document_detail_id', $id)->delete();
        $detail->delete();

        toast('Successfully deleted...','success');
        return redirect()->back()->with('success');
    }

    public function getDocument(string $id)
    {
        $documents = DocumentDetail::with('terminal', 'documentTracking', 'document_category')->find($id);
        return response()->json(['documents'=>$documents]);
    }

    public function getFilters() {
        $types = DocumentCategory::get();
        $users = User::with('office')->get();

        return compact('types', 'users');
    }

    public function filter($request, $object) {

        if(isset($request->type) && $request->type != '') {
            $object->whereHas('documentDetail', function($q) use($request) {
                $q->where('document_category_id', '=', $request->type); });
        }

        if((isset($request->date_from) && isset($request->date_to)) && ($request->date_from != '' && $request->date_to != '')) {
            $object->whereHas('documentDetail', function($q) use($request) {
                $q->whereDate('created_at', '>=', date($request->date_from))->whereDate('created_at', '<=', date($request->date_to));
            });
        }

        if(isset($request->user) && $request->user != '') {
            $object->whereHas('user', function($q) use($request) {
                $q->where('id', '=', $request->user);
            });
        }

        return $object;
    }
}




