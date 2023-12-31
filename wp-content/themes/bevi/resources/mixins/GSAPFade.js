import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

let hasTriggered = false;

export default {
  mounted() {
    if (!hasTriggered) {
      hasTriggered = true;
      this.startTextFade();
    }
  },
  methods: {
    startTextFade() {
      gsap.utils.toArray('.gsap-fade-section').forEach((section) => {
        const elems = section.querySelectorAll('.gsap-fade');
        
        gsap.set(elems, { opacity: 0 });
        
        ScrollTrigger.create({
          trigger: section,
          start: 'top 90%',
          onEnter: () => gsap.to(elems, {
            opacity: 1,
            duration: 1,
            stagger: 0.1,
            delay: 0.1,
            ease: 'power3.out',
            overwrite: 'auto',
          }),
        });
      });
    },
  },
};
