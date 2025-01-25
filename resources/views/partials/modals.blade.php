<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="login-form" method="post" action="{{ route('login') }}">
            @csrf
            <h2>Login</h2>
            <label for="username">E-mail:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
            <br/>
            <p>Not a member yet? <a href="{{ route('register') }}" class="signup-link">Sign up</a></p>
        </form>
    </div>
</div>
<div id="jobDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-details">&times;</span>
        <h2 id="jobTitle"></h2>
        <table class="job-details-table">
            <tr>
                <td><strong>Company:</strong></td>
                <td id="jobCompany"></td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td id="jobDescription"></td>
            </tr>
            <tr>
                <td><strong>Location:</strong></td>
                <td id="jobLocation"></td>
            </tr>
            <tr>
                <td><strong>Salary:</strong></td>
                <td id="jobSalary"></td>
            </tr>
            <tr>
                <td><strong>Tags:</strong></td>
                <td id="jobTags"></td>
            </tr>
        </table>
        <button class="button button-apply">Apply</button>
    </div>
</div>
<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="register-form" method="post" action="{{ route('register') }}">
            @csrf
            <h2>Sign Up</h2>
            <label for="signupEmail">E-mail:</label>
            <input type="text" id="signupEmail" name="email" required>
            <label for="signupPassword">Password:</label>
            <input type="password" id="signupPassword" name="password" required>
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="password_confirmation" required>
            <input type="submit" value="Sign Up">
        </form>
    </div>
</div>

<div id="organizationSignupModal" class="modal">
    <div class="modal-content">
        <span class="close-organization">&times;</span>
        <form id="organization-register-form" method="post" action="{{ route('register') }}">
            @csrf
            <h2>Organization Sign Up</h2>
            <label for="organizationEmail">E-mail:</label>
            <input type="text" id="organizationEmail" name="email" required>
            <label for="organizationPassword">Password:</label>
            <input type="password" id="organizationPassword" name="password" required>
            <label for="confirmOrganizationPassword">Confirm Password:</label>
            <input type="password" id="confirmOrganizationPassword" name="password_confirmation" required>
            <input type="hidden" name="role_id" value="2">
            <input type="submit" value="Sign Up">
        </form>
    </div>
</div>

<div id="applyModal" class="modal">
    <div class="modal-content">
        <span class="close-apply">&times;</span>
        <form id="apply-form" method="post" action="{{ route('jobs.apply') }}" enctype="multipart/form-data">
            @csrf
            <h2>Apply for Job</h2>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="first_name" required>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="last_name" required>
            <label for="applyEmail">E-mail:</label>
            <input type="email" id="applyEmail" name="email" required>
            <label for="resume">Upload CV:</label>
            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            <input type="hidden" id="jobId" name="job_id">
            <input type="submit" value="Submit Application">
        </form>
    </div>
</div>

<div id="postJobModal" class="modal">
    <div class="modal-content">
        <span class="close-post-job">&times;</span>
        <h2>Post a New Job</h2>
        <form id="post-job-form" method="post">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="company">Company:</label>
            <input type="text" id="company" name="company" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            <label for="salary_from">Salary From:</label>
            <input type="number" id="salary_from" name="salary_from" required>
            <label for="salary_to">Salary To:</label>
            <input type="number" id="salary_to" name="salary_to" required>
            <label for="tags">Tags (comma separated):</label>
            <input type="text" id="tags" name="tags" required>
            <input type="submit" value="Post Job">
        </form>
    </div>
</div>
