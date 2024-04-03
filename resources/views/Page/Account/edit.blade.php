@extends('layouts.index')
@section('title','帳號編輯')

@section('main')


@if($errors->any())
<div class="rouneded">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>
@endif

<form  action="{{ route('Account.update', ['id' => $user->id])}}"method="post">

    @csrf
    @method('patch')

    <!--錯誤提醒-->
    @if ($errors->has('username'))
    <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('username') }}</strong>
    </span>
    @endif




    部門： <select class = "select" id="did" name="did">
        <option value="{{$user->did}}">{{$dname}}</option>
        @foreach($departments as $department)
          <option value="{{ $department->did }}">{{ $department->dname }}</option>
        @endforeach
      </select>
    <div class="mt-1">
        <label for="name">姓名：</label>
        <input id="name" class="block mt-1 w-full" type="text" name="name" value={{$user->name}} required autofocus autocomplete="name" />
    </div>

    <div class="mt-1">
        <label for="username" >帳號：</label>
        <input id="username" class="block mt-1 w-full" type="text" name="username" value={{$user->username}} required autocomplete="username" />
    </div>

    <div class="mt-1">
        <label for="password">密碼：</label>
        <input id="password" class="block mt-1 w-full" type="password" name="password"  value={{$user->password}} required autocomplete="new-password" />
    </div>


<!--
    <div class="mt-4">
        <label for="password_confirmation" >確認密碼：</label>
        <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
    </div>
    -->
    <div>
    <button class="m-2" type ="button" onclick="location.href='{{route('AccountList')}}'">返回</button>
    <button class="m-2">送出</button>

</form>
  <form action="{{ route('Account.destroy', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('確定要刪除此帳號嗎?');">
        @csrf
        @method('DELETE')
        <button class="m-2 badge-danger" type="submit">刪除</button>
    </form>
    </div>
@endsection

@section('script')

@endsection


