<template>
  <footer class="bg-gray-500">
    <div class="container flex flex-col lg:flex-row pt-10 mb-10">
      <div class="footer-grid w-full lg:w-3/4">
        <div class="sm:order-last md:order-first logo-container py-6 sm:py-0">
          <img
            :src="require('~/assets/images/bevi-logo.svg').default"
            alt="Bevi Logo"
            class="w-24"
          >
        </div>
        <SiteFooterNavigation
          :navigation="insideBevi"
          :heading="headings ? headings.column_one : null"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="office"
          :heading="headings ? headings.column_two : null"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="support"
          :heading="headings ? headings.column_three : null"
          class="w-auto"
        />
        <SiteFooterNavigation
          :navigation="blog"
          :heading="headings ? headings.column_four : null"
          class="w-auto"
        />
      </div>
      <div class="w-full lg:w-1/4 lg:pl-12 pt-12 mt-12 lg:mt-0 lg:pt-0 border-t border-l-0 lg:border-t-0 lg:border-l border-gray-400 flex flex-col sm:flex-row content-between flex-wrap">
        <h4 class="font-medium text-3xl text-black leading-tight">
          Still or sparkling.<br> What's your Bevi?
        </h4>
        <div class="sm:ml-auto lg:ml-0">
          <h6 class="h6 mt-6 md:mt-0 mb-3 text-blue-600">
            Join us online
          </h6>
          <div class="flex">
            <a
              target="_blank"
              href="https://www.facebook.com/getBevi/"
              class="icon"
            >
              <img
                :src="require('~/assets/images/icons/facebook.svg').default"
                alt="Share on Facebook, opens in new tab"
              >
            </a>
            <a
              target="_blank"
              href="https://twitter.com/getBevi"
              class="icon mx-3"
            >
              <img
                :src="require('~/assets/images/icons/twitter.svg').default"
                alt="Share on Twitter, opens in new tab"
              >
            </a>
            <a
              target="_blank"
              href="https://www.instagram.com/getbevi/"
              class="icon"
            >
              <img
                :src="require('~/assets/images/icons/instagram.svg').default"
                alt="Share on Instagram, opens in new tab"
              >
            </a>
          </div>
        </div>
      </div>
    </div>
    <SiteFooterCopyright :navigation="terms" />
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
      headings: {
        required: false,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.footer-grid {
  display: grid;
  align-items: start;
  grid-gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
}
.logo-container {
  grid-column: 1/-1;
  @screen md {
    grid-column: 1;
  }
}
</style>
