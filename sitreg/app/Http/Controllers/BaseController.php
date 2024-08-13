<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BaseController extends Controller
{
   public function sendResponse($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'data' => $result
        ];
        return response()->json($response, 200);
    }
    
     public function sendResponses($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'languages' => $result
        ];
        return response()->json($response, 200);
    }

    public function sendRespo($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'religions' => $result
        ];
        return response()->json($response, 200);
    }
    
    public function sendRes($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'starsigns' => $result
        ];
        return response()->json($response, 200);
    }
    
    public function sendRe($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'interest1' => $result
        ];
        return response()->json($response, 200);
    }

    public function sendRespon($result, $message)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'events' => $result
        ];
        return response()->json($response, 200);
    }

    public function sendResponces($result, $message, $is_view)
    {
        $response = [
            'success' => "true",
            'message' => $message,
            'events' => $result,
            'is_notification' => $is_view,
        ];
        return response()->json($response, 200);
    }
    
    public function sendMessage($message)
    {
        $response = [
            'success' => "true",
            'message' => $message
        ];
        return response()->json($response, 200);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => "false",
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    private function getAllMonths()
    {
        return [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
    }

    private function fillMissingMonths($usersPerYear, $months)
    {
        $data = [];
        foreach ($months as $month) {
            $matchingRecord = $usersPerYear->firstWhere('x', $month);
            $count = $matchingRecord ? $matchingRecord->y : 0;
            $data[] = [
                'x' => $month,
                'y' => $count
            ];
        }
        return $data;
    }

    public function monthlyUsersChart()
    {
        $usersPerYear = DB::table('users')
            ->select(DB::raw('MONTHNAME(created_at) as x'), DB::raw('count(*) as y'), DB::raw('max(created_at) as createdAt'))
            ->where('user_plan', 'Normal')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])->groupBy('x')->orderBy('createdAt')->get();

        $months = $this->getAllMonths();
        $data = $this->fillMissingMonths($usersPerYear, $months);

        return $data;
    }

    public function monthlyVendorChart()
    {
        $usersPerYear = DB::table('users')
            ->select(DB::raw('MONTHNAME(created_at) as x'), DB::raw('count(*) as y'), DB::raw('max(created_at) as createdAt'))
            ->where('user_plan', 'Subsciber User')
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])->groupBy('x')->orderBy('createdAt')->get();

        $months = $this->getAllMonths();
        $data = $this->fillMissingMonths($usersPerYear, $months);

        return $data;
    }

    public function monthlySelling()
    {
        $sellPerYear = DB::table('tickets')
            ->select(DB::raw('MONTHNAME(created_at) as x'), DB::raw('sum(price) as y'), DB::raw('max(created_at) as createdAt'))
            // ->where('order_status', 2)
            ->whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])->groupBy('x')->orderBy('createdAt')->get();

        $months = $this->getAllMonths();
        $data = $this->fillMissingMonths($sellPerYear, $months);

        return $data;
    }


    function sendNotification($data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = getenv('FIREBSE_SERVERKEY');
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

    function sendNotifications($data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        // $serverKey = getenv('FIREBSE_SERVERKEY');
        $serverKey = ('AAAABPJxy7U:APA91bEkqQn9d0nDnP_j_iBCdBv9dtfvFjws2f5SKIHsd3IEXEYAthPcsjcl9261kmYsOIEE7ti5jhaCTGMh8aJ0N_z_o8voAQIqkJDrqHfCPoXwURdYioYwbYMeGfq94NTi2_9ckK3g');
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
}
