import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default {
  mounted() {
    this.startTextFade();
  },
  methods: {
    startTextFade() {
      gsap.to('.parallax', {
        scrollTrigger: {
          scrub: true,
        },
        y: (i, target) => -ScrollTrigger.maxScroll(window) * target.dataset.speed,
        ease: 'none',
      });
    },
  },
};
