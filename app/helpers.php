<?php
use App\Models\DocumentTracking;
use App\Models\Outgoing;
use App\Models\Terminal;
use Carbon\Carbon;

function receivedTotal()
{
    $terminal = Terminal::with('user')->where('user_id', auth()->user()->id)->first();

    if($terminal == null) return 0;

    $total = DocumentTracking::where('terminal_id', $terminal->id)
    ->where('status', 'received')
    ->get()
    ->count();

    return $total;
}

function rejectedTotal()
{
    $total = DocumentTracking::where('terminal_id', auth()->user()->terminal_id)
    ->where('status', 'rejected')
    ->get()
    ->count();
    return $total;
}

function incomingTotal()
{
    $terminal = Terminal::where('user_id', auth()->user()->id)->first();

    if($terminal==null) return 0;

    $total = DocumentTracking::where('terminal_id', $terminal->id)
    ->where('status', 'incoming')
    ->get()
    ->count();

    return $total;
}

function outgoingTotal()
{
    $total = Outgoing::with('user')->get()->filter(function($o){
                    return $o->user->office_id == auth()->user()->office_id;
                })->count();
    return $total;
}

// check if prop exist
function isExist($obj, $prop) {
    return isset($obj[$prop])? $obj[$prop]:'';
}

// It converts string JSON to Array
function jsonToPHPArray($data) {
    $data = str_replace("'", '"', $data);
    $phpArray = json_decode($data, true);

    return $phpArray;
}

// Calculates the number of days from start to current date
function getNumDays($date){
    $startDate = Carbon::parse($date);
    $currentDate = Carbon::parse(now());

    return $startDate->diffInDays($currentDate);
}

// Checks the status of the  document and returns a color code.
function getTransactionStatus($documentDetail) {
    $type = [
        '1' => 3, //simple
        '2' => 7, // complex
        '3' => 21 // highly technical
    ];

    $transaction_type = $documentDetail->transaction_type;
    if (!array_key_exists($transaction_type, $type)) "<span class='badge rounded-pill bg-secondary'>Invalid Transaction Type</span>";

    $numDays = getNumDays($documentDetail->created_at);
    $maxDays = $type[$transaction_type];
    $mid = floor($maxDays/2);

    if(($numDays >= 0 && $numDays <= $mid) || $numDays<0) {
        return "<span class='badge rounded-pill bg-success'>$numDays day/s</span>";
    } else if($numDays > $mid && $numDays >= $maxDays) {
        return "<span class='badge rounded-pill bg-danger'>$numDays day/s</span>";
    } else {
        return "<span class='badge rounded-pill bg-warning'>$numDays day/s</span>";
    }
}

function bgColorStatus($status) {
    $status = strtolower($status);
    $bg = [
        'completed' => 'bg-success',
        'received'  => 'bg-success',
        'incoming'  => 'bg-warning'
    ];

    return isset($bg[$status])? $bg[$status] : '';
}

?>
