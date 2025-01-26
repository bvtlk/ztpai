@php use App\Helpers\TimeHelper; @endphp

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
