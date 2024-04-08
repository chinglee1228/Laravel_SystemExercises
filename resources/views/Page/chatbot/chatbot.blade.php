@extends('layouts.index')
@section('title','客服機器人')
@section('css')
   <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')


@endsection
<!--main-->
@section('main')
<button class='m-1 btn-secondary btn'r ole ="button" onclick="location.href='/'">回首頁</button>

<body class="antialiased">
    <section class="flex relative bg-[#f5f5f5] items-center justify-center min-h-screen">
        <div class="relative items-center w-full px-5 mx-auto max-w-7xl md:px-12">
            <div class="text-center">

                <p class="w-auto">
                    <a href="/chat" class="font-semibold text-[#4354ff] text-sm uppercase">客服機器人</a>
                </p>
                {{--
                <div class="p-2 pb-6 max-w-lg mx-auto">
                    <p class="text-lg"><span class="text-gray-800 font-medium">使用者:{{Auth::user()->name}}</span></p>
                </div>--}}
            </div>

            <div class="max-w-lg mx-auto mt-4">
                <div class="relative flex items-start p-4 space-x-3 bg-white shadow group rounded-2xl">
                    <div class="flex-1 min-w-0">
                        <div class="pb-10 space-y-4 h-[60vh] overflow-scroll" id="messages">

                            {{--對話框--}}
                            {{--
                            @foreach($messages as $message)

                            @if($message->role == "user")
                            <div class="ml-16 flex justify-end">
                                <div class="bg-gray-100 p-3 rounded-md">
                                    <p class="font-medium text-blue-500 text-right text-sm">{{Auth::user()->name}}</p>
                                    <hr class="my-2" />
                                    <p class="text-gray-800  text-right text-sm">{{$message->content}}</p>
                                </di>
                            </div>
                            @else
                            <div class="bg-gray-100 p-2 rounded-md mr-16">
                                <p class="font-medium text-blue-500 text-sm">客服機器人</p>
                                <hr class="my-2" />
                                <p class="text-gray-800">{{$message->content}}</p>
                            </div>
                            @endif
                            @endforeach--}}
                        </div>

                         <form class="flex gap-2 pt-2" id="form-question">
                            @csrf
                           <input id="input-question"placeholder="輸入傳送訊息!" name="question"class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" />
                            <button id="btn-submit-question" type="submit"
                            class="bg-black  text-white shadow px-3 rounded-md flex items-center">
                            送出</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@section('script')
<script type="text/javascript">
    var username = "{{ Auth::user()->name }}";//宣告給JS使用
</script>
<script>

// 使用 AJAX 發送問題

/*
    // 在确保 DOM 加载完毕后执行
$(document).ready(function () {
    //設置AJAX請求標頭
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});

    $('#form-question').submit(function (e) {
        // 阻止表单默认提交行为
        e.preventDefault();
        // 取得輸入的問題
        var questionText = $('input[name="question"]').val();

        // 顯示問題
        appendMessage('user', questionText);
        $('input[name="question"]').val('');
        scrollToBottom('messages');//滾動到最新消息
        // 发送异步请求到服务器端
        $.ajax({
            type: 'POST',
            url: 'chat/getchat', // 替换为你的处理器路径
            data: {question: questionText},
            success: function (response) {
                //appendMessage('user', response.question);
                appendMessage('bot', response.answer);


            },
            error: function (xhr,status,error) {
                // 处理错误
                console.error("錯誤發生: " + error);
                console.error(xhr.responseText);
            }
        });
    });
});
//判斷訊息來源且顯示
function appendMessage(sender, content) {
    //var username = {{Auth::user()->name}};
    var senderName = sender === 'user' ? '{{Auth::user()->name}}' : '客服機器人';
    var divClass = sender === 'user' ? 'ml-16 flex justify-end' : 'mr-16';
    var messageElement = `
        <div class="${divClass}">
            <div class="bg-gray-100 p-3 rounded-md">
                <p class="font-medium text-blue-500 text-sm">${senderName}</p>
                <hr class="my-2" />
                <p class="text-gray-800 text-sm">${content}</p>
            </div>
        </div>
    `;
    $('#messages').append(messageElement);
}
// 滚动到指定元素的底部
function scrollToBottom(id) {
    var element = document.getElementById(id);
    element.scrollTop = element.scrollHeight;
}*/
</script>
@endsection