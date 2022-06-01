<template>
  <section class="container py-8 lg:py-32 lg:px-12 mx-auto">
    <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
      <div class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:rounded-lg sm:px-10">

        <div
            v-for="(item, idx) in block.r_event"
            class="grid grid-cols-5 gap-4"
            :key="idx"
            :id="`item-${idx}`">

          <div class="col-span-1 py-8 text-base leading-7 text-gray-600">
            <img class="object-contain" src="{{ item.event_image }}" />
          </div>
          <div class="col-span-4 space-y-3 py-8 text-base leading-7 text-gray-600">
            <p class="font-bold">{{ item.event_date }}</p>
            <p class="font-bold">{{ item.event_title }}</p>
            <p>{{ item.event_description }}</p>
            <p><a href="{{ item.event_location }}">{{ item.event_location }}</a></p>
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
  };
</script>
