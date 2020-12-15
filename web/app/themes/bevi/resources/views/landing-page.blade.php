{{--
  Template Name: Landing Page
--}}

@extends('layouts.app-no-header')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials/flexible-content')
  @endwhile
@endsection
