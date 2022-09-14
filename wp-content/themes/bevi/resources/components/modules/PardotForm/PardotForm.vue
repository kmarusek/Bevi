<template>
  <div
    class="pardot-form pardot-form-module"
    v-html="block.pardot_form ? block.pardot_form : block.pardot_form_fallback"
    :style="[ isMobile ? { 'height' : `${block.form_height.mobile}px` } : { 'height' : `${block.form_height.desktop}px` } ]"
  />
</template>

<script>
  export default {
    props: {
      block: {
        type: Object,
        required: true,
      },
    },
    computed: {
      isMobile() {
        return window.innerWidth <= 768;
      },
    },
  };
</script>