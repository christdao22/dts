<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use App\Models\OfficeTerminal;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('office')->get();
        $offices = Offices::get()->sortBy('office_name');
        return view('admin.users', compact('users', 'offices'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'role'              => 'required',
            'first_name'        => 'required|max:50',
            'middle_name'       => 'nullable|max:50',
            'last_name'         => 'required|max:50',
            'office_id'         => 'required',
            'email'             => 'required|email|max:50',
            'password'          => 'required|confirmed|max:50',
            'isEdit'            => 'nullable',
            'userId'            => 'nullable',
        ]);

        if($validated['isEdit'] == '1') {
            $save = DB::table('users')
                ->where('id', $request->userId)
                ->update([
                    'first_name'      => $validated['first_name'],
                    'middle_name'     => $validated['middle_name'],
                    'last_name'       => $validated['last_name'],
                    'office_id'       => $validated['office_id'],
                    'email'           => $validated['email'],
                    'password'        => $validated['password'],
                    'is_dm'           => $validated['role'],
                    'updated_at'      => date('Y-m-d H:i:s'),
                    'can_create'      => $request->can_create == 'on'? 1:0,
                    'can_view_all'    => $request->can_view_all == 'on'? 1:0,

                ]);
        } else {
            $save = User::insert([
                'first_name'      => $validated['first_name'],
                'middle_name'     => $validated['middle_name'],
                'last_name'       => $validated['last_name'],
                'office_id'       => $validated['office_id'],
                'email'           => $validated['email'],
                'password'        => $validated['password'],
                'is_dm'           => $validated['role'],
                'can_create'      => $request->can_create == 'on'? 1:0,
                'can_view_all'    => $request->can_view_all == 'on'? 1:0,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => null,
            ]);
        }

        if(!$save) return back()->withInput()->with('exception', 'Please try again');

        $validated['isEdit'] == '1'? toast('Successfully updated...','success'):toast('Successfully created...','success');

        return redirect()->back()->with('success');
    }

    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json(['user'=>$user]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        toast('Successfully deleted...','success');
        return redirect()->back()->with('success');
    }

    public function guestStore(Request $request)
    {
        $validated = $this->validate($request, [
            'role'              => 'required',
            'first_name'        => 'required|max:50',
            'middle_name'       => 'nullable|max:50',
            'last_name'         => 'required|max:50',
            'office_id'         => 'required',
            'email'             => 'required|email|max:50',
            'password'          => 'required|confirmed|max:50',
            'terminal_name'     => 'required|max:50',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name'      => $validated['first_name'],
                'middle_name'     => $validated['middle_name'],
                'last_name'       => $validated['last_name'],
                'office_id'       => $validated['office_id'],
                'email'           => $validated['email'],
                'password'        => $validated['password'],
                'is_dm'           => $validated['role'],
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => null,
            ]);

            $terminal_id = Terminal::create([
                'terminal_name' => $validated['terminal_name'],
                'user_id'       => $user->id,
            ])->id;

            OfficeTerminal::insert([
                'office_id'   => $user->office_id,
                'terminal_id' => $terminal_id,
                'created_at'  => now(),
            ]);

            DB::commit();

            toast('Successfully created...','success');

            return redirect()->back()->with('success');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('exception', 'Please try again');
        }

    }
}
