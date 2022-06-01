<template>
  <section class="container mx-auto">
    <div class="relative flex min-h-screen flex-col overflow-hidden">
      <div class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:rounded-lg sm:px-10">

        <div
            v-for="(item, idx) in block.r_event"
            class="grid grid-cols-5 gap-4"
            :key="idx"
            :id="`item-${idx}`">

          <div class="col-span-1 py-8 text-base leading-7 text-gray-600">
            <img v-bind:src="item.event_image" class="w-48" />
          </div>
          <div class="col-span-4 space-y-3 py-8 text-base leading-7 text-gray-600">
            <p class="font-bold">{{ item.event_date }}</p>
            <p class="font-bold">{{ item.event_title }}</p>
            <p>{{ item.event_description }}</p>
            <p><a v-bind:src="item.event_location">{{ item.event_location }}</a></p>
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
