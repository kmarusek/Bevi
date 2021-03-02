<site-header 
  :menu="{{ json_encode(getMenuItemsFromLocation('primary_navigation')) }}" 
  :cta="{{ json_encode(get_field('navigation_cta', 'option')) }}"
></site-header>