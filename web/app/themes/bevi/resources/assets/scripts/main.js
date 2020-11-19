/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins
import gsap from 'gsap';
import axios from 'axios';
import VueAxios from 'vue-axios';
import ScrollTrigger from 'gsap/ScrollTrigger';
import VueAwesomeSwiper from 'vue-awesome-swiper';
import VueMoment from 'vue-moment';

// Layout
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';
import SiteNavigation from '~/components/core/SiteNavigation/SiteNavigation';
import SiteFooter from '~/components/core/SiteFooter/SiteFooter';
import SiteFooterNavigation from '~/components/core/SiteFooter/SiteFooterNavigation';
import SiteFooterCopyright from '~/components/core/SiteFooter/SiteCopyright';
import NewsListing from '~/components/core/News/NewsListing';
import NewsFilter from '~/components/core/News/NewsFilter';
import FeaturedNewsArticles from '~/components/core/FeaturedNewsArticles/FeaturedNewsArticles';
import NewsHero from '~/components/core/News/NewsHero';
import NewsContent from '~/components/core/News/NewsContent';

// Components
import TextBlock from '~/components/modules/TextBlock/TextBlock';
import FeatureBlock from '~/components/modules/FeatureBlock/FeatureBlock';
import ImageCarousel from '~/components/modules/ImageCarousel/ImageCarousel';
import ImageGalleryCarousel from '~/components/modules/ImageCarousel/ImageGalleryCarousel';
import CarouselDots from '~/components/modules/CarouselDots/CarouselDots';
import PressRelease from '~/components/modules/PressRelease/PressRelease';
import Wave from '~/components/modules/Wave/Wave';
import FormComponent from '~/components/modules/FormComponent/FormComponent';
import Flavors from '~/components/modules/Flavors/Flavors';
import HeroBanner from '~/components/modules/HeroBanner/HeroBanner';
import InfoModule from '~/components/modules/InfoModule/InfoModule';
import MachineFeature from '~/components/modules/MachineFeature/MachineFeature';
import Counters from '~/components/modules/Counters/Counters';
import MachineComparison from '~/components/modules/MachineComparison/MachineComparison';
import MachineDetails from '~/components/modules/MachineComparison/MachineDetails';
import CategoryButton from '~/components/modules/CategoryButton/CategoryButton';
import PostCard from '~/components/modules/PostCard/PostCard';
import ImageGallery from '~/components/modules/ImageGallery/ImageGallery';
import Sidenav from '~/components/modules/Sidenav/Sidenav';
import AuthorBlock from '~/components/modules/AuthorBlock/AuthorBlock';

// Common
import ImageBlob from '~/components/common/ImageBlob/ImageBlob';
import SingleBubble from '~/components/common/SingleBubble/SingleBubble';
import BulletList from '~/components/common/BulletList/BulletList';
import PageHero from '~/components/common/PageHero/PageHero';

// Register Plugins
gsap.registerPlugin(ScrollTrigger);
Vue.use(VueAwesomeSwiper);
Vue.use(VueAxios, axios);
Vue.use(VueMoment);

// Register Components
Vue.component('SiteHeader', SiteHeader);
Vue.component('SiteNavigation', SiteNavigation);
Vue.component('SiteFooter', SiteFooter);
Vue.component('SiteFooterNavigation', SiteFooterNavigation);
Vue.component('SiteFooterCopyright', SiteFooterCopyright);
Vue.component('TextBlock', TextBlock);
Vue.component('FeatureBlock', FeatureBlock);
Vue.component('ImageCarousel', ImageCarousel);
Vue.component('ImageGalleryCarousel', ImageGalleryCarousel);
Vue.component('CarouselDots', CarouselDots);
Vue.component('ImageBlob', ImageBlob);
Vue.component('SingleBubble', SingleBubble);
Vue.component('BulletList', BulletList);
Vue.component('PageHero', PageHero);
Vue.component('PressRelease', PressRelease);
Vue.component('Wave', Wave);
Vue.component('FormComponent', FormComponent);
Vue.component('Flavors', Flavors);
Vue.component('HeroBanner', HeroBanner);
Vue.component('InfoModule', InfoModule);
Vue.component('MachineFeature', MachineFeature);
Vue.component('CountersComponent', Counters);
Vue.component('MachineComparison', MachineComparison);
Vue.component('MachineDetails', MachineDetails);
Vue.component('NewsListing', NewsListing);
Vue.component('NewsFilter', NewsFilter);
Vue.component('CategoryButton', CategoryButton);
Vue.component('PostCard', PostCard);
Vue.component('ImageGallery', ImageGallery);
Vue.component('FeaturedNewsArticles', FeaturedNewsArticles);
Vue.component('Sidenav', Sidenav);
Vue.component('AuthorBlock', AuthorBlock);
Vue.component('NewsHero', NewsHero);
Vue.component('NewsContent', NewsContent);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
