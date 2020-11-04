<template>
  <div class="absolute bottom-0 w-full">
    <svg>
      <clipPath
        id="wave"
        clipPathUnits="objectBoundingBox"
      >
        <path
          v-if="wave === '1'"
          d="M3.571,1h0A.96.96,0,0,0,3.307.978a2.179,2.179,0,0,0-.234.009A2.179,2.179,0,0,1,2.839,1,.951.951,0,0,1,2.571.97V0h1Z"
          transform="translate(-2.571)"
        />
        <!-- TODO: Update paths -->
        <path
          v-if="wave === '2'"
          d="M3.571,1h0A.96.96,0,0,0,3.307.978a2.179,2.179,0,0,0-.234.009A2.179,2.179,0,0,1,2.839,1,.951.951,0,0,1,2.571.97V0h1Z"
          transform="translate(-2.571)"
        />
        <path
          v-if="wave === '3'"
          d="M3.571,1h0A.96.96,0,0,0,3.307.978a2.179,2.179,0,0,0-.234.009A2.179,2.179,0,0,1,2.839,1,.951.951,0,0,1,2.571.97V0h1Z"
          transform="translate(-2.571)"
        />
        <!-- TODO: Update paths -->
      </clipPath>
    </svg>
  </div>
</template>

<script>
  export default {
    props: {
      wave: {
        required: true,
        type: String,
      },
    },
  };
</script>
