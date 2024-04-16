

##  Laravel 系統練習
製作了會員系統，並且透過權限僅管理員可以新增帳號
-使用datatalbe
*利用chatgpt並且連接pinecone讀取文檔製作chatbot
##  安裝專案
1.
```
composer install
```


2. 設定 `.env` 檔案

```
OPENAI_API_KEY=

PINECONE_API_KEY=
PINECONE_ENVIRONMENT=

PINECONE_INDEX_NAME=

```
- 造訪[openai](https://help.openai.com/en/articles/4936850-where-do-i-find-my-secret-api-key) 檢索API 金鑰並插入到您的`.env ` 中文件。
- 存取 [pinecone](https://pinecone.io/) 建立和擷取您的 API 金鑰，並從儀表板擷取您的環境和索引名稱。
##  執行
1.
```
run npm dev
```
2.
```
php artisan serve
```
