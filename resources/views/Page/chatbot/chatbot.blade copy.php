@extends('layouts.index')
@section('title','客服機器人')
@section('css')
   <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{asset('js/app.js')}}">


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
                <div class="p-2 pb-6 max-w-lg mx-auto">
                    <p class="text-lg"><span class="text-gray-800 font-medium">使用者:{{Auth::user()->name}}</span></p>
                    <p class="truncate"><a  class="text-blue-500">問題標題(後端回傳)</a></p>
                </div>
            </div>

            <div class="max-w-lg mx-auto mt-4">
                <div class="relative flex items-start p-4 space-x-3 bg-white shadow group rounded-2xl">
                    <div class="flex-1 min-w-0">
                        <div id="response"></div>{{--測試--}}
                        <div class="pb-10 space-y-4 h-[60vh] overflow-scroll" id="messages">
                           {{--@foreach($messages as $message)//後端回傳訊息
                            @if($message->role == "user")//判斷是否為使用者--}}
                            <div class="ml-16 flex justify-end">
                                <di class="bg-gray-100 p-3 rounded-md">
                                    <p class="font-medium text-blue-500 text-right text-sm">{{Auth::user()->name}}</p>
                                    <hr class="my-2" />
                                    <p class="text-gray-800  text-right text-sm">問題$message->content</p>
                                </di>
                            </div>
                           {{-- @else//否為機器人回答--}}
                            <div class="bg-gray-100 p-2 rounded-md mr-16">
                                <p class="font-medium text-blue-500 text-sm">客服機器人</p>
                                <hr class="my-2" />
                                <p class="text-gray-800">回答$message->content</p>
                            </div>
                           {{-- @endif
                            @endforeach--}}
                        </div>

                         <form class="flex gap-2 pt-2" id="form-question">
                            @csrf
                            <input type="hidden" class="content"name="content" value="$chat->id" />
                            <input placeholder="輸入傳送訊息!" id="message" name="message" class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" />
                            <button class=" btn btn-primary pull-right" id="chatForm" type="button" >送出</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@section('script')
<script>
    $('#chatForm').submit(function (event) {
    event.preventDefault(); // 防止預設送出行為
    var userInput = document.getElementById('message').value;
        fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // 验证 CSRF 令牌
            },
            body: JSON.stringify({ message: userInput })
        })
        .then(response => response.json())
        .then(data => {
            // 清空輸入
            document.getElementById('message').value = '';

            // 更新 messages 區塊來显示新消息
            var messages = document.getElementById('messages');
            var userMessageElement = '<div class="ml-16 flex justify-end">...User Message...</div>';
            var botMessageElement = '<div>...Bot Response...</div>'; // 您需要创建适合您布局的消息元素
            messages.innerHTML += userMessageElement; // 添加用户消息
            messages.innerHTML += botMessageElement; // 添加機器人回答

            // 滚动到最新消息
            messages.scrollTop = messages.scrollHeight;
        })
        .catch((error) => {
            console.error('Error:', error);
        });

});

// 模擬打字效果
function simulateTyping(sender) {
    var chatDiv = $('#chatbot-message');
    var typingClass = sender === 'bot' ? 'bot-typing' : 'user-typing';
    if (!chatDiv.hasClass(typingClass)) {
        chatDiv.append('<div class="message ' + typingClass + '"></div>');
    }
}

// 停止打字效果
function stopTypingAnimation(sender) {
    var chatDiv = $('#chatbot-message');
    var typingClass = sender === 'bot' ? '.bot-typing' : '.user-typing';
    chatDiv.find(typingClass).remove();
}

</script>
@endsection