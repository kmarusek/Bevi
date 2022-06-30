<template>
  <section class="container mx-auto bg-gray-100">
    <div class="relative flex min-h-screen flex-col overflow-hidden">

      <div class="block-content py-16 pb-4 px-8 md:px-36" data-v-c5a16a1c="">
        <h1 class="gsap-fades my-4 heading-two">{{ block.events_title }}</h1>
        <p>{{ block.events_description }}</p>
        <hr class="mt-6" />
      </div>

      <div class="eventbuttons py-4 px-8 md:px-36">
        <button class="future btn mr-4 md:mr-16">Future events</button>
        <button class="past btn bg-gray-300">Past events</button>
      </div>

      <div
          v-for="(item, idx) in block.r_event"
          :key="idx"
          :id="`item-${idx}`"
          class="eventcard mx-8 md:mx-36 my-16 ring-1 ring-gray-900/5 sm:mx-auto sm:rounded-lg sm:px-10 bg-white border border-white hover:border-blue-200">

        <div class="grid grid-cols-5 gap-4">
          <div v-bind:style="{ 'background-image': 'url(' + item.event_image + ')' }" class="col-span-5 md:col-span-1 my-8 mx-auto md:mx-8 text-base leading-7 text-gray-600 rounded-full w-48 h-48 bg-cover">
          </div>
          <div class="col-span-4 space-y-3 py-8 px-8 md:px-0 text-base leading-7 text-gray-600">
            <p class="eventdate font-bold">{{ item.event_date }}</p>
            <h3 class="text-3xl md:text-4xl mb-4 text-blue-600 font-semibold leading-tight eventcard-hover:text-blue">{{ item.event_title }}</h3>
            <p>{{ item.event_time }}</p>
            <p><a v-bind:href="item.event_location">{{ item.event_location }}</a></p>
            <p class="text-body">{{ item.event_description }}</p>
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
      jQuery( ".eventcard.past" ).hide();
      jQuery( ".eventbuttons .btn.future" ).click(function() {
        jQuery( ".eventbuttons .btn.past" ).addClass('bg-gray-300');
        jQuery( ".eventbuttons .btn.future" ).removeClass('bg-gray-300');
        jQuery( ".eventcard.future" ).show();
        jQuery( ".eventcard.past" ).hide();
      });
      jQuery( ".eventbuttons .btn.past" ).click(function() {
        jQuery( ".eventbuttons .btn.future" ).addClass('bg-gray-300');
        jQuery( ".eventbuttons .btn.past" ).removeClass('bg-gray-300');
        jQuery( ".eventcard.past" ).show();
        jQuery( ".eventcard.future" ).hide();
      });
    },
  };
</script>

<style>
.eventcard:hover .eventcard-hover\:text-blue {
  color: #246eff;
}
</style>
