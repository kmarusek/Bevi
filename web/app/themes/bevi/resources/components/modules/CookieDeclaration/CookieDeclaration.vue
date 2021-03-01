<template>
  <div class="py-20 lg:py-32 container xl:max-w-6xl relative">
    <div id="declaration"></div>
  </div>
</template>

<script>
  import postscribe from 'postscribe';

  export default {
    mounted() {
      // eslint-disable-next-line no-useless-escape
      postscribe('#declaration', '<script id="CookieDeclaration" src="https://consent.cookiebot.com/87b4ec8d-7070-4d14-a5f7-e12be46378df/cd.js" async><\/script>');
    },
  };
</script>
