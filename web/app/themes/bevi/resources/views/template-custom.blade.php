{{--
  Template Name: Page With Header
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <page-hero :wp="{{ json_encode($hero) }}"></page-hero>
    @include('partials/flexible-content')
  @endwhile
@endsection
