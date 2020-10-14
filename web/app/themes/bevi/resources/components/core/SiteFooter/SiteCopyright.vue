<template>
  <div class="bg-purple-200 py-4 font-space text-xs">
    <div class="container flex">
      <p class="mr-auto">
        Bevi &#169; {{ new Date().getFullYear() }} All rights reserved.
      </p>
      <ul class="font-space mx-auto">
        <li
          v-for="(item, index) in terms "
          :key="index"
          class="inline-block mx-6"
        >
          <a
            :href="item.url"
            class="link underline font-bold"
          >
            <span>
              {{ item.name }}
            </span>
          </a>
        </li>
      </ul>
      <div class="ml-auto">
        <p>Back to top</p>
      </div>
    </div>
  </div>
</template>
