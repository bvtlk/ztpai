document.addEventListener('DOMContentLoaded', function () {
    const locationFilter = document.querySelector('.filter-input');
    const salaryInput = document.getElementById('salaryInput');
    const sortFilter = document.getElementById('sortFilter');
    const searchBar = document.getElementById('search-bar');
    const loginModal = document.getElementById('loginModal');
    const signupModal = document.getElementById('signupModal');
    const organizationSignupModal = document.getElementById('organizationSignupModal');
    const buttonPostJobOffer = document.querySelector('.organization-sign-in');
    const buttonLog = document.querySelector('.button-log');
    const buttonSign = document.querySelector('.button-sign');
    const closeOrganization = document.querySelector('.close-organization');

    document.querySelectorAll('.view-resume').forEach(button => {
        button.addEventListener('click', function () {
            const applicationId = this.getAttribute('data-id');
            fetchResume(applicationId);
        });
    });

    function fetchResume(applicationId) {
        fetch(`/api/resume/${applicationId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.resume) {
                    const base64String = data.resume;
                    console.log(base64String);
                    const byteCharacters = atob(base64String);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], { type: 'application/pdf' });
                    const url = URL.createObjectURL(blob);
                    const resumeWindow = window.open(url);
                    resumeWindow.focus();
                } else {
                    throw new Error('Resume data not found');
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    if (locationFilter) {
        locationFilter.addEventListener('change', applyFilters);
    }
    if (salaryInput) {
        salaryInput.addEventListener('change', applyFilters);
    }
    if (sortFilter) {
        sortFilter.addEventListener('change', applyFilters);
    }
    if (searchBar) {
        searchBar.addEventListener('keyup', function (event) {
            if (event.key === 'Enter') {
                applyFilters();
            }
        });
    }
    if (buttonLog) {
        buttonLog.addEventListener('click', function () {
            toggleModal(loginModal, 'block', 'none');
        });
    }
    if (buttonSign) {
        buttonSign.addEventListener('click', function () {
            toggleModal(signupModal, 'block', 'none');
        });
    }
    if (buttonPostJobOffer) {
        buttonPostJobOffer.addEventListener('click', function () {
            const userId = getUserId();
            if (userId) {
                fetch('app/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'logout=1'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.reload();
                        } else {
                            alert('Failed to logout.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                toggleModal(organizationSignupModal, 'block', 'none');
            }
        });
    }
    if (closeOrganization) {
        closeOrganization.addEventListener('click', function () {
            organizationSignupModal.style.display = 'none';
        });
    }

    function getUserEmail() {
        const cookies = document.cookie.split('; ').reduce((prev, current) => {
            const [name, value] = current.split('=');
            prev[name] = value;
            return prev;
        }, {});

        return cookies['username'] || '';
    }

    function getUserId() {
        const cookies = document.cookie.split('; ').reduce((prev, current) => {
            const [name, value] = current.split('=');
            prev[name] = value;
            return prev;
        }, {});

        return cookies['user_id'] || '';
    }

    function applyFilters() {
        const locationValue = locationFilter.value || '';
        const salaryMin = parseInt(salaryInput.value) || 0;
        const sortValue = sortFilter.value || 'created_at-asc';
        const searchTerm = searchBar.value.trim().toLowerCase();

        let [sortField, sortOrder] = sortValue.split('-');

        const formData = new FormData();
        formData.append('location', locationValue);
        formData.append('salaryMin', salaryMin);
        formData.append('sortField', (sortField === '0' || sortField === '' || sortField === 0) ? 'created_at' : sortField);
        formData.append('sortOrder', sortOrder || 'asc');
        formData.append('searchTerm', searchTerm);

        fetch('api/jobs/filter', {
            method: 'POST',
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                updateJobListings(data);
                addEventListenersToButtons();
            })
            .catch((error) => console.error('Error:', error));
    }

    function timeAgo(date) {
        const now = new Date();
        const past = new Date(date);
        const diff = now - past;

        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);

        if (weeks > 0) {
            return `${weeks}w ago`;
        } else if (days > 0) {
            return `${days}d ago`;
        } else if (hours > 0) {
            return `${hours}h ago`;
        } else if (minutes > 0) {
            return `${minutes}m ago`;
        } else {
            return `${seconds}s ago`;
        }
    }

    function updateJobListings(jobs) {
        const tableBody = document.querySelector('#job-listing-table tbody');

        tableBody.innerHTML = '';

        jobs.forEach(job => {
            const row = document.createElement('tr');

            const positionCell = document.createElement('td');
            positionCell.classList.add('position');
            positionCell.innerHTML = `
                <h2>${job.title}</h2>
                <h3>🏢 ${job.company}</h3>
                <h3>💰 $${job.salary_from} - $${job.salary_to}</h3>
                <div class="location">📌 ${job.location}</div>
                <input type="hidden" name="job_id" value="${job.id}">
            `;
            row.appendChild(positionCell);

            const tagsCell = document.createElement('td');
            tagsCell.classList.add('tags');
            if (job.tags) {
                const tagsArray = job.tags.split(',');
                tagsArray.forEach(tag => {
                    const span = document.createElement('span');
                    span.classList.add('tag');
                    span.textContent = tag.trim();
                    tagsCell.appendChild(span);
                });
            }
            row.appendChild(tagsCell);

            const timeCell = document.createElement('td');
            timeCell.classList.add('time');
            timeCell.textContent = timeAgo(job.created_at);
            row.appendChild(timeCell);

            const sourceCell = document.createElement('td');
            sourceCell.classList.add('source');
            sourceCell.innerHTML = `
                <div class="apply_button apply-button">
                    <p>Apply</p>
                </div>
                <div class="details_button details-button">
                    <p>Details</p>
                </div>
            `;
            row.appendChild(sourceCell);

            tableBody.appendChild(row);
        });
    }

    function addEventListenersToButtons() {
        const detailsButtons = document.querySelectorAll('.details-button');
        const applyModal = document.getElementById('applyModal');
        const jobDetailsModal = document.getElementById('jobDetailsModal');
        const closeApply = document.querySelector('.close-apply');
        const applyForm = document.getElementById('apply-form');
        const loginModal = document.getElementById('loginModal');

        const applyButtons2 = document.querySelectorAll('.apply-button');

        applyButtons2.forEach(button => {
            button.addEventListener('click', function () {
                const userEmail = getUserEmail();
                const userId = getUserId();
                if (!userId) {
                    loginModal.style.display = 'block';
                } else {
                    const jobId = button.closest('tr').querySelector('input[name="job_id"]').value;
                    document.getElementById('applyEmail').value = userEmail;
                    document.getElementById('jobId').value = jobId;
                    applyModal.style.display = 'block';
                }
            });
        });

        detailsButtons.forEach(button => {
            button.addEventListener('click', function () {
                const jobId = button.closest('tr').querySelector('input[name="job_id"]').value;
                document.getElementById('jobId').value = jobId;

                fetch(`/api/jobs/${jobId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(job => {
                        document.getElementById('jobTitle').textContent = job.title;
                        document.getElementById('jobCompany').textContent = job.company;
                        document.getElementById('jobDescription').textContent = job.description;
                        document.getElementById('jobLocation').textContent = job.location;
                        document.getElementById('jobSalary').textContent = `$${job.salary_from} - $${job.salary_to}`;
                        document.getElementById('jobTags').textContent = job.tags;

                        jobDetailsModal.style.display = 'block';

                        const applyButtonInDetails = jobDetailsModal.querySelector('.button-apply');

                        applyButtonInDetails.addEventListener('click', function () {
                            const userEmail = getUserEmail();
                            const userId = getUserId();
                            if (!userId) {
                                jobDetailsModal.style.display = 'none';
                                loginModal.style.display = 'block';
                            } else {
                                jobDetailsModal.style.display = 'none';
                                applyModal.style.display = 'block';
                                document.getElementById('applyEmail').value = userEmail;
                                document.getElementById('jobId').value = jobId;
                            }
                        });
                    })
                    .catch(() => console.error('Failed to fetch job details'));
            });
        });

        document.querySelector('.close-details').addEventListener('click', function () {
            jobDetailsModal.style.display = 'none';
        });

        closeApply.addEventListener('click', function () {
            applyModal.style.display = 'none';
        });

        window.onclick = function (event) {
            if (event.target === applyModal) {
                applyModal.style.display = 'none';
            }
        };

        applyForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const applyEmail = document.getElementById('applyEmail').value;
            const resumeFile = document.getElementById('resume').files[0];
            const jobId = document.getElementById('jobId').value;

            if (resumeFile) {
                const resumeBase64 = await fileToBase64(resumeFile);
                const userId = getUserId();

                const formData = {
                    userId: userId,
                    jobId: jobId,
                    firstName: firstName,
                    lastName: lastName,
                    applyEmail: applyEmail,
                    resume: resumeBase64
                };

                fetch('/api/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Application submitted successfully!');
                            applyModal.style.display = 'none';
                        } else {
                            alert('Failed to submit application.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                alert('Please upload your resume.');
            }
        });

        function fileToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result.split(',')[1]);
                reader.onerror = error => reject(error);
                reader.readAsDataURL(file);
            });
        }

        const applyButtons = document.querySelectorAll('.apply-button');

        const cookies = document.cookie.split('; ').reduce((prev, current) => {
            const [name, value] = current.split('=');
            prev[name] = value;
            return prev;
        }, {});

        if ('user_id' in cookies) {
            applyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('applyEmail').value = getUserEmail();
                    applyModal.style.display = 'block';
                });
            });
        } else {
            applyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    document.querySelector('.button-log').click();
                });
            });
        }
    }

    const modal = document.getElementById('loginModal');
    const buttonHeader = document.querySelectorAll('.button-container');
    const buttonPostJobOfferContainer = document.querySelectorAll('.fixed-button-container');
    const organizationForm = document.getElementById('organization-register-form');
    const postJobModal = document.getElementById('postJobModal');
    const closePostJob = document.querySelector('.close-post-job');
    const postJobButton = document.querySelector('.button-post-offer');

    if (postJobButton) {
        postJobButton.addEventListener('click', function () {
            postJobModal.style.display = 'block';
        });
    }

    if (closePostJob) {
        closePostJob.addEventListener('click', function () {
            postJobModal.style.display = 'none';
        });
    }

    window.onclick = function (event) {
        if (event.target === postJobModal) {
            postJobModal.style.display = 'none';
        }
    }

    const postJobForm = document.getElementById('post-job-form');

    if (postJobForm) {
        postJobForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(postJobForm);
            const userId = getUserId();

            formData.append('posted_by_user_id', userId)

            fetch('/api/jobs', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Job posted successfully!');
                        postJobForm.reset();
                        postJobModal.style.display = 'none';
                    } else {
                        alert('Failed to post job: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }

    closeOrganization.addEventListener('click', function () {
        organizationSignupModal.style.display = 'none';
    });

    organizationForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const organizationEmail = document.getElementById('organizationEmail').value;
        const organizationPassword = document.getElementById('organizationPassword').value;
        const confirmOrganizationPassword = document.getElementById('confirmOrganizationPassword').value;

        if (organizationPassword !== confirmOrganizationPassword) {
            alert('Passwords do not match.');
            return;
        }

        const formData = new FormData();
        formData.append('signupEmail', organizationEmail);
        formData.append('signupPassword', organizationPassword);
        formData.append('signupPassword_confirmation', confirmOrganizationPassword);
        formData.append('role_id', 1);

        fetch('/api/register', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Organization registered successfully!');
                    organizationSignupModal.style.display = 'none';
                    buttonHeader.forEach(el => el.style.display = 'block');
                    buttonPostJobOfferContainer.forEach(el => el.style.display = 'block');
                } else {
                    alert('Failed to register organization.');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('register-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const signupEmail = document.getElementById('signupEmail').value;
        const signupPassword = document.getElementById('signupPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (signupPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        const formData = new FormData();
        formData.append('signupEmail', signupEmail);
        formData.append('signupPassword', signupPassword);
        formData.append('signupPassword_confirmation', confirmPassword);
        formData.append('role_id', 2);

        fetch('/api/register', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Failed to register user.');
                    });
                }
                return response.json();
            })
            .then(_ => {
                alert('User registered successfully!');
                signupModal.style.display = 'none';
                buttonHeader.forEach(el => el.style.display = 'block');
                buttonPostJobOfferContainer.forEach(el => el.style.display = 'block');
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Failed to register user.');
            });
    });


    window.onclick = function (event) {
        if (event.target === organizationSignupModal) {
            organizationSignupModal.style.display = 'none';
        }
    };

    function toggleModal(modal, display, headersDisplay) {
        modal.style.display = display;
        buttonHeader.forEach(el => el.style.display = headersDisplay);
        buttonPostJobOfferContainer.forEach(el => el.style.display = headersDisplay);
    }

    document.querySelectorAll('.button-log').forEach(element => {
        element.addEventListener('click', function () {
            toggleModal(modal, 'block', 'none');
        });
    });

    document.querySelectorAll('.signup-link').forEach(element => {
        element.addEventListener('click', function () {
            toggleModal(modal, 'none', 'none');
            signupModal.style.display = 'block';
        });
    });
    document.querySelectorAll('.login-link').forEach(element => {
        element.addEventListener('click', function () {
            toggleModal(signupModal, 'none', 'none');
            modal.style.display = 'block';
        });
    });

    document.querySelectorAll('.close').forEach((element, index) => {
        element.addEventListener('click', function () {
            const modalToClose = index === 0 ? modal : signupModal;
            toggleModal(modalToClose, 'none', 'block');
            buttonHeader.forEach(el => el.style.display = 'block');
            buttonPostJobOfferContainer.forEach(el => el.style.display = 'block');
        });
    });

    closeOrganization.addEventListener('click', function () {
        organizationSignupModal.style.display = 'none';
        buttonHeader.forEach(el => el.style.display = 'block');
        buttonPostJobOfferContainer.forEach(el => el.style.display = 'block');
    });

    document.querySelectorAll('.button-sign').forEach(element => {
        element.addEventListener('click', function () {
            toggleModal(signupModal, 'block', 'none');
        });
    });

    addEventListenersToButtons();
});
