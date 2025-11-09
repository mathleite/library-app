@extends('components.header')

@section('title', 'Edit Author - Library application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-0">Edit author</h2>
                </div>
                <div class="card-body">
                    <form id="authorForm" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nome" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="40"
                                placeholder="Enter author's name (maximum 40 characters)"
                                required
                            >
                            <div class="form-text">
                                <span id="charCount">0</span>/40 characters
                            </div>
                            <div class="invalid-feedback" id="nomeError">
                                Please, enter a valid name (maximum 40 characters).
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('authors.index-view') }}" class="btn btn-outline-secondary me-md-2">
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
                const form = document.getElementById('authorForm');
                const nomeInput = document.getElementById('name');
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

                nomeInput.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = length;

                    if (length > 40) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
                async function fetchData(authorId) {
                    const data = await fetch(`{{ url('api/v1/authors') }}/${authorId}`);
                    nomeInput.value = (await data.json()).name;
                }
                const id = '{{ request()->route('id') }}';
                fetchData(id);

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const nome = nomeInput.value.trim();

                    if (!nome || nome.length > 40) {
                        nomeInput.classList.add('is-invalid');
                        return;
                    }

                    nomeInput.classList.remove('is-invalid');

                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    try {
                        const route = "{{ route("authors.api.update", ['id' => ':id']) }}";
                        const finalRoute = route.replace(':id', '{{ request()->route('id') }}')
                        const response = await fetch(finalRoute, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name: nome })
                        });
                        const data = await response.json();

                        if (response.ok) {
                            window.location.href = '{{ route("authors.index-view") }}';
                        } else {
                            const err = new Error(data.message);
                            err.isApiError = true;
                            throw err;
                        }

                    } catch (error) {
                        if (error && error.isApiError) {
                            showErrorModal(error.message || 'An error occurred');
                        } else {
                            alert(error.message);
                        }

                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });

                nomeInput.addEventListener('blur', function() {
                    const nome = this.value.trim();
                    if (!nome || nome.length > 40) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        </script>
    @endpush
@endsection
