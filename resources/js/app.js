import './bootstrap';
//import './chat';
// resources/js/app.js 或者其他主 JavaScript 文件

const components = {
    loadingDots: `<span class="loading">
    <span style="background-color: #fff;"></span>
    <span style="background-color: #fff;"></span>
    <span style="background-color: #fff;"></span>
    </span>
    `,
    thinking:
        `<span class="animate-pulse text-gray-600 text-sm">回覆中...</span>`,
    chat_user: `
    <div class="ml-16 flex justify-end">
        <di class="bg-gray-100 p-3 rounded-md">
            <p class="font-medium text-blue-500 text-right text-sm">${username}</p>
            <hr class="my-2" />
            <p class="text-gray-800">{content}</p>
        </di>
    </div> `,
    chat_bot: `
    <div class="bg-gray-100 p-2 rounded-md mr-16">
        <p class="font-medium text-blue-500 text-sm">客服機器人</p>
        <hr class="my-2" />
        <div class="text-gray-800" id="{id}">{content}</div>
    </div>`,
};



function getId(length = 6) {
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        result += characters.charAt(randomIndex);
    }

    return result;
}

async function markdownToHtml(markdownString) {//將 markdown 轉換為 HTML
    const { unified } = await import("unified");
    const markdown = (await import("remark-parse")).default;
    const remark2rehype = (await import("remark-rehype")).default;
    const rehypeStringify = (await import("rehype-stringify")).default;

    const result = await unified()
        .use(markdown)
        .use(remark2rehype)
        .use(rehypeStringify)
        .process(markdownString);

    return result.value.toString();
}

function handleSubmitQuestion(form) {
    form.addEventListener("submit", (e) => {
        e.preventDefault();//阻止表單默認提交
        const question = e.target.question.value;//提取輸入框的值
        const inputField = document.getElementById('input-question');//取得輸入框
        const token = e.target._token.value;//提取 CSRF token
        const btn = document.getElementById("btn-submit-question");//取得送出按鈕
        //const messages = document.getElementById("messages");
        const messages = document.querySelector('#messages'); // 取得畫面中的訊息區塊
        btn.innerHTML = components.loadingDots;//顯示發送中

        e.target.question.value = "";//清空輸入框






        messages.innerHTML += components.chat_user.replace(//將用戶的問題加入到畫面中
            "{content}",
            question
        );

        const answerComponentId = getId();
        messages.innerHTML += components.chat_bot//將機器人的回答加入到畫面中
            .replace("{content}", "")
            .replace("{id}", answerComponentId);
            inputField.placeholder = "等待客服回覆中......";
            inputField.disabled = true;
            btn.disabled = true;

        const answerComponent = document.getElementById(answerComponentId);//獲取機器人回答的元素
        answerComponent.innerHTML = components.thinking;//顯示回覆中


        if (!question) return;
        const body = {question};
        fetch("chat", {
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": token,
            },
            method: "POST",
            body: JSON.stringify(body),
        })
            .then(async (res) => {
                answerComponent.innerHTML = "";
                const reader = res.body.getReader();//讀取回應的內容
                const decoder = new TextDecoder();

                let text = "";
                while (true) {
                    const { value, done } = await reader.read();//讀取回應的內容
                    if (done) break;//如果讀取完畢，則跳出循環
                    text += decoder.decode(value, { stream: true });//將回應的內容轉換為文字
                    answerComponent.innerHTML = await markdownToHtml(text);//將回應的內容轉換為 HTML
                    messages.scrollTop = messages.scrollHeight; // 捲動視窗到最底部
                }
                inputField.placeholder = "輸入傳送訊息!";
                inputField.disabled = false;
                btn.disabled = false;
                btn.innerHTML = `送出`;
            })
            .catch((e) => {
                console.error(e);
            });
    });
}

const formQuestion = document.getElementById("form-question");//獲取表單元素
if (formQuestion) handleSubmitQuestion(formQuestion);//如果表單存在，則綁定事件





window.onload = function() {
    messages.innerHTML = document.getElementById('message');//取得畫面中的訊息區塊

    // 加入歡迎訊息
    sendBotMessage('哈囉！請問您想要了解那些遊戲內容?');
};

// 模擬機器人發訊息的函數
function sendBotMessage(message) {
    const answerComponentId = getId();
    messages.innerHTML += components.chat_bot//將機器人的回答加入到畫面中
    .replace("{content}", message)
    .replace("{id}", answerComponentId);

}
