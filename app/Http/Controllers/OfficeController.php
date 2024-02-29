<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{

    public function index()
    {
        $offices = Offices::get();
        return view('admin.offices', compact('offices'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_name' => 'required|unique:offices|max:50',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        if($request->isEdit == '1') {
            $save = DB::table('offices')
                ->where('id', $request->officeId)
                ->update([
                    'office_name'   => $request->office_name,
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
        } else {
            $save = Offices::insert([
                'office_name' => $request->office_name,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => null,
            ]);
        }

        if(!$save) return back()->withInput()->with('exception', 'Please try again');

        $request->isEdit == '1'? toast('Successfully updated...','success'):toast('Successfully created...','success');

        return redirect()->back()->with('success');
    }

    public function show(string $id)
    {
        $office = Offices::find($id);
        return response()->json(['office'=>$office]);
    }

    public function destroy(string $id)
    {
        $office = Offices::find($id);
        $office->delete();

        toast('Successfully deleted...','success');
        return redirect()->back()->with('success');
    }
}
