<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mpesa;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        if (Auth()->user()->role == 'Admin') {
            $courses = Student::join('users', 'users.id', '=', 'students.user_id')->join('courses', 'courses.unit_code', '=', 'students.course_code')
                ->select('users.name', 'users.contact', 'users.email', 'users.residence', 'students.fee', 'students.paid', 'students.course_code', 'students.cohort', 'students.created_at', 'students.updated_at', 'courses.title', 'courses.duration', 'courses.category', 'students.id')->get();
        } else {
            $courses = Student::where('user_id', Auth()->user()->id)->get();
        }
        $data = [
            'items' => $courses
        ];
        return view('courses', $data);
    }
    public function create($id)
    {
        $course = Course::find($id);
        $student = Student::where('user_id', Auth()->user()->id)->where('course_code', $course->unit_code)->first();
        if (!$student) {
            Student::create([
                'user_id' => Auth()->user()->id,
                'course_code' => $course->unit_code,
                'fee' => $course->fee,
                'cohort' => request()->cohort,
                'paid' => 0,
            ]);
            $data = [
                'unit_code' => $course->unit_code,
                'fee' => $course->fee,
            ];
            $paid = 0;
        } else {
            $paid = $student->paid;
        }
        $data = [
            'unit_code' => $course->unit_code,
            'fee' => ($course->fee) - $paid,
        ];
        return view('pay', $data);
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        $fees = [];
        $student = Student::where('user_id', Auth()->user()->id)->get();
        foreach ($student as $s) {
            if (Auth()->user()->role == 'Admin') {
                $fee = Mpesa::join('students', 'students.id', '=', 'mpesas.Student_id')->join('users', 'users.id', '=', 'students.user_id')->select('mpesas.*', 'students.course_code', 'users.name')->get();
            } else {
                $fee = Mpesa::join('students', 'students.id', '=', 'mpesas.Student_id')->join('users', 'users.id', '=', 'students.user_id')->where('users.id', Auth()->user()->id)->select('mpesas.*', 'students.course_code', 'users.name')->get();
            }
            foreach ($fee as $f) {
                array_push($fees, $f);
            }
        }

        //  return $fees;
        $data = [
            'items' => $fees
        ];
        return view('fee', $data);
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
        Log::channel('mpesaSuccess')->info(
            json_encode(
                ['whole' => $res['Body']]
            )
        );
        // Log::channel('mpesaSuccess')->info(
        //     json_encode(
        //         [
        //             'message' => $message,
        //             'amount' => $amount,
        //             'phone' => $phne,
        //             'date' => $date,
        //             'whole' => $res['Body']
        //         ]
        //     )
        // );
        if ($res['Body']['stkCallback']['ResultCode'] == 0) {
            
            $message = $res['Body']['stkCallback']['ResultDesc'];
            $amount = $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
            $TransactionId = $res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
            $date = $res['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value'];
            $phne = $res['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];

            Mpesa::create([
                'TransactionType' => 'Paybill',
                'Student_id' => $id,
                'TransAmount' => $amount,
                'MpesaReceiptNumber' => $TransactionId,
                'TransactionDate' => $date,
                'PhoneNumber' => '+' . $phne,
                'response' => $message
            ]);
            $student = Student::find($id);
            $student->paid += $amount;
            $student->update();
        } else {
            Log::channel('mpesaErrors')->info((json_encode($res['Body']['stkCallback']['ResultDesc'])));
        }
        $response = new Response();
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }
    function Pay($codec)
    {
        $phone = request()->contact;
        $amount = request()->amount;
        $course = Student::where('course_code', $codec)->where('user_id', Auth()->user()->id)->first();
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
            'AccountReference' => $codec . ' Course Payment of id ' . $id,
            'TransactionDesc' => $codec . ' Course Payment ' . $id,
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
            return redirect()->back()->withInput()->with('message', "Error. Try again.");
        }
    }
    function update($id)
    {
        $course = Student::find($id);

        $data = [
            'unit_code' => $course->course_code,
            'fee' => ($course->fee) - ($course->paid),
        ];
        return view('pay', $data);
    }
    public function destroy(Student $student)
    {
        //
    }
}
