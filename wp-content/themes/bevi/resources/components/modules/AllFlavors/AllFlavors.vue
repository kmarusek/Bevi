<template>
  <section class="all-flavors gsap-fade-section">
    <span
      v-if="showTray"
      class="overlay"
      @click="closeTray"
    />
    <div class="container text-center">
      <div class="relative mb-4 md:mb-10">
        <div class="title-wrapper gsap-fade">
          <h2
            v-if="block.title"
            class="h3 font-semibold"
          >
            {{ block.title }}
          </h2>
        </div>
        <div
          v-if="block.intro_text"
          class="mt-2 md:mt-6 block-content md:px-8 gsap-fade"
        >
          <p>
            {{ block.intro_text }}
          </p>
        </div>
      </div>
    </div>
    <FlavorsFilter
      @filter-list="filterList"
      :tags="tags"
      class="md:container"
    />
    <transition name="fade">
      <div
        class="flavors-wrapper container"
        v-if="!loading"
      >
        <div class="tray-container order-2">
          <FlavorTray
            v-if="showTray"
            :flavor="filteredList[selectedIndex]"
            :tray-loading="trayLoading"
            @close="closeTray"
          />
        </div>
        <FlavorCard
          v-for="(flavor, index) in filteredList"
          :key="index"
          :flavor="flavor"
          :show-tray="showTray"
          :selected-index="selectedIndex"
          :index="index"
          @click.native="openTray(flavor.post_name, index)"
        />
      </div>
    </transition>
    <div
      v-if="block.wave"
      class="wave-wrapper"
    >
      <wave
        :wave="block.wave"
        wave-id="all-flavors-wave"
      />
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {
      block: {
        required: true,
        type: Object,
      },
      flavors: {
        required: true,
        type: Array,
      },
      tags: {
        required: true,
        type: Array,
      },
    },
    data() {
      return {
        selectedTag: '',
        filteredList: [],
        showTray: false,
        selectedIndex: null,
        loading: false,
        trayLoading: false,
        windowWidth: window.innerWidth,
      };
    },
    beforeMount() {
      this.filteredList = this.flavors;
    },
    mounted() {
      this.setFlavorsIndex();

      window.addEventListener('resize', () => {
        this.windowWidth = window.innerWidth;
        this.setFlavorsIndex();
      });
    },
    computed: {
      getTrayOrder() {
        return Math.ceil((((this.selectedIndex + 1) / this.rowItemIndex) + 1));
      },
      rowItemIndex() {
        if (this.windowWidth <= 768) {
          return 2;
        }
        if (this.windowWidth > 768 && this.windowWidth <= 1280) {
          return 3;
        }
        return 5;
      },
    },
    methods: {
      setFlavorsIndex() {
        const flavorItems = document.querySelectorAll('.flavor');
        const idx = this.rowItemIndex;

        for (let i = 0; i < flavorItems.length; i += 1) {
          flavorItems[i].classList.forEach((flavorClass) => {
            if (flavorClass.startsWith('order')) {
              flavorItems[i].classList.remove(flavorClass);
            }
          });

          if (i <= (idx - 1)) {
            flavorItems[i].classList.add('order-1');
          } else if (i > (idx - 1) && i < (idx * 2)) {
            flavorItems[i].classList.add('order-2');
          } else if (i > ((idx * 2) - 1) && i < (idx * 3)) {
            flavorItems[i].classList.add('order-3');
          } else if (i > ((idx * 3) - 1) && i < (idx * 4)) {
            flavorItems[i].classList.add('order-4');
          } else if (i > ((idx * 4) - 1) && i < (idx * 5)) {
            flavorItems[i].classList.add('order-5');
          } else if (i > ((idx * 5) - 1) && i < (idx * 6)) {
            flavorItems[i].classList.add('order-6');
          } else if (i > ((idx * 6) - 1) && i < (idx * 7)) {
            flavorItems[i].classList.add('order-7');
          } else if (i > ((idx * 7) - 1) && i < (idx * 8)) {
            flavorItems[i].classList.add('order-7');
          } else if (i > ((idx * 8) - 1) && i < (idx * 9)) {
            flavorItems[i].classList.add('order-7');
          } else if (i > ((idx * 9) - 1) && i < (idx * 10)) {
            flavorItems[i].classList.add('order-7');
          }
        }
      },
      closeTray() {
        const selected = document.querySelector('.selected-flavor');
        this.showTray = false;
        this.selectedIndex = null;

        if (this.windowWidth > 768) {
          selected.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      },
      openTray(item, index) {
        this.trayLoading = true;
        this.selectedIndex = index;

        const flavorTray = document.querySelector('.tray-container');

        flavorTray.classList.forEach((trayClass) => {
          if (trayClass.startsWith('order')) {
            flavorTray.classList.remove(trayClass);
          }

          setTimeout(() => {
            const tray = document.querySelector('#tray');
            this.trayLoading = false;
            this.showTray = true;
            if (tray && this.windowWidth > 768) {
              tray.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            const closeButton = document.querySelector('.close');

            closeButton.focus();
          }, 200);
        });

        flavorTray.classList.add(`order-${ this.getTrayOrder }`);
      },
      filterList(item) {
        this.showTray = false;
        this.selectedIndex = null;
        this.selectedTag = item;
        this.loading = true;

        setTimeout(() => {
          this.loading = false;
        }, 200);

        setTimeout(() => {
          this.setFlavorsIndex();
        }, 500);

        if (item === 'all flavors') {
          this.filteredList = this.flavors;
        } else {
          this.filteredList = this.flavors.filter((flavor) => flavor.flavor_tags && flavor.flavor_tags.some((tag) => tag.name.toLowerCase() === item.toLowerCase()));
        }
      },
    },
    beforeDestroy() {
      window.removeEventListener('resize');
    },
  };
</script>

<style lang="scss" scoped>
.all-flavors {
  @apply relative py-20 bg-gray-100;

  @screen md {
    @apply py-48;
  }

  .tray-container {
    transition: all ease 0.5s;
    @apply w-full;
  }

  .overlay {
    z-index: -1;
    @apply absolute inset-0;
  }

  .flavors-wrapper {
    transition: opacity 0.5s ease-in-out;
    @apply flex flex-wrap justify-center;
  }
}

.fade-enter-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-gray-100 absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#all-flavors-wave);

  @screen md {
    max-height: 500px;
  }
}

.order-8 {
  order: 8;
}
</style>
