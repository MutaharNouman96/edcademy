<x-educator-layout>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle"></i> Create New Course
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('educator.courses.crud.store') }}" method="POST"
                        id="create-form"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Basic Information -->
                            <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>

                          
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="course_category_id"
                                        class="form-select @error('course_category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('course_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Level</label>
                                    <input type="text" name="level" class="form-control"
                                        value="{{ old('level') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Language</label>
                                    <input type="text" name="language" class="form-control"
                                        value="{{ old('language') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Difficulty</label>
                                    <select name="difficulty" class="form-select">
                                        <option value="">Select</option>
                                        <option value="beginner"
                                            {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate"
                                            {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate
                                        </option>
                                        <option value="advanced"
                                            {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Pricing</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" step="0.01" name="price" class="form-control"
                                        value="{{ old('price') }}">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_free"
                                            {{ old('is_free') ? 'checked' : '' }}>
                                        <label class="form-check-label">Free Course</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Details -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Course Details</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Course Type</label>
                                    <select name="type" class="form-select">
                                        <option value="module">Module-based</option>
                                        <option value="video">Video Course</option>
                                        <option value="live">Live Course</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration</label>
                                    <input type="text" name="duration" class="form-control"
                                        placeholder="e.g., 8 weeks" value="{{ old('duration') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Schedule Date</label>
                                    <input type="datetime-local" name="schedule_date" class="form-control"
                                        value="{{ old('schedule_date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Thumbnail</label>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <!-- Drip Content -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Content Release</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="drip"
                                            {{ old('drip') ? 'checked' : '' }}>
                                        <label class="form-check-label">Enable Drip Content</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Drip Duration</label>
                                    <input type="text" name="drip_duration" class="form-control"
                                        value="{{ old('drip_duration') }}">
                                </div>
                            </div>

                            <!-- Publishing -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Publishing</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Publish Option</label>
                                    <select class="form-select" id="publish_option" name="publish_option">
                                        <option value="draft">Draft</option>
                                        <option value="now">Publish Now</option>
                                        <option value="schedule">Schedule</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="publish_date_wrapper" style="display:none">
                                    <label class="form-label">Publish Date</label>
                                    <input type="datetime-local" name="publish_date" class="form-control"
                                        value="{{ old('publish_date') }}">
                                </div>
                            </div>

                              <div class="mb-3">
                                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <button type="button" id="generate-ai" class="btn btn-primary mt-2">
                                    <i class="bi bi-robot"></i> Generate with AI
                                </button>
                            </div>


                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('educator.courses.crud.index') }}"
                                    class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg"></i> Create Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    After creating the course, you’ll be able to add sections and lessons.
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('publish_option').addEventListener('change', function() {
                document.getElementById('publish_date_wrapper').style.display =
                    this.value === 'schedule' ? 'block' : 'none';
            });

            document.getElementById('generate-ai').addEventListener('click', function() {
                //add loader to the button
                document.getElementById('generate-ai').disabled = true;
                document.getElementById('generate-ai').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';
                const form = document.querySelector('#create-form');
                const formData = new FormData(form);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("educator.generate.course.content") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('input[name="title"]').value = data.title;
                        document.querySelector('textarea[name="description"]').value = data.description;
                        document.getElementById('generate-ai').disabled = false;
                        document.getElementById('generate-ai').innerHTML = `<i class="bi bi-robot"></i> Generate with AI`;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while generating content.');
                });
            });
        </script>
    @endpush
</x-educator-layout>
