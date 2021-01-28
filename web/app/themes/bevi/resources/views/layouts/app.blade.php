<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NQTDRQW"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @php do_action('get_header') @endphp
    <div id="app">
      @include('partials.header')
      <main>
        @yield('content')
      </main>
      @php do_action('get_footer') @endphp
        @include('partials.footer')
      </div>
    @php wp_footer() @endphp  <script id="CookieDeclaration" src="https://consent.cookiebot.com/87b4ec8d-7070-4d14-a5f7-e12be46378df/cd.js" type="text/javascript" data-blockingmode="auto"></script>
  </body>
</html>
