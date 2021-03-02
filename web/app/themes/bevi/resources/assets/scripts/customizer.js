import $ from 'jquery';

// eslint-disable-next-line no-undef
wp.customize('blogname', (value) => {
  value.bind((to) => $('.brand').text(to));
});
