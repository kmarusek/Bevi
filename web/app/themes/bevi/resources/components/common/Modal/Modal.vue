<template>
  <portal to="modal">
    <div @keydown.esc="close()">
      <transition
        enter-active-class="ease-out duration-300"
        leave-active-class="ease-in duration-200"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="initialShow"
          class="z-50 fixed bottom-0 inset-x-0 my-6 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center"
        >
          <div class="fixed inset-0 transition-opacity">
            <div
              @click="close()"
              class="absolute inset-0 bg-white opacity-50"
            />
          </div>
          <transition
            enter-active-class="ease-out duration-300"
            leave-active-class="ease-in duration-200"
            enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              v-show="initialShow"
              class="sm:max-w-lg max-h-full overflow-y-scroll overflow-hidden shadow-xl transform transition-all sm:w-full"
              role="dialog"
              aria-modal="true"
              aria-labelledby="modal-headline"
            >
              <div class="bg-white p-6 sm:p-10">
                <slot />
              </div>
            </div>
          </transition>
        </div>
      </transition>
    </div>
  </portal>
</template>

<script>
  export default {
    props: {
      initialShow: {
        type: Boolean,
        default: false,
      },
    },
    methods: {
      close() {
        this.$emit('modalToggle', !this.initialShow);
      },
    },
  };
</script>
