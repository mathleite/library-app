@extends('components.header')

@section('title', 'Edit Book - Library application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-0">Edit book</h2>
                </div>
                <div class="card-body">
                    <form id="bookForm" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="40" placeholder="Enter title" required>
                                <div class="form-text"><span id="titleCount">0</span>/40 characters</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="editor" class="form-label">Editor</label>
                                <input type="text" class="form-control" id="editor" name="editor" maxlength="40" placeholder="Enter editor" required>
                                <div class="form-text"><span id="editorCount">0</span>/40 characters</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="publication_year" class="form-label">Publication year</label>
                                <input type="text" class="form-control" id="publication_year" name="publication_year" maxlength="4" placeholder="e.g. 2024" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="edition" class="form-label">Edition</label>
                                <input type="number" class="form-control" id="edition" name="edition" min="1" max="9999" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="price-currency"></span>
                                    <input type="text"
                                           class="form-control"
                                           id="price"
                                           name="price"
                                           placeholder="0.00"
                                           required
                                           data-mask="#.##0,00"
                                           data-mask-reverse="true">
                                </div>
                                <div class="form-text">Enter price. <small>Will be stored as integer (cents).</small></div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="authors" class="form-label">Authors</label>
                                <div id="authors" class="d-flex flex-wrap gap-2">
                                    <!-- Checkbox options will be populated by JS -->
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="subjects" class="form-label">Subjects</label>
                                <div id="subjects" class="d-flex flex-wrap gap-2">
                                    <!-- Checkbox options will be populated by JS -->
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('books.index-view') }}" class="btn btn-outline-secondary me-md-2">
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
            document.addEventListener('DOMContentLoaded', initForm);

            // Modal setup
            const _errorModalEl = document.getElementById('errorModal');
            let _errorModalInstance = null;
            const _errorModalMessage = document.getElementById('errorModalMessage');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                _errorModalInstance = new bootstrap.Modal(_errorModalEl);
            }

            function _showErrorModal(message) {
                if (_errorModalInstance) {
                    _errorModalMessage.textContent = message;
                    _errorModalInstance.show();
                } else {
                    alert(message);
                }
            }

            const StringUtils = {
                onlyDigits: (str) => str.replace(/\D/g, ''),

                safeToString: (value) => value?.toString() || '',

                truncate: (str, maxLength) => str?.substring(0, maxLength) || ''
            };

            const NumberUtils = {
                safeParseInt: (value, radix = 10) => {
                    const parsed = parseInt(value, radix);
                    return Number.isNaN(parsed) ? null : parsed;
                },

                normalizeToCents: (value) => {
                    const digits = StringUtils.onlyDigits(value);
                    return digits ? parseInt(digits, 10) : 0;
                },

                formatCurrency: (value) => {
                    const digits = StringUtils.onlyDigits(value);
                    if (!digits) return '';

                    const paddedValue = digits.padStart(3, '0');
                    const integerPart = paddedValue.slice(0, -2);
                    const centsPart = paddedValue.slice(-2);

                    return `${Number(integerPart).toLocaleString()}.${centsPart}`;
                }
            };

            const DOMUtils = {
                getElement: (id) => document.getElementById(id),

                createElement: (tag, className = '') => {
                    const element = document.createElement(tag);
                    if (className) element.className = className;
                    return element;
                },

                createCheckboxCell: (itemId, isChecked, type) => {
                    const td = DOMUtils.createElement('td');
                    td.style.width = '1%';
                    td.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           value="${itemId}" id="${type}_${itemId}"
                           ${isChecked ? 'checked' : ''}>
                </div>
            `;
                    return td;
                },

                createTextCell: (content) => {
                    const td = DOMUtils.createElement('td');
                    td.textContent = content;
                    return td;
                },

                updateCharacterCount: (inputElement, countElement) => {
                    countElement.textContent = StringUtils.safeToString(inputElement.value).length;
                }
            };

            const DataProcessor = {
                extractSelectedIds: (items) => {
                    return items
                        .map(item => {
                            if (item == null) return null;
                            if (typeof item === 'number') return item;
                            if (typeof item === 'object') {
                                return NumberUtils.safeParseInt(
                                    item.id ?? item.author_id ?? item.subject_id ?? null
                                );
                            }
                            return NumberUtils.safeParseInt(item);
                        })
                        .filter(Boolean);
                },

                normalizePriceData: (priceData) => {
                    if (priceData == null) return null;
                    if (typeof priceData === 'number') return priceData;
                    if (typeof priceData === 'object') {
                        return NumberUtils.safeParseInt(
                            priceData.amount ?? priceData.cents ?? priceData.value
                        );
                    }
                    return null;
                },

                formatPriceDisplay: (priceCents) => {
                    if (!priceCents) return '';
                    return (priceCents / 100).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            };

            const UIRenderer = {
                createItemsTable: (items, selectedIds, containerId, type, nameField) => {
                    const container = DOMUtils.getElement(containerId);
                    container.innerHTML = '';

                    const table = DOMUtils.createElement('table', 'table table-sm table-striped table-bordered mb-0');
                    const tbody = DOMUtils.createElement('tbody');

                    items.forEach(item => {
                        const row = DOMUtils.createElement('tr');
                        row.id = `${type}_row_${item.id}`;

                        const isChecked = selectedIds.includes(Number(item.id));
                        const checkboxCell = DOMUtils.createCheckboxCell(item.id, isChecked, type);
                        const nameCell = DOMUtils.createTextCell(item[nameField]);

                        row.appendChild(checkboxCell);
                        row.appendChild(nameCell);
                        tbody.appendChild(row);
                    });

                    table.appendChild(tbody);
                    container.appendChild(table);
                },

                populateFormFields: (bookData) => {
                    const fields = {
                        'title': bookData.title || '',
                        'editor': bookData.editor || '',
                        'publication_year': bookData.publicationYear || '',
                        'edition': bookData.edition || ''
                    };

                    Object.entries(fields).forEach(([fieldId, value]) => {
                        const element = DOMUtils.getElement(fieldId);
                        if (element) element.value = value;
                    });

                    const priceCents = DataProcessor.normalizePriceData(bookData.price);
                    const priceInput = DOMUtils.getElement('price');
                    if (priceInput) {
                        priceInput.value = DataProcessor.formatPriceDisplay(priceCents);
                    }

                    const currencyElement = DOMUtils.getElement('price-currency');
                    if (currencyElement && bookData.price?.currency) {
                        currencyElement.innerHTML = bookData.price.currency;
                    }

                    UIRenderer.updateCharacterCounters(bookData);
                },

                updateCharacterCounters: (bookData) => {
                    const titleCount = DOMUtils.getElement('titleCount');
                    const editorCount = DOMUtils.getElement('editorCount');

                    if (titleCount) {
                        titleCount.textContent = StringUtils.safeToString(bookData.title).length;
                    }
                    if (editorCount) {
                        editorCount.textContent = StringUtils.safeToString(bookData.editor).length;
                    }
                }
            };

            const ApiService = {
                fetchAllData: async (bookId) => {
                    const endpoints = [
                        { url: '{{ route("authors.api.index") }}', key: 'authors' },
                        { url: '{{ route("subjects.api.index") }}', key: 'subjects' },
                        { url: `{{ url('api/v1/books') }}/${bookId}`, key: 'book' }
                    ];

                    const responses = await Promise.all(
                        endpoints.map(endpoint => fetch(endpoint.url))
                    );

                    const data = await Promise.all(
                        responses.map(response => response.json())
                    );

                    return endpoints.reduce((acc, endpoint, index) => {
                        acc[endpoint.key] = data[index];
                        return acc;
                    }, {});
                },

                updateBook: async (bookId, data) => {
                    const route = "{{ route('books.api.update', ['id' => ':id']) }}".replace(':id', bookId);

                    const response = await fetch(route, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });

                    return response;
                }
            };

            const FormValidator = {
                validateField: (value, fieldName, maxLength = null, isNumber = false) => {
                    console.log(value, fieldName)
                    if (!value || value instanceof String && value.trim() === '') {
                        return `${fieldName} is required`;
                    }

                    if (maxLength && value.length > maxLength) {
                        return `${fieldName} must not exceed ${maxLength} characters`;
                    }

                    if (isNumber) {
                        const numValue = NumberUtils.safeParseInt(value);
                        if (numValue === null || numValue < 1) {
                            return `${fieldName} must be a valid positive number`;
                        }
                    }

                    return null;
                },

                validateForm: (formData) => {
                    const validations = [
                        { field: formData.title, name: 'Title', maxLength: 40 },
                        { field: formData.editor, name: 'Editor', maxLength: 40 },
                        { field: formData.publication_year, name: 'Publication year', maxLength: 4 },
                        { field: formData.edition, name: 'Edition', isNumber: true },
                        { field: formData.price, name: 'Price', isNumber: true, checkMin: 0 }
                    ];

                    for (const validation of validations) {
                        const error = FormValidator.validateField(
                            validation.field,
                            validation.name,
                            validation.maxLength,
                            validation.isNumber
                        );
                        if (error) return error;
                    }

                    if (!formData.authors.length) return 'Select at least one author';
                    if (!formData.subjects.length) return 'Select at least one subject';

                    return null;
                }
            };

            const FormManager = {
                initForm: () => {
                    const bookId = '{{ request()->route('id') }}';
                    FormManager.loadBookData(bookId);
                    FormManager.setupEventListeners();
                },

                loadBookData: async (bookId) => {
                    try {
                        const { authors, subjects, book } = await ApiService.fetchAllData(bookId);

                        const authorsList = authors?.items ?? authors ?? [];
                        const subjectsList = subjects?.items ?? subjects ?? [];

                        const selectedAuthorIds = DataProcessor.extractSelectedIds(book?.authors ?? []);
                        const selectedSubjectIds = DataProcessor.extractSelectedIds(book?.subjects ?? []);

                        UIRenderer.createItemsTable(authorsList, selectedAuthorIds, 'authors', 'author', 'name');
                        UIRenderer.createItemsTable(subjectsList, selectedSubjectIds, 'subjects', 'subject', 'description');
                        UIRenderer.populateFormFields(book);

                    } catch (error) {
                        console.error('Error loading book data:', error);
                        if (error && error.isApiError) {
                            _showErrorModal(error.message || 'Error loading book data');
                        }
                    }
                },

                setupEventListeners: () => {
                    const titleInput = DOMUtils.getElement('title');
                    const editorInput = DOMUtils.getElement('editor');
                    const priceInput = DOMUtils.getElement('price');
                    const form = DOMUtils.getElement('bookForm');

                    if (titleInput) {
                        titleInput.addEventListener('input', () =>
                            DOMUtils.updateCharacterCount(titleInput, DOMUtils.getElement('titleCount'))
                        );
                    }

                    if (editorInput) {
                        editorInput.addEventListener('input', () =>
                            DOMUtils.updateCharacterCount(editorInput, DOMUtils.getElement('editorCount'))
                        );
                    }

                    if (priceInput) {
                        priceInput.addEventListener('input', () =>
                            FormManager.handlePriceInput(priceInput)
                        );
                    }

                    if (form) {
                        form.addEventListener('submit', (e) => FormManager.handleFormSubmit(e));
                    }
                },

                handlePriceInput: (priceInput) => {
                    priceInput.value = NumberUtils.formatCurrency(priceInput.value);
                },

                handleFormSubmit: async (event) => {
                    event.preventDefault();

                    const formData = FormManager.collectFormData();
                    const validationError = FormValidator.validateForm(formData);

                    if (validationError) {
                        alert(validationError);
                        return;
                    }

                    await FormManager.submitForm(formData);
                },

                collectFormData: () => {
                    const getSelectedCheckboxes = (containerId) =>
                        Array.from(document.querySelectorAll(`#${containerId} input[type=checkbox]:checked`))
                            .map(checkbox => NumberUtils.safeParseInt(checkbox.value))
                            .filter(Boolean);

                    return {
                        title: DOMUtils.getElement('title')?.value.trim() || '',
                        editor: DOMUtils.getElement('editor')?.value.trim() || '',
                        publication_year: DOMUtils.getElement('publication_year')?.value.trim() || '',
                        edition: NumberUtils.safeParseInt(DOMUtils.getElement('edition')?.value) || 0,
                        price: NumberUtils.normalizeToCents(DOMUtils.getElement('price')?.value),
                        authors: getSelectedCheckboxes('authors'),
                        subjects: getSelectedCheckboxes('subjects')
                    };
                },

                submitForm: async (formData) => {
                    const submitBtn = DOMUtils.getElement('submitBtn');
                    const spinner = submitBtn?.querySelector('.spinner-border');
                    const bookId = '{{ request()->route('id') }}';

                    FormManager.setLoadingState(true, submitBtn, spinner);

                    try {
                        const response = await ApiService.updateBook(bookId, formData);
                        const data = await response.json();

                        if (response.ok) {
                            window.location.href = '{{ route("books.index-view") }}';
                        } else {
                            const err = new Error(data.message || 'Error updating book');
                            err.isApiError = true;
                            throw err;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (error && error.isApiError) {
                            _showErrorModal('Error updating book: ' + error.message);
                        } else {
                            alert('Error updating book: ' + error.message);
                        }
                        FormManager.setLoadingState(false, submitBtn, spinner);
                    }
                },

                setLoadingState: (isLoading, submitBtn, spinner) => {
                    if (submitBtn) submitBtn.disabled = isLoading;
                    if (spinner) {
                        spinner.classList.toggle('d-none', !isLoading);
                    }
                }
            };

            function initForm() {
                FormManager.initForm();
            }
        </script>
    @endpush
@endsection

