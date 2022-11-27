<site-header
  :menu="{{ json_encode(getMenuItemsFromLocation('primary_navigation')) }}"
  :cta="{{ json_encode(get_field('navigation_cta', 'option')) }}"
  :banner="{{ json_encode(get_field('information_banner', 'option')) }}"
></site-header>
