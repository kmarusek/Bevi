<template>
  <section class="container mx-auto bg-white">
    <div class="relative flex flex-col overflow-hidden">
      <div class="grid grid-cols-5 gap-4 mt-24 h-header">
        <div class="col-span-5 md:col-span-2 relative h-full flex items-align-center">
          <h1 v-bind:style="{ 'background-image': 'url(/wp-content/themes/bevi/dist/images/eventblob.png)' }" class="heading-two text-center block w-72 h-64 bg-cover m-auto align-middle eventblobtext">{{ block.main_title }}</h1>
        </div>
        <div v-bind:style="{ 'background-image': 'url(' + block.top_image + ')' }" class="col-start-1 md:col-start-3 col-span-3 hidden md:block h-full bg-cover halfborder">
        </div>
      </div>
    </div>
  </section>
  <section class="container mx-auto bg-gray-100">
    <div class="relative flex min-h-screen flex-col overflow-hidden">

      <div class="block-content py-16 pb-4 px-0 lg:px-48" data-v-c5a16a1c="">
        <!--h1 class="gsap-fades my-4 heading-two">{{ block.events_title }}</h1-->
        <div class="grid grid-cols-5 gap-4">
          <div class="col-start-1 col-end-6 md:col-end-3">
            <p>{{ block.events_description }}</p>
          </div>
          <div class="col-start-1 col-end-6 md:col-start-4">
            <a
                v-if="block.events_contact_button"
                v-bind:href="block.events_contact_button.url"
                class="px-3 md:px-10 text-blue bg-white border border-2 border-blue hover:text-white hover:bg-blue w-full rounded-full py-3 inline-block text-center transform transition duration-500">
              {{ block.events_contact_button.title }}
            </a>
          </div>
        </div>
        <hr class="mt-6" />
      </div>

      <div class="eventbuttons py-4 px-0 lg:px-48 flex flex-columns items-center">
        <button class="future btn px-3 md:px-10 mr-4 md:mr-16 w-1/2 md:w-64">Future events</button>
        <button class="past btn px-3 md:px-10 bg-gray-300 w-1/2 md:w-64">Past events</button>
      </div>

      <div
          v-for="(item, idx) in block.r_event"
          :key="idx"
          :id="`item-${idx}`"
          class="eventcard mx-0 lg:mx-48 my-8 ring-1 ring-gray-900/5 rounded-3xl sm:px-10 bg-white border border-white hover:border-blue-200">

        <div class="grid grid-cols-5 gap-4">
          <div v-bind:style="{ 'background-image': 'url(' + item.event_image + ')' }" class="col-span-5 md:col-span-2 xl:col-span-1 my-8 mx-auto text-base leading-7 text-gray-600 rounded-full w-48 xl:w-36 xxl:w-48 h-48 xl:h-36 xxl:h-48 bg-cover relative clip-polygon">
            <!-- ImageBlob :image="item.event_image" /-->

          </div>
          <div class="col-start-1 md:col-start-3 xl:col-start-2 col-end-6 space-y-3 py-8 px-8 md:px-0 text-base leading-7 text-gray-600">
            <p class="text-2xl"><span class="eventdate">{{ item.event_date }}</span><span v-if="item.event_date_end"> - {{ item.event_date_end }}</span></p>
            <h3 class="text-3xl md:text-4xl mb-4 text-blue-600 font-semibold leading-tight eventcard-hover:text-blue">{{ item.event_title }}</h3>
            <p class="text-xl">{{ item.event_time }}</p>
            <p v-if="item.event_location">{{ item.event_location }}</p>
            <p class="text-body">{{ item.event_description }}</p>
            <p class="py-5"><a
                v-if="item.event_gotopage"
                v-bind:href="item.event_gotopage.url"
                class="px-5 md:px-10 mt-5 text-black bg-white border border-2 border-black hover:text-white hover:bg-blue hover:border-blue w-full rounded-full py-4 text-center transform transition duration-500" target="_blank">
              {{ item.event_gotopage.title }}
            </a></p>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    mounted() {
      jQuery('.eventdate').each(function() {
        let date = jQuery(this).html();
        let dateclass = 'future';
        if(new Date() > new Date(date))
        {
          dateclass = 'past';
        }
        jQuery(this).closest('.eventcard').addClass(dateclass);
      });
      jQuery(   ".eventcard.past" ).hide();
      jQuery(   ".eventbuttons .btn.future" ).click(function() {
        jQuery( ".eventbuttons .btn.past" ).addClass('bg-gray-300');
        jQuery( ".eventbuttons .btn.future" ).removeClass('bg-gray-300');
        jQuery( ".eventcard.future" ).show();
        jQuery( ".eventcard.past" ).hide();
      });
      jQuery(   ".eventbuttons .btn.past" ).click(function() {
        jQuery( ".eventbuttons .btn.future" ).addClass('bg-gray-300');
        jQuery( ".eventbuttons .btn.past" ).removeClass('bg-gray-300');
        jQuery( ".eventcard.past" ).show();
        jQuery( ".eventcard.future" ).hide();
      });
    },
  };
</script>

<style>
.halfborder {
  clip-path: circle(600px at 75% 60%);
}

.eventblobtext {
  padding-top: 105px;
}

.h-header {
  height: 500px;
}
.eventcard:hover .eventcard-hover\:text-blue {
  color: #246eff;
}
.clippath-blob {
  -webkit-clip-path: url(#shape);
  clip-path: url(#shape);
}
.clip-polygon {
  clip-path: circle(50% at center);
}
@media screen and (max-width: 1440px) {
  .halfborder {
    clip-path: circle(600px at 85% 60%);
  }
}
@media screen and (max-width: 1439px) {
  .halfborder {
    clip-path: circle(600px at 105% 60%);
  }
}
@media screen and (max-width: 1023px) {
  .halfborder {
    clip-path: circle(600px at 140% 60%);
  }
}
</style>
