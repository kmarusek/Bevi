<template>
  <input
    :type="type"
    :value="value"
    @input="$emit('input', $event.target.value)"
    class="appearance-none text-sm w-full py-4 px-2 outline-none border-b-2 focus:border-blue focus:bg-white bg-gray-100 duration-300 border-blue-200 transition-all font-light"
  />
</template>

<script>
  export default {
    props: {
      type: {
        type: String,
        default: 'text',
      },
      value: {
        type: String,
        default: '',
      },
    },
  };
</script>
