<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\TicketChat;
use App\Models\CreateTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EcommerceContactUs extends Controller
{
  public function index()
  {
    return view('content.apps.app-ecommerce-contactus');
  }
  public function contactusList(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length"); // Rows display per page

    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    $searchValue = $search_arr['value']; // Search value

    // Fetch total records
    $totalRecords = CreateTicket::count();

    // Fetch records with filters
    $query = CreateTicket::with('user')
      ->select('create_tickets.id', 'create_tickets.user_id', 'create_tickets.order_id', 'create_tickets.message', 'create_tickets.status', 'users.username', 'users.email', 'users.image')
      ->join('users', 'users.id', '=', 'create_tickets.user_id');

    if (!empty($searchValue)) {
      $query->where(function ($query) use ($searchValue) {
        $query->where('create_tickets.message', 'like', '%' . $searchValue . '%')
          ->orWhere('users.name', 'like', '%' . $searchValue . '%')
          ->orWhere('create_tickets.order_id', 'like', '%' . $searchValue . '%');
      });
    }

    $totalRecordswithFilter = $query->count();

    $records = $query->orderBy($columnName, $columnSortOrder)
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "user_id" => $record->user_id,
        "username" => $record->user->username,
        "email" => $record->user->email,
        "image" => $record->user->image,
        "order_id" => $record->order_id,
        "message" => $record->message,
        "status" => $record->status,
        "action" => ''
      );
    }

    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordswithFilter,
      "aaData" => $data_arr
    );

    echo json_encode($response);
    exit;
  }

  public function userReply(Request $request, $id = null)
  {
    $this->validate($request, [
      'message' => 'required',
    ], [
      'message.required' => 'Please write a message.',
    ]);

    $imageName = null;
    if ($request->hasFile('image')) {
      $imageName = time() . '.' . $request->image->getClientOriginalName();
      $request->image->move('assets/images/support_images/', $imageName);
    }

    $user = TicketChat::where('user_id', $id)->firstOrFail();

    $data = [
      'ticket_id' => $user->ticket_id,
      'admin_message' => 1,  // Marking as an admin message
      'message' => $request->message,
      'image' => $imageName,
      'created_at' => now(),
      'updated_at' => now(),
    ];

    TicketChat::create($data);

    return redirect()->back()->with('message', 'Message sent successfully');
  }

  public function closeTicket($id)
  {
    CreateTicket::where('user_id', $id)->where('status', 1)->update(['status' => 0]);

    return redirect()->back()->with('message', 'Ticket closed successfully');
  }

  public function chatUser($id)
  {
    $data = TicketChat::select('ticket_chat.ticket_id', 'create_tickets.status', 'ticket_chat.message')
      ->leftJoin('create_tickets', 'create_tickets.id', '=', 'ticket_chat.ticket_id')
      ->where('create_tickets.status', 1)
      ->first();

    $chat = TicketChat::where('from_user', $id)->orderBy('id', 'desc')->get();
    $chatAdmin = TicketChat::where('from_user', Auth::guard('admin')->user()->id)->where('to_user', $id)->get();

    return view('content.apps.app-email', compact('data', 'chat', 'chatAdmin'));
  }
}
