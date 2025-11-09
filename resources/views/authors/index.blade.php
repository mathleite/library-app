@extends('components.header')

@section('title', 'Authors - Library application')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Authors</h1>
        <a href="{{ route('authors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create author
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="authors-table" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="authors-table-body">
                        <!-- Table data will be populated here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', getAllAuthors);
            async function getAllAuthors() {
                try {
                    const response = await fetch('{{ route("authors.api.index") }}', {
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
                        throw new Error(data.message || 'Error to list authors');
                    }

                } catch (error) {
                    alert(`Error to list authors: ${error}`);
                }
            }

            function fillTableData({ items }) {
                const tableBody = document.getElementById('authors-table-body');
                tableBody.innerHTML = "";
                if (!items.length) {
                    return;
                }
                items.forEach( item => {
                    const row = document.createElement("tr")
                    const cellID = document.createElement("td");
                    cellID.textContent = item['id'];
                    const cellName = document.createElement("td");
                    cellName.textContent = item['name'];
                    row.appendChild(cellID);
                    row.appendChild(cellName);
                    const btnCell = document.createElement("td");
                    btnCell.innerHTML = `
                        <button class="btn btn-sm btn-outline-primary" onclick="editAuthor(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAuthor(${item.id})">Delete</button>
                    `;
                    row.appendChild(btnCell);
                    tableBody.appendChild(row);
                });
            }

            async function deleteAuthor(id) {
                if (!confirm("Are you sure?")) return;
                try {
                    const response = await fetch(`{{ url('api/v1/authors') }}/${id}`, {
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
                        throw new Error(data.message || 'Error to delete author');
                    }
                } catch (error) {
                    alert(`Error to delete author: ${error}`);
                }
            }

            function editAuthor(id) {
                const route = "{{ route('authors.edit-view', ['id' => ':id']) }}";
                window.location.href = route.replace(':id', id);
            }
        </script>
    @endpush
@endsection
