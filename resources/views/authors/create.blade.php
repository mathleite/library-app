@extends('components.header')

@section('title', 'Create Author - Library application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-0">Create new author</h2>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('authorForm');
                const nomeInput = document.getElementById('name');
                const charCount = document.getElementById('charCount');
                const submitBtn = document.getElementById('submitBtn');
                const spinner = submitBtn.querySelector('.spinner-border');

                nomeInput.addEventListener('input', function() {
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

                    const nome = nomeInput.value.trim();

                    if (!nome || nome.length > 40) {
                        nomeInput.classList.add('is-invalid');
                        return;
                    }

                    nomeInput.classList.remove('is-invalid');

                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    try {
                        const response = await fetch('{{ route("authors.api.store") }}', {
                            method: 'POST',
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
                            throw new Error(data.message || 'Erro ao cadastrar autor');
                        }

                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro ao cadastrar autor: ' + error.message);

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
