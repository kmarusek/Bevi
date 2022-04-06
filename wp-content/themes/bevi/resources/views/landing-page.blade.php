{{--
  Template Name: Landing Page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials/flexible-content')
  @endwhile
@endsection
