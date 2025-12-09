<x-student-layout>


    <!-- Overlay for mobile sidebar -->
    <div class="overlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-3 sidebar p-0">
                <div class="p-3">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-orange);">Course Content</h5>

                    <!-- Progress Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Course Progress</span>
                            <span class="small fw-bold">35%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 35%;" aria-valuenow="35"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <!-- Chapters List -->
                    <div class="chapters-list accordion" id="chaptersAccordion">
                        @foreach($course_chapters as $c)
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="heading{{ $c->id }}">
                                    <button class="accordion-button {{ $c->id == $currentLesson->course_section_id ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $c->id }}" aria-expanded="{{ $c->id == $currentLesson->course_section_id ? 'true' : 'false' }}" aria-controls="collapse{{ $c->id }}">
                                        <h6 class="mb-0 fw-bold">{{ $c->title }} <span class="small text-muted">({{ $course_lessons->where('course_section_id', $c->id)->count() }} lessons)</span></h6>
                                    </button>
                                </h2>
                                <div id="collapse{{ $c->id }}" class="accordion-collapse collapse {{ $c->id == $currentLesson->course_section_id ? 'show' : '' }}" aria-labelledby="heading{{ $c->id }}" data-bs-parent="#chaptersAccordion">
                                    <div class="accordion-body p-0">
                                        @foreach($course_lessons->where('course_section_id', $c->id)->sortBy('id') as $lesson)
                                            <a href="{{ route('student.course_details', ['course_id' => $course->id, 'lesson_id' => $lesson->id]) }}"
                                               class="list-group-item list-group-item-action d-flex align-items-center ps-4 py-2 {{ $lesson->id == $currentLesson->id ? 'active-lesson' : '' }}">
                                                <i class="bi bi-play-circle me-2"></i>
                                                <div>
                                                    <p class="mb-0">{{ $lesson->title }}</p>
                                                </div>
                                                &nbsp;&nbsp;
                                                (<small class="text-muted">{{ gmdate("i:s", $lesson->duration) }}</small>)
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-9 main-content">
                <!-- Current Video Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="fw-bold mb-3">Current Lesson: {{ $currentLesson->title }}</h4>
                                    <div class="video-thumbnail mb-3 position-relative">
                                        <iframe src="{{ $currentLesson->video_link }}" frameborder="0" allowfullscreen class="img-fluid rounded" style="width: 100%; height: 350px;"></iframe>
                                        <div class="video-duration">{{ gmdate("i:s", $currentLesson->duration) }}</div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $educator->profile_picture ?? 'https://placehold.co/40x40/E55A2B/white?text=E' }}" class="rounded-circle me-2" alt="Profile Picture" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $educator->first_name.' '.$educator->last_name ?? 'Unknown Uploader' }}</h6>
                                            <small class="text-muted">Uploaded on {{ $course->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="badge bg-secondary">Lesson {{ $currentLesson->lesson_number }} of {{ $course_lessons->where('course_section_id', $currentLesson->course_section_id)->count() }}</span>
                                        </div>
                                        <button class="btn btn-primary d-none">
                                            <i class="bi bi-play-fill me-2"></i>Continue Watching
                                        </button>
                                    </div>
                                    <hr/>
                                    <h3>Notes</h3>
                                    <p class="text-muted mb-4">{{ $currentLesson->notes }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="fw-bold mb-3">Up Next</h5>
                                    <div class="list-group">
                                        @php
                                            $allLessonsSorted = $course_lessons->sortBy('id')->values();
                                            $currentIndex = $allLessonsSorted->search(function ($lesson) use ($currentLesson) {
                                                return $lesson->id === $currentLesson->id;
                                            });
                                            $upcomingLessons = $allLessonsSorted->slice($currentIndex + 1)->take(3);
                                        @endphp
                                        @foreach($upcomingLessons as $lesson)
                                            <a href="{{ route('student.course_details', ['course_id' => $course->id, 'lesson_id' => $lesson->id]) }}"
                                                class="list-group-item list-group-item-action d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="https://placehold.co/80x45/FF8C5A/white?text={{ $loop->iteration }}"
                                                        class="rounded me-3" alt="Thumbnail">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $lesson->title }}</h6>
                                                    <small class="text-muted">{{ gmdate("i:s", $lesson->duration) }}</small>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-body">

                                <div class="p-2">
                                    <div class="mt-4">
                                        <form id="comment-form" action="{{ route('student.lesson_comment.store') }}">
                                            @csrf
                                            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
                                            <div class="mb-3">
                                                <textarea class="form-control" name="comment" rows="3" placeholder="Add a comment..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Post Comment</button>
                                        </form>
                                    </div>
                                    <div class="mt-5">
                                    <h5 class="fw-bold mb-3">Comments ({{$comments->count()}})</h5>
                                    <div id="comments-list" class="mt-3">
                                        @foreach($comments as $comment)
                                            <div class="d-flex align-items-start mb-3">
                                                <img src="{{ $comment->user->profile_picture ?? 'https://placehold.co/40x40/E55A2B/white?text=U' }}" class="rounded-circle me-2" alt="Profile Picture" width="40" height="40">
                                                <div>
                                                    <h6 class="mb-0">{{ $comment->user->first_name . ' ' . $comment->user->last_name }} <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></h6>
                                                    <p class="mb-0">{{ $comment->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Update progress bar based on completed chapters
            const progressBar = document.querySelector('.progress-bar');
            const completedChapters = document.querySelectorAll('.chapter-item .bi-check-circle-fill').length;
            const totalChapters = document.querySelectorAll('.chapter-item').length;
            const progressPercentage = (completedChapters / totalChapters) * 100;

            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = `${Math.round(progressPercentage)}%`;
        });

        // AJAX for comments
        document.getElementById('comment-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const commentsList = document.getElementById('comments-list');
            const commentTextarea = form.querySelector('textarea[name="comment"]');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newCommentHtml = `
                        <div class="d-flex align-items-start mb-3">
                            <img src="${data.comment.user_profile_picture}" class="rounded-circle me-2" alt="Profile Picture" width="40" height="40">
                            <div>
                                <h6 class="mb-0">${data.comment.user_name} <small class="text-muted">${data.comment.created_at_human}</small></h6>
                                <p class="mb-0">${data.comment.comment_text}</p>
                            </div>
                        </div>
                    `;
                    commentsList.insertAdjacentHTML('afterbegin', newCommentHtml); // Add new comment at the top
                    commentTextarea.value = ''; // Clear the textarea
                } else {
                    alert('Error posting comment.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while posting your comment.');
            });
        });
    </script>
    <style>
        .chapter-item.active-chapter {
            background-color: var(--primary-orange);
            color: white;
            border-radius: 5px;
        }
        .chapter-item.active-chapter h6,
        .chapter-item.active-chapter p,
        .chapter-item.active-chapter i {
            color: white !important;
        }
        .active-lesson {
            color: #6F42C1 !important;
            background-color: #F3E8FF !important;
            border-radius: 5px;
        }
    </style>
</x-student-layout>
