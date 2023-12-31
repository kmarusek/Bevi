@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  <news-hero :post-data="{{ json_encode(App::getPostData()) }}" :categories="{{ json_encode($all_categories) }}"></news-hero>
  <news-content :post-data="{{ json_encode(App::getPostData()) }}"></news-content>
  <author-block :post-data="{{ json_encode(App::getPostData()) }}"></author-block>
  @endwhile
@endsection
