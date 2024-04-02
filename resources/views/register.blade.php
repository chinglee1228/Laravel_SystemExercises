<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>註冊</h1>

        <form method="POST" action="{{ route('register') }}">
            <div>
                <label for="name">　　名稱：</label>
                <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <label for="username" value="{{ __('Username') }}">　　帳號：</label>
                <input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <label for="password" value="{{ __('Password') }}">　　密碼：</label>
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label for="password_confirmation" value="{{ __('Confirm Password') }}">確認密碼：</label>
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

                <button class="ms-4">
                    送出
                </button>
            </div>
        </form>


</body>
</html>