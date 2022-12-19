<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="preload" href="@php echo get_template_directory_uri() . '/assets/fonts/italian-plate/ItalianPlateNo1Expanded-Bold.woff' @endphp" as="font" crossorigin="anonymous" />
  <link rel="preload" href="@php echo get_template_directory_uri() . '/assets/fonts/italian-plate/ItalianPlateNo1Expanded-Bold.woff2' @endphp" as="font" crossorigin="anonymous" />
  @php wp_head() @endphp
  @if (is_page('careers'))
    <script src="https://www.workable.com/assets/embed.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8">
      whr(document).ready(function(){
        // whr_embed(250461, {detail: 'titles', base: 'jobs', zoom: 'country', grouping: 'none'});
        whr_embed(250461, {detail: 'titles', base: 'departments', zoom: 'city', });
      });
    </script>
  @endif
  <script type="text/javascript" src="//cdn.bizible.com/scripts/bizible.js" async=""></script>
  <!-- begin Convert Experiences code-->
  <script type="text/javascript" src="//cdn-4.convertexperiments.com/js/10035227-10034155.js"></script>
  <!-- end Convert Experiences code -->
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-NQTDRQW');</script>
  <!-- End Google Tag Manager -->
</head>
