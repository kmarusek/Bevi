@extends('layouts.app')

@section('content')
  <page-hero :block="{{ json_encode(get_field('hero', get_option('page_for_posts'))) }}"></page-hero>
  <featured-news-articles :block="{{ json_encode($featured_articles) }}"></featured-news-articles>
  <news-listing :categories="{{ json_encode($all_categories) }}"></news-listing>
  <form-component :block="{{ json_encode(get_field('blog_form', get_option('page_for_posts'))) }}"></form-component>
@endsection