import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default {
  mounted() {
    this.startTextFade();
  },
  methods: {
    startTextFade() {
      gsap.utils.toArray('.gsap-fade-section').forEach((section) => {
        const elems = section.querySelectorAll('.gsap-fade');
        
        gsap.set(elems, { y: 50, opacity: 0 });
        
        ScrollTrigger.create({
          trigger: section,
          start: 'top 85%',
          onEnter: () => gsap.to(elems, {
            y: 0,
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
