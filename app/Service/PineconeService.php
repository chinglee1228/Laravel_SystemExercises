<?php

namespace App\Service;

use Exception;
use \Probots\Pinecone\Client as Pinecone;
use App\Service\QueryEmbedding;

class PineconeService
{
    protected QueryEmbedding $query;
    private $apiKey;
    private $indexName;
    private $baseUri ;


    public function __construct(QueryEmbedding $query )

    {
        $this->query = $query;
        $this->apiKey = env('PINECONE_API_KEY');
        $this->indexName = env('PINECONE_INDEX_NAME');
        $this->baseUri = env('PINECONE_INDEX_HOST');
        /*
        $this->client = new Client([
            'base_uri' => $this->indexUrl,
            'headers' => [
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);*/
    }

    public function GetRelevantContent($question)
    {
        //$question = "物品遺失";//搜尋測試
        //$question = $this->query->StrealineQuestion($question);//將使用者問題精簡化
        $queryVectors = $this->query->getQueryEmbedding($question);//將問題轉換成向量
        $content = $this->queryPinecone($queryVectors);//查詢pinecone
        $text = $this->ExtractText($content);//提取text
        return $text;
    }

    public function queryPinecone($queryVectors)
    {
        $pinecone = new Pinecone($this->apiKey);
        $pinecone->setIndexHost($this->baseUri);
        try {
            $response = $pinecone->data()->vectors()->query(
            namespace: 'pdf-test',
            vector:$queryVectors,
            topK: 1,
                //includeValues: true,
                //includeMetadata: true,
            );

            // 返回查詢結果
            if ($response->ok()) {
                return $response->json();
            } else {
                throw new Exception("Pinecone: " . $response->body());
            }
        } catch (Exception $e) {
            // 處理任何異常情況並返回錯誤響應
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }



    }


    public function ExtractText($content)//提取text
    {
        //提取text
        foreach ($content['matches'] as $result) {

                $text = $result['metadata']['text'] ?? $content;
        }
        return $text;


    }
}








