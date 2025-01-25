@php use App\Helpers\TimeHelper; @endphp
    <!DOCTYPE html>
<html>
<head>
    <title>Job Offers</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}" defer></script>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
</head>
<body>
@include('partials.modals')
<div class="header">
    <div class="text-container">
        @if (session('role_id') !== 1)
            <h1>Find Your Dream Employee</h1>
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="What are you looking for today...">
                <span class="material-symbols-outlined search-icon">search</span>
            </div>
        @else
            <h1>Find Your Dream Job</h1>
        @endif
    </div>

    <div style="position: relative;">
        <div class="button-container">
            @if (!session()->has('user_id'))
                <button class="button button-log">Log in</button>
                <button class="button button-sign">Sign up</button>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="button logout">Logout</button>
                </form>
            @endif
        </div>
    </div>

    <div class="fixed-button-container">
        @if (session()->has('user_id') && session('role_id') === 1)
            <!-- Przycisk "Post a Job" tylko dla użytkowników z role_id 1 -->
            <button id="postJobButton" class="button button-post-offer">Post a Job</button>
        @elseif (!session()->has('user_id'))
            <!-- Przycisk dla niezalogowanych organizacji -->
            <button class="button organization-sign-in">Organization sign in</button>
        @endif
    </div>
</div>


<div class="container">
    @if (session('role_id') != 1)
        <div class="filter-actions">
            <div class="filter-container">
                <select class="filter-input">
                    <option selected value="0">📌 Location</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-container">
                <input type="number" id="salaryInput" class="filter-input" min="0" step="5000"
                       placeholder="💰 Minimum salary">
            </div>
            <div class="filter-container">
                <select id="sortFilter" class="filter-input">
                    <option selected value="0">🚀 Sort by</option>
                    <option value="created_at-asc">Oldest first</option>
                    <option value="created_at-desc">Newest first</option>
                    <option value="salary_from-asc">Salary ⬆</option>
                    <option value="salary_from-desc">Salary ⬇</option>
                    <option value="location-asc">Location ⬆</option>
                    <option value="location-desc">Location ⬇</option>
                </select>
            </div>
        </div>
        <table id="job-listing-table">
            <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td class="position">
                        <h2>{{ $job->title }}</h2>
                        <h3>🏢 {{ $job->company }}</h3>
                        <h3>💰 ${{ $job->salary_from }} - ${{ $job->salary_to }}</h3>
                        <div class="location">📌 {{ $job->location }}</div>
                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                    </td>
                    <td class="tags">
                        @foreach (explode(',', $job->tags) as $tag)
                            <span class="tag">{{ trim($tag) }}</span>
                        @endforeach
                    </td>
                    <td class="time">
                        {{ TimeHelper::timeAgo($job->created_at) }}
                    </td>
                    <td class="source">
                        <div class="apply_button apply-button">Apply</div>
                        <div class="details_button details-button">Details</div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <table id="applications-table">
            <thead>
            <tr>
                <th>Job Title</th>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Applied At</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($applications as $application)
                <tr>
                    <td>{{ $application->job->title }}</td>
                    <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                    <td>{{ $application->email }}</td>
                    <td>
                        <button class="view-resume" data-id="{{ $application->id }}">View Resume</button>
                    </td>
                    <td>{{ $application->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>

<style>
    .header {
        background: url('{{ asset('img/main_banner.png') }}') no-repeat center #f4f4f4 !important;
    }
</style>
