@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <page-hero :block="{{ json_encode($hero) }}"></page-hero>
    <featured-news-articles :block="{{ json_encode($featured_articles) }}"></featured-news-articles>
    <news-listing :categories="{{ json_encode($all_categories) }}" />
  @endwhile
@endsection