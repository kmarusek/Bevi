
    <site-footer 
      :inside-bevi="{{ json_encode(getMenuItemsFromLocation('primary_footer')) }}"
      :office="{{ json_encode(getMenuItemsFromLocation('office_footer')) }}"
      :support="{{ json_encode(getMenuItemsFromLocation('support_footer')) }}"
      :blog="{{ json_encode(getMenuItemsFromLocation('blog_footer')) }}"
      :terms="{{ json_encode(getMenuItemsFromLocation('terms_footer')) }}"
    ></site-footer>
