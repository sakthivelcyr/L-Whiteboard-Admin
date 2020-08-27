<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Customer;
use Carbon\Carbon;

class Apis extends Controller
{
    //
    public function register(Request $request) {
       
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        $validator = array(
            'status' => false,
            'messages' => array()
        );

        $isEmailExist  = false;
        $isMobileExist = false;

        $name = $request
            ->input('name');
        $role = $request
            ->input('role');    
        $college = $request
            ->input('college');    
        $mobile = $request
            ->input('mobile');        
        $state = $request
            ->input('state');
        $district = $request
            ->input('district');        
        $email = $request
            ->input('email');
        $profileUrl = $request
            ->input('profileUrl');
        $imei = $request
            ->input('imei');
        $firebaseToken = $request
            ->input('firebaseToken');        
        $device_latitude = $request
            ->input('device_latitude');        
        $device_longitude = $request
            ->input('device_longitude');                
        
        if (empty($name)) array_push($validator['messages'], "Please enter your name.");
        if (empty($role)) array_push($validator['messages'], "Please enter your role.");
        if (empty($college)) array_push($validator['messages'], "Please enter your college.");
        if (empty($state)) array_push($validator['messages'], "Please enter your state.");
        if (empty($district)) array_push($validator['messages'], "Please enter your district.");
        
        if (empty($profileUrl)) array_push($validator['messages'], "Profile Url has not been provided.");
        if (empty($imei)) array_push($validator['messages'], "IMEI has not been provided.");
        if (empty($firebaseToken)) array_push($validator['messages'], "Firebase token has not been provided.");        
        if (empty($device_longitude)) array_push($validator['messages'], "Device longitude has not been provided.");        
        if (empty($device_latitude)) array_push($validator['messages'], "Device latitude has not been provided.");        

        if (empty($mobile)) array_push($validator['messages'], "Please enter your mobile number.");
        else if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
            array_push($validator['messages'], "Mobile number is not in correct format.");
        } 
        else {           
            
            $check = DB::select('select mobile from customer where mobile=?', [$mobile]);
            if($check)
                $isMobileExist = true;            
        }

        if (empty($email)) array_push($validator['messages'], "Please enter your email.");
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($validator['messages'], "Email is not in correct format.");
        else {
            $customer = DB::table('customer')->where('email',$email)->exists();                        
            if($customer)
                $isEmailExist = true;                           
        }
        
        if (empty($validator['messages'])) {
        if ($isEmailExist == true) {            
            $data = array(
                'name' => $name,
                'mobile' => $mobile,
                'state' => $state,
                'district' => $district,
                'role' => $role,
                'email' => $email,
                'profileUrl' => $profileUrl,
                'imei' => $imei,
                'firebaseToken' => $firebaseToken,
                'college' => $college,  
                'device_latitude' => $device_latitude,
                'device_longitude' => $device_longitude,
                'updated_at' => Carbon::now()->toDateTimeString(),                   
                'deviceToken' => bin2hex(random_bytes(12))
            );            
            $customer = DB::table('customer')->where('email',$email)->first();
                
                $affected = DB::table('customer')
                ->where('email', $customer->email)
                ->update($data);

            if ($affected) {
                $validator['status'] = true;
                array_push($validator['messages'], "Successfully Updated");
            } else {
                log_message('debug', 'sql query fail in /register ', false);
                array_push($validator['messages'], "Error while adding the member information");
            }
        } else {
            if (empty($validator['messages'])) {

                $data = [
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'state' => $state,
                    'role' => $role,
                    'college' => $college,
                    'district' => $district,                    
                    'profileUrl' => $profileUrl,
                    'imei' => $imei,    
                    'device_latitude' => $device_latitude,
                    'device_longitude' => $device_longitude,           
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'firebaseToken' => $firebaseToken,                    
                    'deviceToken' => bin2hex(random_bytes(12)), 
                    'updated_at' => Carbon::now()->toDateTimeString(),                   
                ];                                
                $check = DB::table('customer')->insert($data);
                if ($check) {
                    $validator['status'] = true;
                    array_push($validator['messages'], "Successfully Added");
                } else {
                    log_message('debug', 'sql query fail in /register ', false);
                    array_push($validator['messages'], "Error while adding the member information");
                }
            } else {
                log_message('debug', 'sql query fail in /register ', false);
            }
        }
        if ($validator['status']) {
            $validator['deviceToken'] = $data['deviceToken'];
        }
        }
        echo json_encode($validator);        
        
    }  
}
