@extends('layouts.index')
@section('title','新增帳號')
@section('main')
<button clsaa='m-4' type ="button" onclick="location.href='/'">返回</button>


    
    @if($errors->any())
    <div class="rouneded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>    
            @endforeach
        </ul>
    </div>
    @endif


        <form  action="{{ route('register.store') }}"method="POST">
           
            @csrf
            部門： <select class = "select" id="did" name = "did" autocomplete="new-did" :value="old('did')">
                <option value="">請選擇部門...</option>
                @foreach($departments as $department)
                  <option value={{ $department->did }}>{{ $department->dname }}</option>
                @endforeach
              </select> 
            <div>
                <label for="name">名稱：</label>
                <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-1">
                <label for="username" >帳號：</label>
                <input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            </div>

            <div class="mt-1">
                <label for="password">密碼：</label>
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>
<!--
            <div class="mt-4">
                <label for="password_confirmation" >確認密碼：</label>
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            -->

                <button class="m-2">送出</button>
          
        </form>

        @stop