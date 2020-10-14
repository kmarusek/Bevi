<template>
  <footer class="bg-gray-500">
    <div class="container flex pt-10 mb-10">
      <div class="footer-grid w-full md:w-3/4">
        <div class="order-last md:order-first">
          <img
            :src="require('~/assets/images/bevi-logo.svg')"
            alt="Bevi Logo"
            class="w-24"
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
      <div class="w-full md:w-1/4 pl-12 border-l border-gray-400 flex content-between flex-wrap">
        <h3 class="font-medium text-3xl text-blue-600 leading-none">
          Still or sparkling. Whats your Bevi?
        </h3>
        <div>
          <h4 class="h4">
            Join us online
          </h4>
        </div>
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
