<template>

    <section class="w-full informationBanner-wrap"
    v-if="banner.show === true"
    >
        <p>
        {{banner.text}}
        <a v-if="banner.cta.url"
        :href="banner.cta.url"
        :target="banner.cta.taget ?? '_self'"
        >
        {{ banner.cta.title }}
        </a>
        </p>

    </section>

</template>

<script>
    export default {
        props: {
            banner: {
                required: true,
                type: Object,
            },
        },
        mounted () {
            //all scripts
        },
    };
</script>

<style lang="scss" scoped>
    .informationBanner-wrap {
        background-color: #246eff;
        padding: 8px 0px;
        color: #ffffff;
        margin-top: 16px;
        display:flex;
        justify-content: center;
        align-items: center;
        font-family: Space Grotesk,sans-serif;
    }
    .informationBanner-wrap  a {
    @apply relative font-space font-semibold transition-all duration-300 inline items-center;
        font-size: 14px;
    &:after {
      @apply absolute w-full left-0 bg-white transition-all duration-300;
      content: "";
      height: 2px;
      margin-top: 2px;
      top: 100%;
      transform: scaleX(0) translateY(-2px);
      transform-origin: left;
      z-index: 0;
    }
    &:hover,
    &.active {
      &:after {
        transform: scaleX(1) translateY(-2px);
      }
    }
  }
    
</style>