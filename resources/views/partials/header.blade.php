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
            <button id="postJobButton" class="button button-post-offer">Post a Job</button>
        @elseif (!session()->has('user_id'))
            <button class="button organization-sign-in">Organization sign in</button>
        @endif
    </div>
</div>
