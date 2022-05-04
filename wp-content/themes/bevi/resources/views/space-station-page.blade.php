{{--
  Template Name: Space Station Page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  @include('partials.bbcontent')
  @endwhile
@endsection
