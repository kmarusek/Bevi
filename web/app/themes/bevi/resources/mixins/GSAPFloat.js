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
      
      const randomX = random(0, 10);
      const randomY = random(0, 10);
      const randomTime = random(3, 4);
      const randomAngle = random(-5, 5);

      const float = document.querySelectorAll('.gsap-float');
      const floatTl = gsap.timeline({ repeat: -1, yoyo: true });
      floatTl.set(float, {
        x: 0,
        y: 0,
        rotation: 0,
      });
      floatTl.to(float, {
        rotation: randomAngle,
        x: randomX,
        y: randomY,
        duration: randomTime,
        ease: Sine.easeInOut,
      }, 0);
    },
  },
};
