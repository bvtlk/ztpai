@extends('layouts.app')

@section('content')
    <h1>{{ $job->title }}</h1>
    <p>{{ $job->description }}</p>
    <p>Location: {{ $job->location }}</p>
    <p>Salary: {{ $job->salary_from }} - {{ $job->salary_to }}</p>
    <a href="{{ route('applications.index', $job->id) }}">View Applications</a>
@endsection
