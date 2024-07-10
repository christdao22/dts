<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\DocumentDetail;
use App\Models\DocumentTrace;
use App\Models\DocumentTracking;
use App\Models\GeneratedCode;
use App\Models\Outgoing;
use App\Models\ReceivedHistory;
use App\Models\Remark;
use App\Models\Terminal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class DocumentController extends Controller
{

    public function maintenance()
    {
        if (false) {
            Alert::warning('System maintenance', 'The system will be down for maintenance today. Please save your work')->persistent('Dismiss');
        }

        if (false) {
            Alert::success('System maintenance completed', 'Sorry for the inconvenience. Anhi lang sa ICT Team if naa mo concern. Thank you for your understanding!.');
        }
    }

    public function find(Request $request)
    {
        try {
            $search_text = $request->input('query');

            $documentDetail = DocumentDetail::with('document_category')->where('document_code', $search_text)->first();
            if ($documentDetail) {
                $documentTraces = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user.terminal', 'documentDetail', 'remark')->get();
                $documentLatest = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user.terminal', 'documentDetail', 'remark')->latest()->first();
                $documentTracking = DocumentTracking::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail', 'terminal', 'remark')->first();

            } else {
                Alert::error('oppss', 'No record found...');
                return view('document.tracked');
            }
        } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('document.tracked');
        }
        return view('document.tracked', compact('documentTraces', 'documentTracking', 'documentDetail'));
    }

    public function find2(Request $request)
    {
        try {
            $search_text = $request->input('query');

            $documentDetail = DocumentDetail::where('document_code', $search_text)->first();

            if ($documentDetail) {
                $documentTraces = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user.terminal', 'documentDetail')->get();
                $documentLatest = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user.terminal', 'documentDetail')->latest()->first();
                $data = $documentLatest->user->terminal->id;

                // throw new Exception('Something went wrong');
                $documentTracking = DocumentTracking::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail', 'terminal')->first();
                if ($data == $documentTracking->terminal->id && $documentTracking->status == "incoming") {
                    $documentTracking = null;
                }
            } else {
                Alert::error('oppss', 'No record found...');
                return view('tracked');
            }
        } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('tracked');
        }
        return view('tracked', compact('documentTraces', 'documentTracking', 'documentDetail'));
    }

    public function dts()
    {
        return view('tracked');
    }

    public function allDocuments(Request $request)
    {
        $this->maintenance();
        $filters = $this->getFilters();
        $documentTrackings = DocumentTracking::where('terminal_id', auth()->user()->office_id)
            ->where('status', 'received')
            ->with('user', 'documentDetail');
        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->get();

        return view('document.received', compact('documentTrackings', 'filters'));
    }

    public function received(Request $request)
    {
        $this->maintenance();
        $documentTrackings = [];
        $filters = $this->getFilters();
        $terminals = $this->getTerminals();

        $terminal = Terminal::with('user')->where('user_id', auth()->user()->id)->first();
        if ($terminal != null) {
            $documentTrackings = DocumentTracking::where('terminal_id', $terminal->id)
                ->where('status', 'received')
                ->with('user', 'documentDetail', 'remark');

            $documentTrackings = $this->filter($request, $documentTrackings);
            $documentTrackings = $documentTrackings->orderBy('created_at', 'desc')->get();
        }

        return view('document.received', compact('documentTrackings', 'terminals', 'filters'));
    }

    public function incoming(Request $request)
    {
        $this->maintenance();
        $filters = $this->getFilters();

        return view('document.incoming', compact('filters'));
    }

    public function receivedHistory(Request $request)
    {
        $this->maintenance();
        $filters = $this->getFilters();
        $receivedHistories = ReceivedHistory::with('user.terminal', 'documentDetail.document_category', 'remark');
        $receivedHistories = $this->filter($request, $receivedHistories);
        $receivedHistories = $receivedHistories->get();

        $receivedHistories = $receivedHistories->filter(function ($r) {
            return $r->user->office_id == auth()->user()->office_id;
        });

        return view('document.received_histories', compact('receivedHistories', 'filters'));
    }

    public function outgoing(Request $request)
    {
        $this->maintenance();
        $terminals = $this->getTerminals();
        $filters = $this->getFilters();

        $documentTrackings = Outgoing::with('user.terminal', 'documentDetail.documentTracking', 'documentDetail.document_category', 'terminal', 'remark')->where('user_id', auth()->user()->id);
        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->orderBy('created_at', 'desc')->get();

        $documentTrackings->filter(function ($o) {
            return $o->user->office_id == auth()->user()->office_id;
        });

        return view('document.outgoing', compact('documentTrackings', 'filters', 'terminals'));
    }

    public function completed(Request $request)
    {
        $this->maintenance();

        $documentTrackings = [];
        $terminals = $this->getTerminals();
        $filters = $this->getFilters();

        if (!auth()->user()->is_admin) {
            $terminal = Terminal::with('user')->where('user_id', auth()->user()->id)->first();

            if ($terminal != null) {
                $documentTrackings = DocumentTracking::where('terminal_id', $terminal->id)
                    ->where('status', 'completed')
                    ->with('remark', 'documentDetail', 'user');
            }

        } else {
            $documentTrackings = DocumentTracking::where('status', 'completed')
                ->with('remark', 'documentDetail.document_category', 'user.terminal');
        }

        $documentTrackings = $this->filter($request, $documentTrackings);
        $documentTrackings = $documentTrackings->orderBy('updated_at', 'desc')->get();

        return view('document.completed', compact('documentTrackings', 'terminals', 'filters'));
    }

    public function tracked()
    {
        return view('document.tracked');
    }

    public function create(Request $request)
    {
        $this->maintenance();

        $filters = $this->getFilters();
        $terminals = $this->getTerminals();

        $categories = cache()->rememberForever('categories_cache_' . date('Y-m-d'), function () {
            return DocumentCategory::get()->sortBy('category_name');
        });

        $document_codes = DocumentDetail::where('user_id', null)->get();

        return view('document.create', compact('terminals', 'categories', 'filters', 'document_codes'));
    }

    public function store(Request $request)
    {
        try {
            $document_code = $request->document_code != '' ? $request->document_code : $this->generateDocumentNumber();

            DB::transaction(function () use ($request, $document_code) {
                $generated_code_query = GeneratedCode::where('document_code', $document_code);
                $generated_data = $generated_code_query->get();
                $documentDetail = DocumentDetail::create([
                    'user_id' => auth()->user()->id,
                    'document_code' => $document_code,
                    'type' => $request->type,
                    'name_of_client' => $request->name_of_client,
                    'description' => $request->description,
                    'terminal_id' => $request->terminal,
                    'document_category_id' => $request->category_id == 'others' ? null : $request->category_id,
                    'contact' => $request->contact,
                    'created_at' => count($generated_data) != 0 ? date($generated_data[0]->created_at) : date('Y-m-d H:i:s'),
                ]);

                $generated_code_query->delete();

                $remark = Remark::create([
                    'remarks' => $request->remarks,
                ]);

                DocumentTracking::create([
                    'id' => $documentDetail->id,
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'terminal_id' => $request->terminal,
                    'remark_id' => $remark->id,
                    'status' => 'incoming',
                ]);

                DocumentTrace::create([
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id' => $remark->id,
                ]);

                ReceivedHistory::create([
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id' => $remark->id,
                ]);
            });
            $latestId = DocumentDetail::orderBy('created_at', 'desc')->pluck('id')->first();

            toast('Successfully created...', 'success');
            return redirect()->back()->with('success', $latestId);

        } catch (\Exception $e) {
            Alert::error('oppss', 'Please try again...');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                if ($request->status === "incoming") {
                    $remark = Remark::create([
                        'remarks' => $request->remarks,
                    ]);

                    $documentTracking = DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id = auth()->user()->id;
                    $documentTracking->status = $request->status;
                    $documentTracking->terminal_id = $request->terminal_id;
                    $documentTracking->remark_id = $remark->id;
                    $documentTracking->save();

                    $documentDetail = DocumentDetail::FindOrFail($id);
                    $documentDetail->save();

                    $documentTrace = DocumentTrace::where('document_detail_id', '=', $id)->latest()->first();
                    $documentTrace->remark_id = $remark->id;
                    $documentTrace->save();

                    Outgoing::create([
                        'document_detail_id' => $documentTracking->id,
                        'user_id' => auth()->user()->id,
                        'terminal_id' => $request->terminal_id,
                        'remark_id' => $remark->id,
                    ]);

                    Alert::success('Forwarded', '');

                } elseif ($request->status === "received") {
                    $documentTracking = DocumentTracking::with('documentDetail')->FindOrFail($id);
                    $documentTracking->status = $request->status;
                    $documentTracking->is_received = 1;

                    $documentTracking->save();
                    DocumentTrace::create([
                        'user_id' => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                    ]);

                    ReceivedHistory::create([
                        'user_id' => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                        'remark_id' => $documentTracking->remark_id,
                    ]);

                    Alert::success('Received', '');
                } elseif ($request->status === "completed") {
                    $remark = Remark::create([
                        'remarks' => $request->remarks,
                    ]);

                    $documentTracking = DocumentTracking::with('documentDetail')->FindOrFail($id);
                    $documentTracking->status = $request->status;
                    $documentTracking->remark_id = $remark->id;
                    $documentTracking->save();

                    DocumentTrace::create([
                        'user_id' => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                        'status' => $request->status,
                        'remark_id' => $remark->id,
                    ]);

                    Alert::success('Completed', '');
                }
            });
        } catch (\Exception $e) {
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function updateEdit(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $documentDetail = DocumentDetail::find($id);
                $documentDetail->type = $request->category_id == 'others' ? $request->type : '';
                $documentDetail->name_of_client = $request->name_of_client;
                $documentDetail->description = $request->description;
                $documentDetail->terminal_id = $request->terminal_id;
                $documentDetail->contact = $request->contact;
                $documentDetail->document_category_id = $request->category_id == 'others' ? null : $request->category_id;
                $documentDetail->save();

                $tracking = DocumentTracking::find($id);
                $tracking->terminal_id = $request->terminal_id;
                $tracking->save();
            });
        } catch (\Exception $e) {
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        Alert::success('Success', 'Successfully updated...');
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $detail = DocumentDetail::find($id);
        if ($detail->is_verified) {
            toast('Document cannot be deleted if verified.', 'danger');
            return redirect()->back()->with('danger');
        }

        DocumentTracking::where('document_detail_id', $id)->delete();
        DocumentTrace::where('document_detail_id', $id)->delete();
        ReceivedHistory::where('document_detail_id', $id)->delete();
        $detail->delete();

        toast('Successfully deleted...', 'success');
        return redirect()->back()->with('success');
    }

    public function getDocument(string $id)
    {
        $documents = DocumentDetail::with('terminal', 'documentTracking.remark', 'document_category')->find($id);
        return response()->json(['documents' => $documents]);
    }

    public function getFilters()
    {
        $types = cache()->rememberForever('types_cache_' . date('Y-m-d'), function () {
            return DocumentCategory::get()->sortBy('category_name');
        });

        $users = cache()->rememberForever('users_cache_' . date('Y-m-d'), function () {
            return User::with('office')->get();
        });

        return compact('types', 'users');
    }

    public function filter($request, $object)
    {
        $searchValue = $request->search['value'] ?? null;

        $object->when($request->filled('type'), function ($query) use ($request) {
                    if($request->type != "others"){
                            $query->where('document_details.document_category_id', $request->type);
                    } else {
                            $query->where('document_details.document_category_id', null);
                    }
                })
                ->when($request->filled('date_from') && $request->filled('date_to'), function ($query) use ($request) {
                    $dateFrom = Carbon::parse($request->date_from);
                    $dateTo = Carbon::parse($request->date_to)->addDay();
                    $query->whereBetween('document_details.created_at', [$dateFrom, $dateTo]);
                })
                ->when($request->filled('user'), function ($query) use ($request) {
                    $query->where('document_trackings.user_id', $request->user);
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    if($request->status == 'pending receive') {
                        $query->where('document_trackings.status', 'incoming');
                        $query->where('document_trackings.is_received', 0);
                    } else {
                        $query->where('document_trackings.status', $request->status);
                    }
                })
                ->when($searchValue, function ($query) use ($searchValue) {
                    $query->where(function ($subQuery) use ($searchValue) {
                        $subQuery->where('document_details.document_code', 'like', "%{$searchValue}%")
                            ->orWhere('document_details.name_of_client', 'like', "%{$searchValue}%")
                            ->orWhere('document_details.contact', 'like', "%{$searchValue}%")
                            ->orWhere('terminals.terminal_name', 'like', "%{$searchValue}%")
                            ->orWhere('document_trackings.status', 'like', "%{$searchValue}%")
                            ->orWhere('document_categories.category_name', 'like', "%{$searchValue}%");
                    });
                });

        return $object;
    }

    public function generateDocumentNumber()
    {
        $number = mt_rand(100000, 999999);

        // call the same function if the barcode exists already
        if ($this->documentNumberExists($number)) {
            return $this->generateRegistrationNumber();
        }

        // otherwise, it's valid and can be used
        return $number;
    }

    public function documentNumberExists($code)
    {
        return DocumentDetail::where('document_code', $code)->exists() || GeneratedCode::where('document_code', $code)->exists();
    }

    public function generateCode()
    {
        $document_code = $this->generateDocumentNumber();

        GeneratedCode::create([
            'document_code' => $document_code,
        ]);

        Alert::success($document_code, 'Code Generated Successfully')->persistent('Dismiss');
        return redirect()->back();
    }

    /**
     * Change from Complete to Forward
     */
    public function undoActionComplete(Request $request, string $id)
    {
        $documentTrace = DocumentTrace::where('document_detail_id', $id)->latest()->first();
        $documentTrace->status = 'received';
        $documentTrace->save();

        $documentTracking = DocumentTracking::FindOrFail($id);
        $documentTracking->status = 'incoming';
        $documentTracking->user_id = $documentTrace->user_id;
        $documentTracking->terminal_id = $request->terminal_id;
        $documentTracking->save();

        $remark = Remark::FindOrFail($documentTracking->remark_id);
        $remark->remarks = $request->remarks;
        $remark->save();

        Outgoing::create([
            'document_detail_id' => $documentTracking->id,
            'user_id' => $documentTrace->user_id,
            'terminal_id' => $request->terminal_id,
            'remark_id' => $remark->id,
        ]);

        Alert::success('Forwarded Successfully', '');
        return redirect()->back();
    }

    /**
     * re forward to other office
     */
    public function changeForward(Request $request, string $id)
    {
        $documentTracking = DocumentTracking::FindOrFail($id);
        $documentTracking->terminal_id = $request->terminal_id;
        $documentTracking->save();

        $outgoing = Outgoing::where('document_detail_id', '=', $documentTracking->id)->latest()->first();
        $outgoing->terminal_id = $request->terminal_id;
        $outgoing->save();

        $remark = Remark::FindOrFail($documentTracking->remark_id);
        $remark->remarks = $request->remarks;
        $remark->save();

        Alert::success('Successfully Changed', '');
        return redirect()->back();
    }

    public function guestCreate(Request $request)
    {
        try {
            $document_code = $this->generateDocumentNumber();
            DB::transaction(function () use ($request, $document_code) {
                DocumentDetail::create([
                    'document_code' => $document_code,
                    'name_of_client' => $request->name_of_client,
                    'description' => $request->description,
                    'contact' => $request->contact,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            });

            Alert::success($document_code, 'Successfully Created')->persistent('Dismiss');
            return redirect()->back()->with([
                'success' => 'true',
                'code' => $document_code,
            ]);

        } catch (\Exception $e) {
            Alert::error('oppss', 'Please try again...');
            return redirect()->back();
        }
    }

    public function printPDF(string $id)
    {

        $detail = DocumentDetail::where('document_code', $id)->get()->first();

        return view('document.pdf_slip', compact('detail'));
    }

    public function storeGuestCreate(string $id, Request $request)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $documentDetail = DocumentDetail::find($id);
                $documentDetail->user_id = auth()->user()->id;
                $documentDetail->name_of_client = $request->add_name_of_client;
                $documentDetail->contact = $request->add_contact;
                $documentDetail->description = $request->add_description;
                $documentDetail->document_category_id = $request->add_category_id == 'others' ? null : $request->add_category_id;
                $documentDetail->type = $request->add_type;
                $documentDetail->terminal_id = $request->add_terminal_id;
                $documentDetail->save();

                $remark = Remark::create([
                    'remarks' => $request->add_remarks,
                ]);

                DocumentTracking::create([
                    'id' => $documentDetail->id,
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'terminal_id' => $request->add_terminal_id,
                    'remark_id' => $remark->id,
                    'status' => 'incoming',
                ]);

                DocumentTrace::create([
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id' => $remark->id,
                ]);

                ReceivedHistory::create([
                    'user_id' => auth()->user()->id,
                    'document_detail_id' => $documentDetail->id,
                    'remark_id' => $remark->id,
                ]);
            });
            $latestId = DocumentDetail::orderBy('created_at', 'desc')->pluck('id')->first();

            toast('Successfully created...', 'success');
            return redirect()->back()->with('success', $latestId);

        } catch (\Exception $e) {
            Alert::error('oppss', 'Please try again...');
            return redirect()->back();
        }
    }

    public function deleteGuestCode(string $id)
    {
        $document = DocumentDetail::find($id);
        $document->delete();
        toast('Successfully deleted...', 'success');

        return redirect()->back()->with('addDocumentModal', 'true');
    }

    public function getTerminals()
    {
        return cache()->rememberForever('terminals_cache_' . date('Y-m-d'), function () {
            return Terminal::with('user')->whereHas('user', function ($query) {
                return $query->where('is_active', 1);
            })->orderBy('terminal_name')->get();
        });
    }

    public function dtAllDocuments(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;

            $baseQuery = DB::table('document_details')
            ->join('document_trackings', 'document_details.id', '=', 'document_trackings.document_detail_id')
            ->join('terminals', 'document_trackings.terminal_id', '=', 'terminals.id')
            ->leftJoin('document_categories', 'document_details.document_category_id', '=', 'document_categories.id')
            ->whereNotNull('document_details.user_id')
            ->when($request->is_show_docs == '0', function ($query) use ($user) {
                $query->where('document_details.user_id', $user->id);
            });

            $totalRecords = $baseQuery->count('document_details.id');

            $baseQuery = $this->filter($request, $baseQuery);
            // Count filtered records
            $filteredRecords = $baseQuery->count('document_details.id');

            $documents = $baseQuery->select(
                    'document_details.id',
                    'document_details.document_code',
                    'document_details.name_of_client',
                    'document_details.contact',
                    'document_details.description',
                    'document_details.type',
                    'document_details.created_at',
                    'document_details.document_category_id',
                    'document_details.user_id',
                    'document_trackings.status',
                    'document_trackings.is_received',
                    'terminals.terminal_name',
                    'document_categories.category_name')
                ->orderBy('document_details.created_at', 'desc')
                ->offset($start)
                ->limit($length)
                ->get();

            return DataTables::of($documents)
                ->addIndexColumn()
                ->editColumn('category', function($document) {
                    return $document->document_category_id != null?
                                $document->category_name : "<b>Others: </b>" .
                                $document->type;
                })
                ->editColumn('from', function($document) {
                    return $document->name_of_client . '<br>' . ($document->contact != ''? '(' . $document->contact . ')':'') ;
                })
                ->editColumn('terminal', function($document) {
                    return isset($document->terminal_name)? $document->terminal_name : '';
                })
                ->editColumn('created_at', function($document) {
                    return formatDateTime($document->created_at);
                })
                ->editColumn('status', function($document) {
                    if($document->status == 'completed') {
                        $status = '<span class="badge rounded-pill bg-success">Completed/Release</span>';
                    } else if(!$document->is_received) {
                        $status = '<span class="badge rounded-pill bg-secondary">Pending receive</span>';
                    } else {
                        $status = '<span class="badge rounded-pill bg-warning">In progress</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function ($document) {
                    $actionBtn = '
                        <div class="d-flex justify-content-end gap-1">
                            <a class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Track"
                                href="' . route('web.find', ['query' => $document->document_code]) . '">
                                <i class="ri-route-line"></i>
                            </a>
                            ' . (!$document->is_received && $document->user_id == auth()->user()->id ? '
                            <button class="btn btn-danger deleteBtn" data-bs-id="' . $document->id . '">
                                <i class="ri-delete-bin-line"></i>
                                <form id="delete_form_' . $document->id . '"
                                    action="' . route('document.destroy', $document->id) . '"
                                    method="POST" enctype="multipart/form-data" style="display:none;">
                                    ' . method_field('DELETE') . csrf_field() . '
                                </form>
                            </button>
                            <a href="javascript:void(0)" class="btn btn-primary editButton"
                                data-bs-id="' . $document->id . '"><i class="ri-edit-2-line"></i></a>
                            ' : '') . '
                        </div>
                    ';

                    return $actionBtn;
                })
                ->addColumn('description', function ($document) {
                    return make_excerpt($document->description, 25);
                })
                ->rawColumns(['created_at', 'terminal', 'status', 'from', 'description', 'category', 'action'])
                ->with('recordsTotal', $totalRecords)
                ->with('recordsFiltered', $filteredRecords)
                ->skipAutoFilter()
                ->skipPaging(true)
                ->make(true);
        }
    }

    public function dtIncoming(Request $request) {
        if ($request->ajax()) {
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;

            $baseQuery = DB::table('document_trackings')
                ->join('document_details', 'document_trackings.document_detail_id', '=', 'document_details.id')
                ->join('users', 'document_trackings.user_id', '=', 'users.id')
                ->leftJoin('terminals', 'users.id', '=', 'terminals.user_id')
                ->leftJoin('document_categories', 'document_details.document_category_id', '=', 'document_categories.id')
                ->leftJoin('remarks', 'document_trackings.remark_id', '=', 'remarks.id')
                ->where('document_trackings.terminal_id', auth()->user()->terminal->id)
                ->where('document_trackings.status', 'incoming');

            $totalRecords = DB::table('document_trackings')
                ->where('document_trackings.terminal_id', auth()->user()->terminal->id)
                ->where('document_trackings.status', 'incoming')
                ->count('document_trackings.id');

            $baseQuery = $this->filter($request, $baseQuery);

            $filteredRecords  = $baseQuery->count('document_trackings.id');

            $documents = $baseQuery->select(
                'document_details.id',
                'document_details.document_code',
                'document_details.name_of_client',
                'document_details.contact',
                'document_details.description',
                'document_details.type',
                'document_details.created_at',
                'document_details.document_category_id',
                'document_details.user_id',
                'document_trackings.status',
                'terminals.terminal_name',
                'document_categories.category_name',
                'remarks.remarks')
                ->orderBy('document_details.created_at', 'desc')
                ->offset($start)
                ->limit($length)
                ->get();

            return DataTables::of($documents)
                ->addIndexColumn()
                ->editColumn('category', function ($document) {
                    return $document->document_category_id != null ?
                    $document->category_name : "<b>Others: </b>" .
                    $document->type;
                })
                ->editColumn('from', function ($document) {
                    return $document->name_of_client . '<br>' . ($document->contact != '' ? '(' . $document->contact . ')' : '');
                })
                ->editColumn('terminal', function ($document) {
                    return isset($document->terminal_name) ? $document->terminal_name : '';
                })
                ->editColumn('created_at', function ($document) {
                    return formatDateTime($document->created_at);
                })
                ->addColumn('action', function ($document) {
                    $actionBtn = '<button class="btn btn-warning viewBtn" href="javascript:void(0)"
                                data-bs-id="' . $document->id . '">
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Show"><i
                                                class="ri ri-eye-fill"></i></span>
                                    </button>
                                    <a class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Track"
                                        href="' . route("web.find", 'query='.$document->document_code) . '"><i
                                            class="ri-route-line"></i></a>
                                    <button class="btn btn-success receivedBtn" data-bs-id="' . $document->id . '"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Receive"><i
                                            class=" ri-mail-add-line"></i></button>';

                    return $actionBtn;
                })
                ->addColumn('description', function ($document) {
                    return make_excerpt($document->description, 25);
                })
                ->rawColumns(['created_at', 'terminal', 'from', 'description', 'category', 'action'])
                ->with('recordsTotal', $totalRecords)
                ->with('recordsFiltered', $filteredRecords)
                ->skipAutoFilter()
                ->skipPaging(true)
                ->make(true);

        }
    }

    public function dtReceived(Request $request) {
        if ($request->ajax()) {
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;

            $baseQuery = DB::table('document_trackings')
                ->join('document_details', 'document_trackings.document_detail_id', '=', 'document_details.id')
                ->join('users', 'document_trackings.user_id', '=', 'users.id')
                ->leftJoin('document_categories', 'document_details.document_category_id', '=', 'document_categories.id')
                ->leftJoin('remarks', 'document_trackings.remark_id', '=', 'remarks.id')
                ->where('document_trackings.terminal_id', auth()->user()->terminal->id)
                ->where('document_trackings.status', 'received');


            $totalRecords = DB::table('document_trackings')
                ->where('document_trackings.terminal_id', auth()->user()->terminal->id)
                ->where('document_trackings.status', 'received')
                ->count('document_trackings.id');

            $baseQuery = $this->filter($request, $baseQuery);
            $filteredRecords = $baseQuery->count('document_trackings.id');

            $documents = $baseQuery->select(
                'document_details.id',
                'document_details.document_code',
                'document_details.name_of_client',
                'document_details.contact',
                'document_details.description',
                'document_details.type',
                'document_details.created_at',
                'document_details.document_category_id',
                'document_details.user_id',
                'document_trackings.status',
                'document_categories.category_name',
                'remarks.remarks')
                ->orderBy('document_details.created_at', 'desc')
                ->offset($start)
                ->limit($length)
                ->get();

            return DataTables::of($documents)
                ->addIndexColumn()
                ->editColumn('category', function ($document) {
                    return $document->document_category_id != null ?
                    $document->category_name : "<b>Others: </b>" .
                    $document->type;
                })
                ->editColumn('from', function ($document) {
                    return $document->name_of_client . '<br>' . ($document->contact != '' ? '(' . $document->contact . ')' : '');
                })
                ->editColumn('date_time', function ($document) {
                    return formatDateTime($document->created_at);
                })
                ->addColumn('action', function ($document) {
                    $actionBtn = '<button type="button" class="btn btn-warning" href="javascript:void(0)"
                                data-bs-id="' . $document->id . '"><i
                                            class="ri-share-forward-2-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Forward"></i></button>
                                    <a class="btn btn-info"
                                        href="' . route('web.find', 'query='.$document->document_code) . '"><i
                                            class="ri-route-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Track"></i></a>
                                    <button type="button" class="btn btn-success"href="javascript:void(0)"
                                        data-bs-id="' . $document->id . '"><i
                                            class=" ri-check-fill" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Complete"></i></button>';

                    return $actionBtn;
                })
                ->addColumn('description', function ($document) {
                    return make_excerpt($document->description, 25);
                })
                ->rawColumns(['created_at', 'terminal', 'from', 'description', 'category', 'action'])
                ->with('recordsTotal', $totalRecords)
                ->with('recordsFiltered', $filteredRecords)
                ->skipAutoFilter()
                ->skipPaging(true)
                ->make(true);
        }

    }

}

