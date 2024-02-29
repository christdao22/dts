<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories  = DocumentCategory::get();
        return view('document.document_category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // unique:document_categories|
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|max:50',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        if($request->isEdit == '1') {
            $save = DB::table('document_categories')
                ->where('id', $request->categoryId)
                ->update([
                    'category_name'   => $request->category_name,
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
        } else {
            $save = DocumentCategory::insert([
                'category_name' => $request->category_name,
                'created_at'    => date('Y-m-d H:i:s'),
                'user_id'       => auth()->user()->id,
                'is_direct'     => $request->is_direct == 'on'? 1:0,
                'updated_at'    => null,
            ]);
        }

        if(!$save) return back()->withInput()->with('exception', 'Please try again');

        $request->isEdit == '1'? toast('Successfully updated...','success'):toast('Successfully created...','success');

        return redirect()->back()->with('success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = DocumentCategory::find($id);
        return response()->json(['category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentCategory $documentCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = DocumentCategory::find($id);
        $category->delete();

        toast('Successfully deleted...','success');
        return redirect()->back()->with('success');
    }
}
