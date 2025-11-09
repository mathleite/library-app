@extends('components.header')

@section('title', 'Create Subject - Library application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-0">Create new subject</h2>
                </div>
                <div class="card-body">
                    <form id="subjectForm" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input
                                type="text"
                                class="form-control"
                                id="description"
                                name="description"
                                maxlength="40"
                                placeholder="Enter subject description (maximum 40 characters)"
                                required
                            >
                            <div class="form-text">
                                <span id="charCount">0</span>/40 characters
                            </div>
                            <div class="invalid-feedback" id="descriptionError">
                                Please, enter a valid description (maximum 40 characters).
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('subjects.index-view') }}" class="btn btn-outline-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="errorModalLabel">Oops!</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="errorModalMessage" class="mb-0"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('subjectForm');
                const descInput = document.getElementById('description');
                const charCount = document.getElementById('charCount');
                const submitBtn = document.getElementById('submitBtn');
                const spinner = submitBtn.querySelector('.spinner-border');

                // Modal setup
                const errorModalEl = document.getElementById('errorModal');
                let errorModalInstance = null;
                const errorModalMessage = document.getElementById('errorModalMessage');
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    errorModalInstance = new bootstrap.Modal(errorModalEl);
                }

                function showErrorModal(message) {
                    if (errorModalInstance) {
                        errorModalMessage.textContent = message;
                        errorModalInstance.show();
                    } else {
                        alert(message);
                    }
                }

                descInput.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = length;

                    if (length > 40) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const description = descInput.value.trim();

                    if (!description || description.length > 40) {
                        descInput.classList.add('is-invalid');
                        return;
                    }

                    descInput.classList.remove('is-invalid');

                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    try {
                        const response = await fetch('{{ route("subjects.api.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ description })
                        });

                        let data = await response.json();

                        if (response.ok) {
                            window.location.href = '{{ route("subjects.index-view") }}';
                            return;
                        }

                        const message = (data && data.message) ? data.message : ('API error: ' + (response.status || 'unknown'));
                        const apiError = new Error(message);
                        apiError.isApiError = true;
                        throw apiError;

                    } catch (error) {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');

                        if (error && error.isApiError) {
                            showErrorModal(error.message || 'An error occurred');
                        }
                    }
                });

                descInput.addEventListener('blur', function() {
                    const description = this.value.trim();
                    if (!description || description.length > 40) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        </script>
    @endpush
@endsection

