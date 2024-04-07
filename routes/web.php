<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PineconeController;

/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//登入就能檢視
Route::middleware([App\Http\Middleware\Authenticate::class])->group(function () {
    //Route::get('/', function () {return view('Page\index');});
    Route::get('/', function () {
        return view('Page.index');
    })->name('index');

    Route::get('index',[RegisterController::class, 'index']);
    Route::get('test', [PineconeController::class,'checkConnection']);//測試pinecone查詢

});

//did為2才能檢視
Route::middleware([App\Http\Middleware\PermissionControl::class])->group(function () {
    Route::get('index',[RegisterController::class, 'index'])->name('root');
    Route::get('register',[RegisterController::class, 'create'])->name('register.create');//註冊頁面
    Route::post('store',[RegisterController::class, 'store'])->name('register.store');//新增註冊
    Route::get('AccountList',[RegisterController::class, 'usertable'])->name('AccountList');//帳號列表
    Route::get('AccountList/getData',[RegisterController::class, 'getData'])->name('getData');
    Route::get('AccountList/{id}',[RegisterController::class,'edit'])->name('Account.edit');
    Route::patch('AccountList/{id}',[RegisterController::class,'update'])->name('Account.update');
    Route::delete('AccountList/{id}',[RegisterController::class,'destroy'])->name('Account.destroy');


    //chatbot
    //Route::get('chat',    [ChatbotController::class, 'index'])->name('chatindex');
    Route::get("chat", [ChatbotController::class, 'show'])->name('chat.index');
    Route::post("chat", [ChatbotController::class, 'sendToAIModel'])->name('chat.store');
   // Route::post("chat/getchat", [ChatbotController::class, 'sendToAIModel'])->name('chat.get');


});







Route::middleware(['auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Route::resource('r', RegisterController::class);
    });
