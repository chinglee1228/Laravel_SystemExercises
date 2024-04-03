@extends('layouts.index')
@section('title','主頁')
@section('main')
@section('css')

@endsection


    @if(session('success'))
    <div id="auto" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div>
    <!--<button type ="button" onclick="location.href='r'">註冊</button>-->
    @if (Auth::user()->did=='2')
    <button type ="button" onclick="location.href='{{url('register')}}'">帳號註冊</button>
    <button type ="button" onclick="location.href='{{url('AccountList')}}'">帳號列表</button>
    <button type ="button" onclick="location.href='{{url('chat')}}'">聊天機器人</button>


    @endif
</div>

@section('script')

<script type="text/javascript">
    $(document).ready(function() {
        // 定時5秒關閉
        setTimeout(function() {
            $('#auto').alert('close');
        }, 3000); // 时间设置为5秒
    });
</script>
@endsection
@stop