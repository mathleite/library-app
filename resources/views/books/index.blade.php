@extends('components.header')

@section('title', 'Books - Library application')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Books</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create book
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="books-table" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Editor</th>
                        <th>Year</th>
                        <th>Edition</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="books-table-body">
                        <!-- Table data will be populated here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', getAllBooks);
            async function getAllBooks() {
                try {
                    const response = await fetch('{{ route("books.index") }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    });
                    const data = await response.json();

                    if (response.ok) {
                        fillTableData(data);
                    } else {
                        throw new Error(data.message || 'Error to list books');
                    }

                } catch (error) {
                    alert(`Error to list books: ${error}`);
                }
            }

            function formatPrice({ amount, currency }) {
                if (amount === undefined || amount === null) return '';
                const intVal = parseInt(amount, 10);
                if (isNaN(intVal)) return '';
                return `${currency} ${(intVal / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }

            function fillTableData({ items }) {
                const tableBody = document.getElementById('books-table-body');
                tableBody.innerHTML = "";
                if (!items.length) {
                    return;
                }
                items.forEach( item => {
                    const row = document.createElement("tr")
                    const cellID = document.createElement("td");
                    cellID.textContent = item['id'];
                    const cellTitle = document.createElement("td");
                    cellTitle.textContent = item['title'];
                    const cellEditor = document.createElement("td");
                    cellEditor.textContent = item['editor'];
                    const cellYear = document.createElement("td");
                    cellYear.textContent = item['publicationYear'];
                    const cellEdition = document.createElement("td");
                    cellEdition.textContent = item['edition'];

                    const cellPrice = document.createElement("td");
                    cellPrice.textContent = formatPrice(item['price']);
                    row.appendChild(cellID);
                    row.appendChild(cellTitle);
                    row.appendChild(cellEditor);
                    row.appendChild(cellYear);
                    row.appendChild(cellEdition);
                    row.appendChild(cellPrice);
                    const btnCell = document.createElement("td");
                    btnCell.innerHTML = `
                        <button class="btn btn-sm btn-outline-primary" onclick="editBook(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBook(${item.id})">Delete</button>
                    `;
                    row.appendChild(btnCell);
                    tableBody.appendChild(row);
                });
            }

            async function deleteBook(id) {
                if (!confirm("Are you sure?")) return;
                try {
                    const response = await fetch(`{{ url('api/v1/books') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    });
                    const data = await response.json();

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Error to delete book');
                    }
                } catch (error) {
                    alert(`Error to delete book: ${error}`);
                }
            }

            function editBook(id) {
                const route = "{{ route('books.edit-view', ['id' => ':id']) }}";
                window.location.href = route.replace(':id', id);
            }
        </script>
    @endpush
@endsection

