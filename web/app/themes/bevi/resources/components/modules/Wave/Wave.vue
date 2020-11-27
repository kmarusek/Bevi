<template>
  <div class="absolute bottom-0 w-full">
    <svg>
      <clipPath
        :id="waveId"
        clipPathUnits="objectBoundingBox"
      >
        <path
          v-if="wave === '1'"
          d="M3.571,1h0A.96.96,0,0,0,3.307.978a2.179,2.179,0,0,0-.234.009A2.179,2.179,0,0,1,2.839,1,.951.951,0,0,1,2.571.97V0h1Z"
          transform="translate(-2.571)"
        />
        <path
          v-if="wave === '2'"
          d="M0-945.516H1v.988s-.185.012-.5.013-.5-.013-.5-.013Z"
          transform="translate(0 945.516)"
        />
        <path
          v-if="wave === '3'"
          d="M0-1061.794H1v.983a3.1,3.1,0,0,1-.5.013c-.3-.013-.5-.013-.5-.013Z"
          transform="translate(0 1061.794)"
        />
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
      waveId: {
        required: true,
        type: String,
      },
    },
  };
</script>
