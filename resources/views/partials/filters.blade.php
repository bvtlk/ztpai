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
        <input type="number" id="salaryInput" class="filter-input" min="0" step="5000" placeholder="💰 Minimum salary">
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
