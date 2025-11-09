@extends('components.header')

@section('title', 'Create Book - Library application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h2 class="h5 mb-0">Create new book</h2>
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
                                    <span class="input-group-text" id="price-currency">BRL</span>
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
                },

                isValidPositiveInteger: (value) =>
                    Number.isInteger(value) && value > 0
            };

            const DOMUtils = {
                getElement: (id) => document.getElementById(id),

                createElement: (tag, className = '') => {
                    const element = document.createElement(tag);
                    if (className) element.className = className;
                    return element;
                },

                createCheckboxCell: (itemId, type) => {
                    const td = DOMUtils.createElement('td');
                    td.style.width = '1%';
                    td.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           value="${itemId}" id="${type}_${itemId}">
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
                    if (inputElement && countElement) {
                        countElement.textContent = StringUtils.safeToString(inputElement.value).length;
                    }
                },

                getSelectedCheckboxValues: (containerSelector) =>
                    Array.from(document.querySelectorAll(containerSelector))
                        .map(checkbox => NumberUtils.safeParseInt(checkbox.value))
                        .filter(Boolean)
            };

            const UIRenderer = {
                createItemsTable: (items, containerId, type, nameField) => {
                    const container = DOMUtils.getElement(containerId);
                    if (!container) return;

                    container.innerHTML = '';

                    const table = DOMUtils.createElement('table', 'table table-sm table-striped table-bordered mb-0');
                    const tbody = DOMUtils.createElement('tbody');

                    items.forEach(item => {
                        const row = DOMUtils.createElement('tr');
                        row.id = `${type}_row_${item.id}`;

                        const checkboxCell = DOMUtils.createCheckboxCell(item.id, type);
                        const nameCell = DOMUtils.createTextCell(item[nameField]);

                        row.appendChild(checkboxCell);
                        row.appendChild(nameCell);
                        tbody.appendChild(row);
                    });

                    table.appendChild(tbody);
                    container.appendChild(table);
                },

                updateCharacterCounters: (titleInput, editorInput) => {
                    const titleCount = DOMUtils.getElement('titleCount');
                    const editorCount = DOMUtils.getElement('editorCount');

                    DOMUtils.updateCharacterCount(titleInput, titleCount);
                    DOMUtils.updateCharacterCount(editorInput, editorCount);
                }
            };

            const ApiService = {
                fetchOptions: async () => {
                    const endpoints = [
                        {
                            url: '{{ route("authors.api.index") }}',
                            key: 'authors',
                            containerId: 'authors',
                            nameField: 'name'
                        },
                        {
                            url: '{{ route("subjects.api.index") }}',
                            key: 'subjects',
                            containerId: 'subjects',
                            nameField: 'description'
                        }
                    ];

                    try {
                        const responses = await Promise.all(
                            endpoints.map(endpoint => fetch(endpoint.url))
                        );

                        const data = await Promise.all(
                            responses.map(response => response.json())
                        );

                        endpoints.forEach((endpoint, index) => {
                            const items = data[index]?.items ?? data[index] ?? [];
                            UIRenderer.createItemsTable(
                                items,
                                endpoint.containerId,
                                endpoint.key.slice(0, -1), // Remove 's' from plural
                                endpoint.nameField
                            );
                        });

                    } catch (error) {
                        console.error('Error fetching options:', error);
                        if (error && error.isApiError) {
                            _showErrorModal(error.message || 'Error fetching options');
                        } else {
                            throw error;
                        }
                    }
                },

                createBook: async (bookData) => {
                    const response = await fetch('{{ route("books.api.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(bookData)
                    });

                    return response;
                }
            };

            const FormValidator = {
                validateRequiredField: (value, fieldName, maxLength = null) => {
                    if (!value || value instanceof String && value.trim() === '') {
                        return `${fieldName} is required`;
                    }

                    if (maxLength && value.length > maxLength) {
                        return `${fieldName} must not exceed ${maxLength} characters`;
                    }

                    return null;
                },

                validateNumberField: (value, fieldName, minValue = 1) => {
                    if (!NumberUtils.isValidPositiveInteger(value)) {
                        return `${fieldName} must be a valid positive number`;
                    }

                    if (value < minValue) {
                        return `${fieldName} must be at least ${minValue}`;
                    }

                    return null;
                },

                validatePrice: (price) => {
                    if (!Number.isInteger(price) || price < 0) {
                        return 'Invalid price';
                    }
                    return null;
                },

                validateSelectedItems: (items, itemType) => {
                    if (!items.length) {
                        return `Select at least one ${itemType}`;
                    }
                    return null;
                },

                validateForm: (formData) => {
                    const validations = [
                        {
                            validator: FormValidator.validateRequiredField,
                            args: [formData.title, 'Title', 40]
                        },
                        {
                            validator: FormValidator.validateRequiredField,
                            args: [formData.editor, 'Editor', 40]
                        },
                        {
                            validator: FormValidator.validateRequiredField,
                            args: [formData.publication_year, 'Publication year', 4]
                        },
                        {
                            validator: FormValidator.validateNumberField,
                            args: [formData.edition, 'Edition']
                        },
                        {
                            validator: FormValidator.validatePrice,
                            args: [formData.price]
                        },
                        {
                            validator: FormValidator.validateSelectedItems,
                            args: [formData.authors, 'author']
                        },
                        {
                            validator: FormValidator.validateSelectedItems,
                            args: [formData.subjects, 'subject']
                        }
                    ];

                    for (const validation of validations) {
                        const error = validation.validator(...validation.args);
                        if (error) return error;
                    }

                    return null;
                }
            };

            const FormManager = {
                elements: {},

                initializeElements: () => {
                    FormManager.elements = {
                        titleInput: DOMUtils.getElement('title'),
                        editorInput: DOMUtils.getElement('editor'),
                        titleCount: DOMUtils.getElement('titleCount'),
                        editorCount: DOMUtils.getElement('editorCount'),
                        priceInput: DOMUtils.getElement('price'),
                        publicationYearInput: DOMUtils.getElement('publication_year'),
                        editionInput: DOMUtils.getElement('edition'),
                        form: DOMUtils.getElement('bookForm'),
                        submitBtn: DOMUtils.getElement('submitBtn')
                    };

                    FormManager.elements.spinner = FormManager.elements.submitBtn?.querySelector('.spinner-border');
                },

                setupEventListeners: () => {
                    const { titleInput, editorInput, priceInput, form } = FormManager.elements;

                    if (titleInput) {
                        titleInput.addEventListener('input', () =>
                            FormManager.handleTitleInput()
                        );
                    }

                    if (editorInput) {
                        editorInput.addEventListener('input', () =>
                            FormManager.handleEditorInput()
                        );
                    }

                    if (priceInput) {
                        priceInput.addEventListener('input', () =>
                            FormManager.handlePriceInput()
                        );
                    }

                    if (form) {
                        form.addEventListener('submit', (e) => FormManager.handleFormSubmit(e));
                    }
                },

                handleTitleInput: () => {
                    const { titleInput, titleCount } = FormManager.elements;
                    DOMUtils.updateCharacterCount(titleInput, titleCount);
                },

                handleEditorInput: () => {
                    const { editorInput, editorCount } = FormManager.elements;
                    DOMUtils.updateCharacterCount(editorInput, editorCount);
                },

                handlePriceInput: () => {
                    const { priceInput } = FormManager.elements;
                    if (priceInput) {
                        priceInput.value = NumberUtils.formatCurrency(priceInput.value);
                    }
                },

                collectFormData: () => {
                    const {
                        titleInput,
                        editorInput,
                        publicationYearInput,
                        editionInput,
                        priceInput
                    } = FormManager.elements;

                    return {
                        title: titleInput?.value.trim() || '',
                        editor: editorInput?.value.trim() || '',
                        publication_year: publicationYearInput?.value.trim() || '',
                        edition: NumberUtils.safeParseInt(editionInput?.value) || 0,
                        price: NumberUtils.normalizeToCents(priceInput?.value),
                        authors: DOMUtils.getSelectedCheckboxValues('#authors input[type=checkbox]:checked'),
                        subjects: DOMUtils.getSelectedCheckboxValues('#subjects input[type=checkbox]:checked')
                    };
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

                submitForm: async (formData) => {
                    FormManager.setLoadingState(true);

                    try {
                        const response = await ApiService.createBook(formData);
                        const data = await response.json();

                        if (response.ok) {
                            window.location.href = '{{ route("books.index-view") }}';
                        } else {
                            const err = new Error(data.message || 'Error creating book');
                            err.isApiError = true;
                            throw err;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (error && error.isApiError) {
                            _showErrorModal('Error creating book: ' + error.message);
                        } else {
                            alert('Error creating book: ' + error.message);
                        }
                        FormManager.setLoadingState(false);
                    }
                },

                setLoadingState: (isLoading) => {
                    const { submitBtn, spinner } = FormManager.elements;

                    if (submitBtn) {
                        submitBtn.disabled = isLoading;
                    }

                    if (spinner) {
                        spinner.classList.toggle('d-none', !isLoading);
                    }
                }
            };

            function initForm() {
                FormManager.initializeElements();
                FormManager.setupEventListeners();
                ApiService.fetchOptions()
                    .catch(error => {
                        console.error('Failed to fetch options:', error);
                        if (error && error.isApiError) {
                            _showErrorModal(error.message || 'Failed to fetch options');
                        }
                    });
            }
        </script>
    @endpush
@endsection

