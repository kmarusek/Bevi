<template>
  <footer class="bg-gray-500 py-10">
    <div class="container flex">
      <div class="footer-grid w-full md:w-3/4">
        <div class="">
          <img
            :src="require('~/assets/images/bevi-logo.svg')"
            alt="Bevi Logo"
            class="w-auto"
          >
        </div>
        <SiteFooterNavigation
          :navigation="insideBevi"
          title="Inside Bevi"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="office"
          title="Office"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="support"
          title="Support"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="blog"
          title="Blog"
          class="w-auto"
        />
      </div>
      <div class="w-full md:w-1/4 text-center border-l">
        <h3>Still or sparkling. Whats your Bevi?</h3>
      </div>
    </div>
  </footer>
</template>
<script>
  export default {
    props: {
      insideBevi: {
        required: true,
        type: Array,
      },
      office: {
        required: true,
        type: Array,
      },
      support: {
        required: true,
        type: Array,
      },
      blog: {
        required: true,
        type: Array,
      },
      terms: {
        required: true,
        type: Array,
      },
    },
  };
</script>

<style lang="scss" scoped>
.footer-grid {
  display: grid;
  align-items: start;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
}
</style>
