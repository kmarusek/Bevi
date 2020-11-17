{{--
  Template Name: Page With Header
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    {{-- <featured-news-articles :block="{{ json_encode($all_posts) }}"></featured-news-articles> --}}
    <page-hero :wp="{{ json_encode($hero) }}"></page-hero>
    @include('partials/flexible-content')
  @endwhile
@endsection
