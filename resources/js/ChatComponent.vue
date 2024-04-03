<!-- resources/js/components/ChatComponent.vue -->

<template>
  <!-- 您的组件模板内容 -->
</template>

<script>
export default {
  data() {
    // 返回组件的数据对象
    return {
      messages: [],
      // 其他所需的数据属性
    };
  },
  mounted() {
    // 初始化和设置事件侦听器
    this.initializeEcho();
  },
  beforeDestroy() {
    // 清理和关闭事件侦听器
    if (window.Echo) {
      window.Echo.leave('questions');
    }
  },
  methods: {
    initializeEcho() {
      // 导入 Echo 实例
      import Echo from 'laravel-echo';

      // 设置和监听频道和事件
      window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'your-pusher-key', // 替换为实际的 Pusher key
        cluster: 'your-pusher-cluster', // 对应的 Cluster
        encrypted: true,
      });

      window.Echo.channel('questions')
        .listen('.QuestionAsked', (event) => {
          console.log(event.question);
          // 将接收到的消息添加到 messages 数组中
          this.messages.push(event.question);
        });
   },

   // 其他方法，例如发送消息等
  }
};
</script>

<style>
/* 您的 CSS 样式 */
</style>
