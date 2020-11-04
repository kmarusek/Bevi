<template>
  <section
    class="relative overflow-hidden flex items-center main-banner"
    :class="{ wave : wp.hero.wave }"
  >
    <div class="container xl:max-w-6xl z-10 relative text-center lg:text-left">
      <h1 class="h1">
        {{ wp.pageTitle }}
      </h1>
    </div>
    <single-bubble
      class="absolute z-20 top-0"
      stroke-color="blue"
    />
    <single-bubble
      class="absolute z-20 right-0 bottom-0"
      stroke-color="light-blue"
    />
    <single-bubble
      class="absolute z-20 w-8"
      stroke-color="gray"
    />
    <single-bubble class="absolute z-20" />
    <single-bubble class="absolute z-20" />
    <single-bubble class="absolute z-20" />
    <img
      :src="require('~/assets/images/backgrounds/page-header.jpg')"
      class="object-cover w-full h-full absolute top-0 left-0 z-0"
    >
    <wave
      v-if="wp.hero.wave"
      :wave="wp.hero.wave"
    />
  </section>
</template>

<script>
  export default {
    props: {
      wp: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.main-banner {
  min-height: 50vh;
  @apply w-full;

  &.wave {
    clip-path: url(#wave);
    @apply -mb-6;
  }
}
</style>
