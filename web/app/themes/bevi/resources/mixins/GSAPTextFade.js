import { gsap } from 'gsap';

export default {
  mounted() {
    this.startTextFade();
  },
  methods: {
    startTextFade() {
      gsap.config({ nullTargetWarn: false });
      gsap.utils.toArray('.gsap-text-fade').forEach((section) => {
        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: section,
            start: 'top 90%',
            end: 'top 70%',
            onRefresh: (self) => self.progress === 1 && self.animation.progress(1),
          },
        });
        gsap.set(section, {
          autoAlpha: 0,
          y: 100,
        });
        tl.to(section, {
          autoAlpha: 1,
          y: 0,
          ease: 'power3.out',
          duration: 1,
        });
      });
    },
  },
};
