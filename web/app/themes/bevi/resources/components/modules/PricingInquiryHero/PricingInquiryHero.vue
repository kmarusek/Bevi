<template>
  <section
    :style="{ 'background-image': 'url(' + block.background_image.url + ')' }"
    :class="[
      'bg-cover bg-no-repeat bg-center flex',
      {
        wave: block.wave,
      }
    ]"
  >
    <div class="container flex flex-col lg:flex-row">
      <div class="grid grid-cols-8 items-end pt-20 xs:py-32 w-full md:gap-20">
        <div class="col-span-2 space-y-4 pb-20">
          <h3
            v-if="block.subtitle"
            class="font-space font-medium md:text-lg"
          >
            {{ block.subtitle }}
          </h3>
          <h2
            v-if="block.title"
            class="heading-two"
          >
            {{ block.title }}
          </h2>
          <div
            v-if="block.content"
            v-html="block.content"
          />
        </div>
        <div class="col-span-4">
          <img
            :src="block.image.sizes.large"
            :width="block.image.sizes['large-width']"
            :height="block.image.sizes['large-height']"
            :alt="block.image.alt"
          >
        </div>
        <div class="col-span-2 pb-20">
          <form
            @submit.prevent="onSubmit"
            class="space-y-4"
          >
            <div>
              <Label for="first_name">
                First name
              </Label>
              <Input
                v-model="form.first_name"
                id="first_name"
              />
            </div>
            <div>
              <Label for="last_name">
                Last name
              </Label>
              <Input
                v-model="form.last_name"
                id="last_name"
              />
            </div>
            <div>
              <Label for="email">
                Email
              </Label>
              <Input
                v-model="form.email"
                id="email"
                type="email"
              />
            </div>
            <div>
              <Label for="phone">
                Phone number
              </Label>
              <Input
                v-model="form.phone"
                id="phone"
                type="phone"
              />
            </div>
            <div class="text-center">
              <button
                class="btn"
                type="button"
              >
                Get a quote
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="banner-wave"
    />
  </section>
</template>

<script>
  import axios from 'axios';
  import Label from '../../common/Label/Label.vue';
  import Input from '../../common/Input/Input.vue';

  export default {
    components: {
      Label,
      Input,
    },
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    data() {
      return {
        form: {
          first_name: '',
          last_name: '',
          email: '',
          phone_number: '',
          zip_code: '',
          country: '',
          location: '',
          daily_users: '',
        },
      };
    },
    methods: {
      onSubmit() {
        axios.post('http://go.pardot.com/l/891143/2020-09-23/y4', {
          '891143_573pi_891143_573': this.form.first_name,
          '891143_575pi_891143_575': this.form.last_name,
          '891143_577pi_891143_577': this.form.email,
          '891143_1693pi_891143_1693': this.form.phone_number,
          '891143_581pi_891143_581': this.form.company,
          '891143_585pi_891143_585': this.form.zip_code,
          '891143_925pi_891143_925': this.form.country,
          '891143_1695pi_891143_1695': this.form.location,
          '891143_583pi_891143_583': this.form.daily_users,
        });
      },
    },
  };
</script>

<style lang="scss" scoped>
.wave {
  clip-path: url(#banner-wave);
  @apply -mb-6 relative;
}
</style>
