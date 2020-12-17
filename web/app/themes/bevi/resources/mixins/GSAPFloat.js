import { gsap, Sine } from 'gsap';

export default {
  mounted() {
    this.float();
  },
  methods: {
    float() {
      function random(min, max) {
        const delta = max - min;
        return (direction = 1) => (min + delta * Math.random()) * direction;
      }
      
      const randomX = random(2, 12);
      const randomY = random(2, 12);
      const randomTime = random(3, 5);
      const randomTime2 = random(5, 10);
      const randomAngle = random(-4, 4);
      const float = document.querySelectorAll('.gsap-float');

      function rotate(target, direction) {
        gsap.to(target, randomTime2(), {
          rotation: randomAngle(direction),
          ease: Sine.easeInOut,
          onComplete: rotate,
          onCompleteParams: [target, direction * -1],
        });
      }
      function moveX(target, direction) {
        gsap.to(target, randomTime(), {
          x: randomX(direction),
          ease: Sine.easeInOut,
          onComplete: moveX,
          onCompleteParams: [target, direction * -1],
        });
      }
      function moveY(target, direction) {
        gsap.to(target, randomTime(), {
          y: randomY(direction),
          ease: Sine.easeInOut,
          onComplete: moveY,
          onCompleteParams: [target, direction * -1],
        });
      }
      float.forEach((el) => {
        moveX(el, 1);
        moveY(el, -1);
        rotate(el, 1);
      });
    },
  },
};
