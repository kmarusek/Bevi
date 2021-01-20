import { gsap, Sine } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default {
  mounted() {
    this.triggerFloat();
  },
  methods: {
    triggerFloat() {
      gsap.utils.toArray('.gsap-float-section').forEach((section) => {
        const target = section.querySelectorAll('.gsap-float');

        const float = gsap.timeline({ repeat: -1, yoyo: true, repeatRefresh: true });
        const randomDuration = gsap.utils.random(5, 7, 10);

        float.from(target, {
          rotate: 0,
          x: 0,
          y: 0,
        });

        float.to(target, {
          rotation: 'random(-8, 8)',
          x: 'random(-20, 20)',
          y: 'random(-20, 20)',
          duration: randomDuration,
          stagger: 0.1,
          ease: Sine.easeInOut,
          transformOrigin: '50% 50%',
        });

        ScrollTrigger.create({
          trigger: section,
          animation: float,
          start: 'top bottom',
          end: 'bottom top',
          onEnter: () => { float.play(); },
          onEnterBack: () => { float.play(); },
          onLeave: () => { float.pause(); },
          onLeaveBack: () => { float.pause(); },
        });
      });
    },
  },
};
