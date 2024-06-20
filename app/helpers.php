<?php
use App\Models\DocumentTracking;
use App\Models\Outgoing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

function totalDocQuery() {
    $userId = auth()->id();
    $terminal_id = isset(auth()->user()->terminal->id) ? auth()->user()->terminal->id : false;

    if ($terminal_id == false) return 0;

    $results = DB::table('document_trackings')
    ->selectRaw('
        (SELECT COUNT(id) FROM document_trackings WHERE terminal_id = ? AND status = ?) as received_count,
        (SELECT COUNT(id) FROM document_trackings WHERE terminal_id = ? AND status = ?) as incoming_count,
        (SELECT COUNT(id) FROM outgoings WHERE user_id = ?) as outgoing_count
    ', [
        $terminal_id, 'received',
        $terminal_id, 'incoming',
        $userId,
    ])
    ->first();

    return [
        'received_count' => $results->received_count,
        'incoming_count' => $results->incoming_count,
        'outgoing_count' => $results->outgoing_count,
    ];
}

function receivedTotal()
{
    $terminal_id = isset(auth()->user()->terminal->id) ? auth()->user()->terminal->id : false;
    if ($terminal_id == false) {
        return 0;
    }

    return DocumentTracking::where('terminal_id', $terminal_id)
        ->where('status', 'received')
        ->count('id');

}

function incomingTotal()
{
    $terminal_id = isset(auth()->user()->terminal->id) ? auth()->user()->terminal->id : false;
    if ($terminal_id == false) {
        return 0;
    }

    return DocumentTracking::where('terminal_id', $terminal_id)
        ->where('status', 'incoming')
        ->count('id');
}

function outgoingTotal()
{
    $userId = auth()->id();
    return Outgoing::whereHas('user', function ($query) use ($userId) {
        $query->where('id', $userId);
    })->count('id');
}

// check if prop exist
function isExist($obj, $prop)
{
    return isset($obj[$prop]) ? $obj[$prop] : '';
}

// It converts string JSON to Array
function jsonToPHPArray($data)
{
    $data = str_replace("'", '"', $data);
    $phpArray = json_decode($data, true);

    return $phpArray;
}

// Calculates the number of days from start to current date
function getNumDays($date)
{
    $startDate = Carbon::parse($date);
    $currentDate = Carbon::parse(now());

    return $startDate->diffInDays($currentDate);
}

// Checks the status of the  document and returns a color code.
function getTransactionStatus($documentDetail)
{
    $type = [
        '1' => 3, //simple
        '2' => 7, // complex
        '3' => 21, // highly technical
    ];

    $transaction_type = $documentDetail->transaction_type;
    if (!array_key_exists($transaction_type, $type)) {
        "<span class='badge rounded-pill bg-secondary'>Invalid Transaction Type</span>";
    }

    $numDays = getNumDays($documentDetail->created_at);
    $maxDays = $type[$transaction_type];
    $mid = floor($maxDays / 2);

    if (($numDays >= 0 && $numDays <= $mid) || $numDays < 0) {
        return "<span class='badge rounded-pill bg-success'>$numDays day/s</span>";
    } else if ($numDays > $mid && $numDays >= $maxDays) {
        return "<span class='badge rounded-pill bg-danger'>$numDays day/s</span>";
    } else {
        return "<span class='badge rounded-pill bg-warning'>$numDays day/s</span>";
    }
}

function bgColorStatus($status)
{
    $status = strtolower($status);
    $bg = [
        'completed' => 'bg-success',
        'received' => 'bg-success',
        'incoming' => 'bg-warning',
    ];

    return isset($bg[$status]) ? $bg[$status] : '';
}

function formatDateTime($date)
{
    $datetime = new DateTime($date);
    $formattedDateTime = $datetime->format('F j, Y g:i A');

    return $formattedDateTime;
}

// function make_excerpt($text, $length = 100, $suffix = '...')
// {
//     if (strlen($text) <= $length) {
//         return $text;
//     }

//     $excerpt = substr($text, 0, $length);

//     $lastSpace = strrpos($excerpt, ' ');
//     if ($lastSpace !== false) {
//         $excerpt = substr($excerpt, 0, $lastSpace);
//     }

//     return $excerpt . $suffix;
// }

function make_excerpt($text, $length = 100, $suffix = '...')
{
    if (strlen($text) <= $length) return $text;

    $excerpt = substr($text, 0, $length);

    $lastSpace = strrpos($excerpt, ' ');
    if ($lastSpace !== false) {
        $excerpt = substr($excerpt, 0, $lastSpace);
    }

    $fullText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $excerpt = htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8');

    return <<<HTML
        <span class="excerpt">{$excerpt}{$suffix}<br></span>
        <span class="full-text d-none">{$fullText}</span>
        <a href="#" class="see-more">See more</a>
        HTML;
}


