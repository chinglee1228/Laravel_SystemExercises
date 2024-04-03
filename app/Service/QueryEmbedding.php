<?php

namespace App\Service;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class QueryEmbedding
{

    public function getQueryEmbedding($question): array
    {
        $result = OpenAI::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $question,
        ]);

        if (count($result['data']) == 0) {
            throw new Exception("無法產生查詢嵌入!");
        }

        return $result['data'][0]['embedding'];
    }


    public function askQuestionStreamed($context, $question)
    {
        $condensed_question_template = "
        鑑於以下對話和後續問題，將後續問題改寫為一個獨立的問題。
        <chat_history>
            {chat_history}
        </chat_history>

        Follow Up Input: {question}
        Standalone question:";
        $system_QA_template = "
        您是一位遊戲客服人員。 使用以下上下文來回答最後的問題。
        如果你不知道答案，請說'對不起，我無法回答您的問題，麻煩請詢問線上客服人員'。 不要試圖編造答案。
        如果問題與上下文或聊天歷史記錄無關，請禮貌地回答您只回答與上下文相關的問題。
        若是回答過於模糊請提供相關的關鍵字供使用者提問。
        ----------------
        <context>
        {context}
        </context>

        <chat_history>
            {chat_history}
        </chat_history>

        Question: {question}
        Helpful answer in markdown:
        ";
        // 替換上下文
        $system_prompt = str_replace("{context}", $context, $system_QA_template);

        return OpenAI::chat()->createStreamed([
            'model' => 'gpt-4', // 使用的模型
            'temperature' => 0.8,
            'max_tokens' => 2048,
            'top_p' => 1,//一个从 0 到 1 的浮点数，越低越保守，越高越創造性高
            'n' => 1,
            'stream' => false, //如果设置为真，则以流的形式逐渐返回结果
            'logprobs' => null,//返回模型关于其预测的特定令牌的概率分数
            'stop' => ["\n", "END"], // 可以定义多个结束符号
            'presence_penalty' => 0, //推动模型生成出现次数比较少的话题的值。
            'frequency_penalty' => 0, //减少模型重复相同内容的倾向的值。
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                //['role' => 'system', 'content' => $condensed_question_template], // 精簡版问题提示
                ['role' => 'user', 'content' => $question],
            ],
        ]);
    }
}
