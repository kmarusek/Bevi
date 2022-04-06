<template>
  <div>
    <div
      v-for="detail in details"
      :key="detail.id"
    >
      <h6 class="font-semibold text-lg text-blue-600 leading-tight">
        {{ detail.detail_title }}
      </h6>
      <div
        class="mb-10 mt-2 px-4 md:px-12 text-body"
        v-html="detail.detail_text"
      />
    </div>
    <a
      v-if="counters.counter_link"
      :href="counters.counter_link.url"
      :target="counters.counter_link.target"
      class="btn mx-4"
    >
      {{ counters.counter_link.title }}
    </a>
  </div>
</template>

<script>
  export default {
    props: {
      details: {
        required: true,
        type: Array,
      },
      counters: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style>

</style>
