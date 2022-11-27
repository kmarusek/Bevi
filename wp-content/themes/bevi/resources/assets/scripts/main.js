/* eslint-disable import/extensions */
import { createApp } from 'vue';

import { registerScrollSpy } from 'vue3-scroll-spy';


import Swipe from './slider';

// Plugins
import iframeResizer from 'iframe-resizer';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VueScrollTo from 'vue-scrollto';

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
import FaqsListing from '~/components/core/FaqsListing/FaqsListing';

// Components
import InformationBanner from '~/components/core/InformationBanner/InformationBanner';
import TextBlock from '~/components/modules/TextBlock/TextBlock';
import FeatureBlock from '~/components/modules/FeatureBlock/FeatureBlock';
import ImageCarousel from '~/components/modules/ImageCarousel/ImageCarousel';
import ImageGalleryCarousel from '~/components/modules/ImageCarousel/ImageGalleryCarousel';
import CarouselDots from '~/components/modules/CarouselDots/CarouselDots';
import PressRelease from '~/components/modules/PressRelease/PressRelease';
import Wave from '~/components/modules/Wave/Wave';
import VideoComponent from '~/components/modules/VideoComponent/VideoComponent';
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
import Events from '~/components/modules/Events/Events';
import Compare from '~/components/modules/Compare/Compare';
import GravityForm from '~/components/modules/GravityForm/GravityForm';
import ThankYou from '~/components/modules/ThankYou/ThankYou';
import AuthorBlock from '~/components/modules/AuthorBlock/AuthorBlock';
import AllFlavors from '~/components/modules/AllFlavors/AllFlavors';
import FlavorsFilter from '~/components/modules/AllFlavors/FlavorsFilter';
import FlavorCard from '~/components/modules/AllFlavors/FlavorCard';
import FlavorTray from '~/components/modules/AllFlavors/FlavorTray';
import Careers from '~/components/modules/Careers/Careers';
import Faqs from '~/components/modules/Faqs/Faqs';
import CookieDeclaration from '~/components/modules/CookieDeclaration/CookieDeclaration';
import PricingInquiryHero from '~/components/modules/PricingInquiryHero/PricingInquiryHero';
import PageHeroWithFormAndVideo from '~/components/modules/PageHeroWithFormAndVideo/PageHeroWithFormAndVideo';
import PardotForm from '~/components/modules/PardotForm/PardotForm';
import Accordion from '~/components/modules/Accordion/Accordion';
import CustomDrinkBuilder from '~/components/modules/CustomDrinkBuilder/CustomDrinkBuilder';

// Common
import ImageBlob from '~/components/common/ImageBlob/ImageBlob';
import SingleBubble from '~/components/common/SingleBubble/SingleBubble';
import BulletList from '~/components/common/BulletList/BulletList';
import PageHero from '~/components/common/PageHero/PageHero';
import SkipLink from '~/components/common/SkipLink/SkipLink';

const app = createApp({});

// Register Plugins
gsap.registerPlugin(ScrollTrigger);
app.use(VueAxios, axios);
app.use(VueScrollTo, {
  offset: -110,
  duration: 500,
  easing: 'ease',
});
registerScrollSpy(app);

// Register Components
app.component('SiteHeader', SiteHeader);
app.component('SiteNavigation', SiteNavigation);
app.component('SiteFooter', SiteFooter);
app.component('SiteFooterNavigation', SiteFooterNavigation);
app.component('SiteFooterCopyright', SiteFooterCopyright);
app.component('TextBlock', TextBlock);
app.component('FeatureBlock', FeatureBlock);
app.component('ImageCarousel', ImageCarousel);
app.component('ImageGalleryCarousel', ImageGalleryCarousel);
app.component('CarouselDots', CarouselDots);
app.component('ImageBlob', ImageBlob);
app.component('SingleBubble', SingleBubble);
app.component('BulletList', BulletList);
app.component('PageHero', PageHero);
app.component('PressRelease', PressRelease);
app.component('Wave', Wave);
app.component('VideoComponent', VideoComponent);
app.component('FormComponent', FormComponent);
app.component('Flavors', Flavors);
app.component('HeroBanner', HeroBanner);
app.component('InfoModule', InfoModule);
app.component('MachineFeature', MachineFeature);
app.component('CountersComponent', Counters);
app.component('MachineComparison', MachineComparison);
app.component('MachineDetails', MachineDetails);
app.component('NewsListing', NewsListing);
app.component('NewsFilter', NewsFilter);
app.component('CategoryButton', CategoryButton);
app.component('PostCard', PostCard);
app.component('ImageGallery', ImageGallery);
app.component('FeaturedNewsArticles', FeaturedNewsArticles);
app.component('Sidenav', Sidenav);
app.component('Events', Events);
app.component('Compare', Compare);
app.component('AuthorBlock', AuthorBlock);
app.component('NewsHero', NewsHero);
app.component('AllFlavors', AllFlavors);
app.component('FlavorsFilter', FlavorsFilter);
app.component('FlavorCard', FlavorCard);
app.component('FlavorTray', FlavorTray);
app.component('NewsContent', NewsContent);
app.component('Careers', Careers);
app.component('Faqs', Faqs);
app.component('FaqsListing', FaqsListing);
app.component('CookieDeclaration', CookieDeclaration);
app.component('PricingInquiryHero', PricingInquiryHero);
app.component('PageHeroWithFormAndVideo', PageHeroWithFormAndVideo);
app.component('PardotForm', PardotForm);
app.component('Accordion', Accordion);
app.component('CustomDrinkBuilder', CustomDrinkBuilder);
app.component('SkipLink', SkipLink);
app.component('GravityForm', GravityForm);
app.component('ThankYou', ThankYou);
app.component('InformationBanner', InformationBanner);

app.mount('#app');
