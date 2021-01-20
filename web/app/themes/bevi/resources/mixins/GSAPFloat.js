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

        float.from(target, {
          rotate: 0,
          x: 0,
          y: 0,
        });

        float.to(target, {
          rotation: 'random(-5, 5)',
          x: 'random(-15, 15)',
          y: 'random(-15, 15)',
          duration: 'random(5, 10)',
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
    // float(elems) {
    //   function random(min, max) {
    //     const delta = max - min;
    //     return (direction = 1) => (min + delta * Math.random()) * direction;
    //   }

    //   const randomX = random(2, 12);
    //   const randomY = random(2, 12);
    //   const randomTime = random(3, 5);
    //   const randomTime2 = random(5, 10);
    //   const randomAngle = random(-4, 4);
    //   const float = elems;

    //   function rotate(target, direction) {
    //     gsap.to(target, randomTime2(), {
    //       rotation: randomAngle(direction),
    //       ease: Sine.easeInOut,
    //       onComplete: rotate,
    //       onCompleteParams: [target, direction * -1],
    //     });
    //   }
    //   function moveX(target, direction) {
    //     gsap.to(target, randomTime(), {
    //       x: randomX(direction),
    //       ease: Sine.easeInOut,
    //       onComplete: moveX,
    //       onCompleteParams: [target, direction * -1],
    //     });
    //   }
    //   function moveY(target, direction) {
    //     gsap.to(target, randomTime(), {
    //       y: randomY(direction),
    //       ease: Sine.easeInOut,
    //       onComplete: moveY,
    //       onCompleteParams: [target, direction * -1],
    //     });
    //   }
    //   float.forEach((el) => {
    //     moveX(el, 1);
    //     moveY(el, -1);
    //     rotate(el, 1);
    //   });
    // },
  },
};
