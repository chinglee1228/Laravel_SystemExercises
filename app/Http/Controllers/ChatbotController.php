<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;

use App\Helpers\ServerEvent;
use App\Models\Chat;
use App\Models\Message;
use App\Service\QueryEmbedding;
use App\Service\PineconeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class ChatbotController extends Controller
{
    //
    private $openAiService;
    protected $query;

    protected  $pinecone;

    public function __construct(QueryEmbedding $query , PineconeService $pinecone)
    {
        $this->query = $query;
        $this->pinecone = $pinecone;

    }

    public function index(){

        return view("Page/Chatbot/chatbot");
    }
    public function show()
    {
        $chat_id = '1';

        $chat = Chat::where('id',$chat_id)->first();
        return view('Page/Chatbot/chatbot', [
            'chat' => $chat,
            'messages' => Message::query()->where('chat_id', $chat->id)->get()
        ]);
    }


    public function chat(Request $request)
    {
        try {
            $question = $request->question; // 接收到的訊息
            // 送到AI模型處理, 假设 sendToAIModel 方法会返回 AI 的回答
            $response = $this->sendToAIModel($question);
            // 保存用户的问题和 AI 的回答
            return response()->json([
                //'question' => $question,
                //'answer' => $response,
                //'status' =>'processing',
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendToAIModel(Request $request)
    {
        set_time_limit(0);// 设置脚本无超时限制
        return response()->stream(function () use ($request) {
            try{
                $chat_id = '1';
                $question = $request->question;
                $context = $this->pinecone->GetRelevantContent($question)?? [];//查詢pinecone取得相關內容資料
                $stream = $this->query->askQuestion($context, $question);//使用查詢到的資料進行問答
                //$stream = $this->query->StrealineQuestion($question);//返回精簡問題
                $message = '';//初始化訊息

                foreach ($stream as $response) {
                    $context= $response->choices[0]->delta->content;
                    $message .= $context; // 将格式化后的内容追加到整体消息中
                    $formatted_message = nl2br(trim($message));//將單條的訊息格式化
                    if (strpos($formatted_message, '：') !== false) {//檢查是否有冒號
                        //將冒號前的字加上粗體
                        $formatted_message = preg_replace('/([^：\n]+)：/', "<strong>$1：</strong>", $formatted_message);

                    }
                    //檢查連接是否中斷
                    if (connection_aborted()) {
                        break;
                        }

                    ServerEvent::send($context, "");//丟給前端
                    }

                    Message::insert([[
                        'chat_id' => $chat_id,
                        'role' => Message::ROLE_USER,
                        'content' => $question
                    ], [
                        'chat_id' => $chat_id,
                        'role' => Message::ROLE_BOT,
                        'content' => $message
                    ]]);


                } catch (Exception $e) {
                    Log::error($e);
                    ServerEvent::send("客服機器人異常，請洽詢客服專員!");
                    }
                }, 200, [
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'keep-alive',
                    'X-Accel-Buffering' => 'no',//確保不會由於nginx緩衝而延遲訊息
                    'Content-Type' => 'text/event-stream',// 正確設置SSE所需的herder
                ]);
            }
        }
