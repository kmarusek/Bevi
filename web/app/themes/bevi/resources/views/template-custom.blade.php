{{--
  Template Name: Page With Header
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    {{-- <page-hero :wp="{{ json_encode($hero) }}"></page-hero> --}}
    <featured-news-articles :block="{{ json_encode($all_posts) }}"></featured-news-articles>
    @include('partials/flexible-content')
  @endwhile
@endsection
