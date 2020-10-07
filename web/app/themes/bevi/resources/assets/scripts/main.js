/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins

// Components
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';

// Register Plugins

// Register Components
Vue.component('SiteHeader', SiteHeader);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
