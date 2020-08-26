<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Login extends Controller
{
    //
    function index(Request $req)
    {
        $req->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);        
        
        $check = DB::select('select email,password from user where email=? and password=?', [$req->input('email'),md5($req->input('password'))]);

        if($check) {
            $req->session()->put('data',$req->input());
            return redirect('index');
        }
        else {
            $req->session()->flash('message', 'Wrong email id and password');
            return redirect()->back();
        }   
    }
}
