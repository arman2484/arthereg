<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Notification extends Controller
{
  public function index()
  {
    return view('content.apps.app-notification');
  }
  public function add(Request $request)
  {
    // dd($request->all());
    $rules = [
      'title' => 'required',
      'message' => 'required'
    ];

    $customMessages = [
      'title.required' => 'Please enter title.',
      'message.required' => 'Please enter message.'
    ];
    $this->validate($request, $rules, $customMessages);

    $users = User::all();

    foreach ($users as $user) {
      $notification = new Notifications([
        'title' => $request->title,
        'message' => $request->message,
        'sender_id' => $request->sender_id,
      ]);
      $FcmToken = User::select('device_token')->where('id', $user->id)->first()->device_token;
      $data = [
        "registration_ids" => array($FcmToken),
        "notification" => [
          "title" => $request->title,
          "message" => $request->message,
          "sender_id" => $request->sender_id,
        ],
        "data" => [
          "title" => "match_user",
          'sender_id' =>  $request->sender_id,
          'message' => $request->message,
          'user_id' => $user->id
        ]
      ];

      // dd($data);
      $this->sendNotification($data);

      // dd("asdf");
      $user->notifications()->save($notification);
    }
    // echo "<pre>";
    // print_r($data);
    // die;
    return redirect()->back()->with('message', 'Notification added successfully');
  }
  function sendNotification($data)
  {
    $url = 'https://fcm.googleapis.com/fcm/send';

    // $serverKey = getenv('FIREBSE_SERVERKEY');
    $serverKey = 'AAAAX26WaPo:APA91bFF8vpxBlzyLUzetmH_ytImj3iJ-9cASib10YymuwuMF_SmDvEo4kcZHQzlRYBLEE5_8ud-K1rdrusQe7gSess0bcDGJuKAVNNeB9ls3NmJAoQ3BMSIfNVsAb4yJdqbVV9ngSAk';
    $encodedData = json_encode($data);
    $headers = [
      'Authorization:key=' . $serverKey,
      'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
      die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
    return true;

    // // FCM response
    // echo "--";
    // print_r($result);
    // echo "--";
  }
  public function notificationList()
  {
    return view('content.apps.app-ecommerce-notification-list');
  }
  public function getNotificationListData(Request $request)
  {
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length");
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');
    $columnIndex = $columnIndex_arr[0]['column'];
    $columnName = $columnName_arr[$columnIndex]['data'];
    $columnSortOrder = $order_arr[0]['dir'];
    $searchValue = $search_arr['value'];
    $totalRecords = Notifications::select('count(*) as allcount')->count();
    $totalRecordswithFilter = Notifications::select('count(*) as allcount')->where('title', 'like', '%' . $searchValue . '%')->count();
    // dd($columnName, $columnSortOrder);
    $records = Notifications::orderBy($columnName, $columnSortOrder)
      ->where('title', 'like', '%' . $searchValue . '%')
      ->skip($start)
      ->take($rowperpage)
      ->get();
    $data_arr = array();
    foreach ($records as $record) {
      $data_arr[] = array(
        "id" => $record->id,
        "created_at" => Carbon::parse($record->created_at)->format('l, j M'),
        "title" => $record->title,
        "message" => $record->message,
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
}
