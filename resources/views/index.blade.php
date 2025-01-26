@extends('layouts.app')

@section('title', 'Job Offers')

@section('content')
    @if (session('role_id') != 1)
        @include('partials.filters')
        @include('partials.job-listing-table')
    @else
        @include('partials.applications-table')
    @endif
@endsection
