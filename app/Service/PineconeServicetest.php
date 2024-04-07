<?php
// app/Services/PineconeService.php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Exception;
use \Probots\Pinecone\Client as Pinecone;

class PineconeService
{
    private $apiKey;
    private $indexName;
    private $baseUri ;

    public function __construct()
    {
        $this->apiKey = env('PINECONE_API_KEY');
        $this->indexName = env('PINECONE_INDEX_NAME');
        $this->baseUri = env('PINECONE_INDEX_HOST');

        // 如果 Pinecone index URL 不同，記得更新 $baseUri
    }

    public function verifyConnection($queryVectors)
    {
        $pinecone = new Pinecone($this->apiKey);
        $pinecone->setIndexHost($this->baseUri);
        try{
            $response = $pinecone->data()->vectors()->query(
            namespace: 'pdf-test',
            vector: $queryVectors,
            topK: 5,
            //includeValues: true,

            //includeMetadata: true,
        );


        if ($response->ok()) {
            return $response->json();
        } else {
            throw new Exception("Pinecone: " . $response->body());
        }
        }
        catch (Exception $e) {
            // Handle exception
            return $e->getMessage();
        }


        //return $response;





        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->baseUri . "/indexes/{$this->indexName}");

            // 检查响应状态是否为200
            if ($response->ok()) {
                return $response->json();
            } else {
                throw new Exception("Unable to connect to Pinecone: " . $response->body());
            }
        } catch (Exception $e) {
            // Handle exception
            return $e->getMessage();
        }
    }
}
