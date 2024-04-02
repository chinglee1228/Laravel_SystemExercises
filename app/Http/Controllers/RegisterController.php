<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function index(){
        return view("register");
    }
    public function create(){
        return view("register");
    }
    public function store(Request $request){
       
        Register::create($request->validated());

        return redirect()->route('/dashboard');
    }
}
