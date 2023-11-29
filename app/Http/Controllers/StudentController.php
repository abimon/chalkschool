<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mpesa;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response ;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function create($id)
    {
        $course=Course::find($id);
        Student::create([
            'user_id'=>Auth()->user()->id,
            'course_code'=>$course->unit_code,
            'fee'=>$course->fee,
            'paid'=>0,
        ]);
        $data=[
            'code'=>$course->unit_code,
            'fee'=>$course->fee,
        ];
        return redirect('/student/payfee',$data);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Student $student)
    {
        //
    }

    public function edit($code)
    {
        
    }
    function generate_token()
    {

        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
    }
    public function lipaNaMpesaPassword()
    {
        $passkey = env('MPESA_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp = date('YmdHis');
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }
    public function Callback($id)
    {
        $res = request();
        if($res['Body']['stkCallback']['ResultCode']==0){
        Log::channel('mpesa')->info(json_encode(['massage'=>$res['Body']['stkCallback']['ResultDesc'],'Amount'=>$res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'],'TransactionId'=>$res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value']]));
        Mpesa::create([
                'TransactionType' => 'Paybill',
                'Student_id' => $id,
                'TransAmount' => $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'],
                'MpesaReceiptNumber' => $res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'],
                'TransactionDate' => $res['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value'],
                'PhoneNumber' => $res['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'],
                'response' => 'Success'
            ]);
            $amount = $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
            $this->update($id,$amount);
        }
        else{
            Log::channel('mpesaErrors')->info((json_encode($res['Body']['stkCallback']['ResultDesc'])));
        }
        $response = new Response();
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }
    function Pay($code)
    {
        $phone = request()->phone;
        $amount=request()->amount;
        $course= Course::where('unit_code')->where('user_id',Auth()->user()->id)->first();
        $id = $course->id;
        $code = str_replace('+', '', substr('254', 0, 1)) . substr('254', 1);
        $originalStr = $phone;
        $prefix = substr($originalStr, 0, 1);
        $contact = str_replace('0', $code, $prefix) . substr($originalStr, 1);
        $url = (env('MPESA_ENV') == 'live') ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generate_token()));
        $curl_post_data = [
            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $contact,
            'PartyB' => env('MPESA_SHORT_CODE'),
            'PhoneNumber' => $contact,
            'CallBackURL' => 'https://school.healthandlifecentre.com/api/fee/callback/' . $id,
            'AccountReference' => $code.' Course Payment',
            'TransactionDesc' => $code.' Course Payment',
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        $res = json_decode($curl_response);
        // return $res;
        if ($res->ResponseCode == 0) {
            return redirect('/');
        } else {
            return redirect()->back()->withInput()->with('message',"Error. Try again.");
        }
    }
    function update($id,$amount)
    {
        Student::where('id',$id)->update([
            'paid' => +$amount,
        ]);
        return redirect()->back();
    }
    public function destroy(Student $student)
    {
        //
    }
}
