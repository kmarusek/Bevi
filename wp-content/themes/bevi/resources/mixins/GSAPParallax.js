import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

let hasTriggered = false;

export default {
  mounted() {
    if (!hasTriggered) {
      hasTriggered = true;
      this.startParallaxFade();
    }
  },
  methods: {
    startParallaxFade() {
      gsap.registerPlugin(ScrollTrigger);
      
      gsap.utils.toArray('.parallax').forEach((section) => {
        ScrollTrigger.matchMedia({
          '(min-width: 1024px)': () => {
            gsap.to(section, {
              y: -section.dataset.speed * 100,
              ease: 'power3.out',
              
              scrollTrigger: {
                trigger: section,
                scrub: true,
              },
            });
          },
        });
      });
    },
  },
};
