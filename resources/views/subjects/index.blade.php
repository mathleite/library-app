@extends('components.header')

@section('title', 'Subjects - Library application')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Subjects</h1>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create subject
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="subjects-table" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="subjects-table-body">
                        <!-- Table data will be populated here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', getAllSubjects);
            async function getAllSubjects() {
                try {
                    const response = await fetch('{{ route("subjects.index") }}', {
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
                        throw new Error(data.message || 'Error to list subjects');
                    }

                } catch (error) {
                    alert(`Error to list subjects: ${error}`);
                }
            }

            function fillTableData({ items }) {
                const tableBody = document.getElementById('subjects-table-body');
                tableBody.innerHTML = "";
                if (!items.length) {
                    return;
                }
                items.forEach( item => {
                    const row = document.createElement("tr")
                    const cellID = document.createElement("td");
                    cellID.textContent = item['id'];
                    const cellDesc = document.createElement("td");
                    cellDesc.textContent = item['description'];
                    row.appendChild(cellID);
                    row.appendChild(cellDesc);
                    const btnCell = document.createElement("td");
                    btnCell.innerHTML = `
                        <button class="btn btn-sm btn-outline-primary" onclick="editSubject(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSubject(${item.id})">Delete</button>
                    `;
                    row.appendChild(btnCell);
                    tableBody.appendChild(row);
                });
            }

            async function deleteSubject(id) {
                if (!confirm("Are you sure?")) return;
                try {
                    const response = await fetch(`{{ url('api/v1/subjects') }}/${id}`, {
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
                        throw new Error(data.message || 'Error to delete subject');
                    }
                } catch (error) {
                    alert(`Error to delete subject: ${error}`);
                }
            }

            function editSubject(id) {
                const route = "{{ route('subjects.edit-view', ['id' => ':id']) }}";
                window.location.href = route.replace(':id', id);
            }
        </script>
    @endpush
@endsection

