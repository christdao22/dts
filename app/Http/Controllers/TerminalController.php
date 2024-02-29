<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use App\Models\OfficeTerminal;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminalController extends Controller
{

    public function index()
    {
        $terminals = Terminal::with('office_terminal.office')->with('user')->get();
        $offices = Offices::get();
        $officers = User::get();

        return view('admin.terminal', compact('terminals', 'offices', 'officers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'terminal_name'      => 'required|max:50',
            'officer'            => 'required',
            'selected_offices'   => 'required|array',
            'selected_offices.*' => 'integer',
            'terminalId'         => 'nullable',
            'isEdit'             => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            if($validated['isEdit'] == '1') {
                $terminal_id = $validated['terminalId'];

                Terminal::where('id', $terminal_id)->update([
                    'terminal_name' => $validated['terminal_name'],
                    'user_id'       => $validated['officer'],
                    'updated_at'    => now(), // Use Laravel helper function for current timestamp
                ]);
                OfficeTerminal::where('terminal_id', $terminal_id)->delete();

            } else {
                $terminal_id = Terminal::create([
                    'terminal_name' => $validated['terminal_name'],
                    'user_id'       => $validated['officer'],
                ])->id;
            }

            $officeTerminal = [];
            foreach ($validated['selected_offices'] as $selected_office) {
               $officeTerminal[] = [
                    'office_id'   => $selected_office,
                    'terminal_id' => $terminal_id,
                    'created_at'  => now(),
                ];
            }

            OfficeTerminal::insert($officeTerminal);

            DB::commit();

            $message = $validated['isEdit'] == '1' ? 'Successfully updated...' : 'Successfully created...';
            toast($message, 'success');
            return redirect()->back()->with('success');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('exception', 'Please try again');
        }
    }

    public function show(string $id)
    {
        $terminal = Terminal::with('office_terminal.office')->find($id);
        $terminal['isEdit'] = 1;

        return response()->json(['terminal'=>$terminal]);
    }

    public function destroy(string $id)
    {
        $terminal = Terminal::find($id);
        $terminal->delete();

        toast('Successfully deleted...','success');
        return redirect()->back()->with('success');
    }

    public function getTerminals(?string $q=null )
    {
        $terminalsQuery = Terminal::query();

        if ($q == 'true') {
            $terminalsQuery->whereHas('user', function ($query) {
                $query->where('is_dm', true);
            });
        }

        $terminals = $terminalsQuery->get();
        return response()->json(['terminal'=>$terminals]);
    }

}

