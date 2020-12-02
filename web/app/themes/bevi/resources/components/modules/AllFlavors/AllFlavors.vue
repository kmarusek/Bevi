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
          <h2 class="h2">
            {{ block.title }}
          </h2>
        </div>
        <div class="mt-2 md:mt-6 block-content smaller md:px-8 gsap-fade">
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
    <div
      class="flavors-wrapper container"
    >
      <div class="overflow-hidden tray-wrapper order-2">
        <FlavorTray
          v-if="showTray"
          :flavor="flavors[selectedIndex]"
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
        this.showTray = false;
        this.selectedIndex = null;
      },
      openTray(item, index) {
        this.trayLoading = true;
        this.selectedIndex = index;
        const tray = document.querySelector('#tray');

        const flavorTray = document.querySelector('.tray-wrapper');

        flavorTray.classList.forEach((trayClass) => {
          if (trayClass.startsWith('order')) {
            flavorTray.classList.remove(trayClass);
          }

          setTimeout(() => {
            this.trayLoading = false;
            this.showTray = true;
            tray.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }, 200);
        });

        flavorTray.classList.add(`order-${ this.getTrayOrder }`);
      },
      filterList(item) {
        this.selectedTag = item;
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
        }, 500);

        if (item === 'all flavors') {
          this.filteredList = this.flavors;
        } else {
          this.filteredList = this.flavors.filter((flavor) => flavor.flavor_tags.some((tag) => tag.name.toLowerCase() === item.toLowerCase()));
        }

        this.setFlavorsIndex();
      },
    },
    beforeDestroy() {
      window.removeEventListener('resize');
    },
  };
</script>

<style lang="scss" scoped>
.all-flavors {
  @apply relative overflow-x-hidden py-20 bg-gray-100;

  @screen md {
    @apply py-48;
  }

  .tray-wrapper {
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
</style>
