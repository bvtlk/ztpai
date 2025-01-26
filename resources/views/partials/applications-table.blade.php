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
