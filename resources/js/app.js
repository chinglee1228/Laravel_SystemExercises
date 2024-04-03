import './bootstrap';
// resources/js/app.js 或者其他主 JavaScript 文件

const components = {
    loadingDots: `<span class="loading">
    <span class="animate-pulse  style="background-color: #fff;">....</span>
    </span>`,
    thinking:
        '<span class="animate-pulse text-gray-600 text-sm">回覆中...</span>',
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

function isUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (error) {
        return false;
    }
}

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

async function markdownToHtml(markdownString) {
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
        const token = e.target._token.value;
        const btn = document.getElementById("btn-submit-question");
        const messages = document.getElementById("messages");
        btn.innerHTML = components.loadingDots;
        e.target.question.value = "";//清空輸入框

        messages.innerHTML += components.chat_user.replace(
            "{content}",
            question
        );

        const answerComponentId = getId();
        messages.innerHTML += components.chat_bot
            .replace("{content}", "")
            .replace("{id}", answerComponentId);

        const answerComponent = document.getElementById(answerComponentId);
        answerComponent.innerHTML = components.thinking;

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
                const reader = res.body.getReader();
                const decoder = new TextDecoder();

                let text = "";
                while (true) {
                    const { value, done } = await reader.read();
                    if (done) break;
                    text += decoder.decode(value, { stream: true });
                    answerComponent.innerHTML = await markdownToHtml(text);
                }

                btn.innerHTML = `送出`;
            })
            .catch((e) => {
                console.error(e);
            });
    });
}

const formSubmitLink = document.getElementById("form-submit-link");
if (formSubmitLink) handleSubmitIndexing(formSubmitLink);

const formQuestion = document.getElementById("form-question");
if (formQuestion) handleSubmitQuestion(formQuestion);
