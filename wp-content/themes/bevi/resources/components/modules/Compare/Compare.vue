<template>

  <section class="container mx-auto bg-gray-100 comparison">
    <div class="relative flex min-h-screen flex-col overflow-hidden">

      <div class="block-content py-16 pb-4 px-0 lg:px-48 text-center">
        <h1 class="gsap-fades my-4 heading-two text-blue">{{ block.title }}</h1>
        <h2 class="gsap-fades my-4 font-space font-medium md:text-lg">{{ block.subtitle }}</h2>
      </div>

      <div class="bubbles">
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 -left-36 -top-40 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-8 -left-30 top-64"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-20 top-20 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 top-40 -right-12"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-8 top-98"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -left-30 bottom-36 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-16 -left-16 bottom-24 opacity-50"
            aria-hidden="true"
        >
      </div>

      <div class="block-content w-full py-16 pb-4 px-0 lg:px-48 flex">

        <div class="grow w-full md:w-1/2 bg-grey-light m-3">
          <div class="w-full text-center">
              <img class="max-h-80 inline" src="http://bevi.local/wp-content/uploads/2022/03/2.0_Drawing-01.png" alt="Dispenser" />
              <h3 class="gsap-fades my-4 text-2.5xl font-semibold mt-6 text-blue">The Standup 2.0</h3>
              <p class="gsap-fades mt-4 mb-12 px-4">Mix flavors and add enhancements in just 2.5 sq feet.</p>
          </div>
          <div class="w-full bg-gray-200 px-8 py-6 rounded-2xl">

            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700 text-body">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-false.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700 text-body">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-false.png" alt=""></div>
            </div>

            <div class="flex my-2 py-2 border-t border-gray mt-4">
              <div class="flex-auto w-1/2 text-gray-700 text-body">Max flavors</div>
              <div class="flex-auto w-1/2 text-right">
                <div class="w-full">
                  up to 5
                </div>
                <div class="w-full text-sm">
                  max 3 flavors
                </div>
              </div>
            </div>
            <div class="flex my-2 py-2 border-t border-gray mt-4">
              <div class="flex-auto w-1/2 text-gray-700 text-body">Max flavors</div>
              <div class="flex-auto w-1/2 text-right">
                <div class="w-full">
                  up to 5
                </div>
                <div class="w-full text-sm">
                  max 3 flavors
                </div>
              </div>
            </div>

            <a href="" class="btn w-full mb-2">Get a quote</a>

          </div>
        </div>



        <div class="grow w-full md:w-1/2 bg-grey-light m-3">
          <div class="w-full text-center">
            <img class="max-h-80 inline" src="http://bevi.local/wp-content/uploads/2022/03/2.0_Drawing-01.png" alt="Dispenser" />
            <h3 class="gsap-fades my-4 text-2.5xl font-semibold mt-6 text-blue">The Standup 2.0</h3>
            <p class="gsap-fades mt-4 mb-12 px-4">Mix flavors and add enhancements in just 2.5 sq feet.</p>
          </div>
          <div class="w-full bg-gray-200 px-8 py-6 rounded-2xl">

            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-true.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700 text-body">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-false.png" alt=""></div>
            </div>
            <div class="flex my-2">
              <div class="flex-auto w-5/6 text-gray-700 text-body">Filtered still + sparkling</div>
              <div class="flex-auto text-right"><img class="inline" src="/wp-content/themes/bevi/dist/images/chk-false.png" alt=""></div>
            </div>

            <div class="flex my-2 py-2 border-t border-gray mt-4">
              <div class="flex-auto w-1/2 text-gray-700 text-body">Max flavors</div>
              <div class="flex-auto w-1/2 text-right">
                <div class="w-full">
                  up to 5
                </div>
                <div class="w-full text-sm">
                  max 3 flavors
                </div>
              </div>
            </div>
            <div class="flex my-2 py-2 border-t border-gray mt-4">
              <div class="flex-auto w-1/2 text-gray-700 text-body">Max flavors</div>
              <div class="flex-auto w-1/2 text-right">
                <div class="w-full">
                  up to 5
                </div>
                <div class="w-full text-sm">
                  max 3 flavors
                </div>
              </div>
            </div>

            <a href="" class="btn w-full mb-2">Get a quote</a>

          </div>
        </div>

      </div>

    </div>
  </section>

</template>

<style>
.comparison {
  background-image: url("/wp-content/themes/bevi/dist/images/bluewave.png");
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: contain;
}
 .max-h-80 {
   max-height: 314px;
 }
</style>

<script>
export default {
  props: {
    block: {
      required: true,
      type: Object,
    },
  },
  mounted() {
    jQuery('.smtg').each(function() {
      let date = jQuery(this).html();
    });
  },
};
</script>