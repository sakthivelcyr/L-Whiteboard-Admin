<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{
    //
    function index()
    {
        //echo view("admin.templates.header");
        return view("admin.index");
        $commentData = 'sd';
        //return View::make('admin.templates.header')->with('admin.index', $commentData);

    }

    function customer()
    {
        $data = DB::table('customer')->get();
        return view('admin.customers', ['customers' => $data]);
        //echo $data;
    }
}
