@php use App\Domain\ValueObjects\Money; @endphp
@extends('components.header')

@section('title', 'Books report - Library application')

@section('content')
    <div class="container-fluid">
        <!-- Cabeçalho do Relatório -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Books Report</h1>
                <p class="text-muted mb-0">Complete book analysis by author and subject.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="exportToExcel()">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form id="filterForm" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Autor, livro ou assunto..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="autor_id" class="form-label">Author</label>
                        <select class="form-select" id="autor_id" name="autor_id">
                            <option value="">All authors</option>
                            @foreach($autores as $autor)
                                <option value="{{ $autor->codigo_autor }}"
                                    {{ request('autor_id') == $autor->codigo_autor ? 'selected' : '' }}>
                                    {{ $autor->nome_autor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="assunto_id" class="form-label">Subject</label>
                        <select class="form-select" id="assunto_id" name="assunto_id">
                            <option value="">All subjects</option>
                            @foreach($assuntos as $assunto)
                                <option value="{{ $assunto->codigo_assunto }}"
                                    {{ request('assunto_id') == $assunto->codigo_assunto ? 'selected' : '' }}>
                                    {{ $assunto->descricao_assunto }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estatísticas Resumidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title h2">{{ $totalLivros }}</h4>
                                <p class="card-text mb-0">Book's Total</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-book h1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title h2">{{ $totalAutores }}</h4>
                                <p class="card-text mb-0">Authors</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person h1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title h2">{{ $totalAssuntos }}</h4>
                                <p class="card-text mb-0">Subjects</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-tags h1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title h2">{{ (new Money($valorTotal))->withCurrency() }}</h4>
                                <p class="card-text mb-0">Total Amount</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-dollar h1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Relatório -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Report Details</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="reportTable">
                        <thead class="table-light">
                        <tr>
                            <th width="15%">Author</th>
                            <th width="15%">Subject</th>
                            <th width="20%">Book</th>
                            <th width="10%">Editor</th>
                            <th width="8%">Edition</th>
                            <th width="8%">Year</th>
                            <th width="10%">Price</th>
                            <th width="14%">Statistics</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reportData as $item)
                            <tr data-item='@json($item)'>
                                <td>
                                    <div class="fw-semibold">{{ $item->nome_autor }}</div>
                                    <small class="text-muted">ID: {{ $item->codigo_autor }}</small>

                                    @if($item->total_autores_livro > 1)
                                        <div class="mt-1">
                                            <small class="text-info">
                                                <i class="bi bi-people"></i>
                                                Co-autores: {{ $item->todos_autores }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->assuntos_agregados)
                                        <span class="badge bg-info text-dark">{{ $item->assuntos_agregados }}</span>
                                        @if($item->total_assuntos_livro > 1)
                                            <div class="mt-1">
                                                <small class="text-secondary">
                                                    {{ $item->total_assuntos_livro }} assuntos
                                                </small>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">Sem assunto</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-primary" onclick="showDetails({{json_encode($item)}})">{{ $item->titulo_livro }}</div>
                                    <small class="text-muted">ID: {{ $item->codigo_livro }}</small>

                                    @if($item->total_autores_livro > 1)
                                        <div class="mt-1">
                        <span class="badge bg-success">
                            <i class="bi bi-people"></i>
                            {{ $item->total_autores_livro }} autores
                        </span>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->editora }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->edicao }}ª</span>
                                </td>
                                <td>{{ $item->ano_publicacao }}</td>
                                <td>
                                    <span
                                        class="fw-bold text-success">{{ (new Money($item->preco))->withCurrency() }}</span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="d-flex justify-content-between">
                                            <span>Livros do autor:</span>
                                            <span class="badge bg-primary">{{ $item->total_livros_autor }}</span>
                                        </div>
                                        @if($item->total_autores_livro > 1)
                                            <div class="d-flex justify-content-between mt-1">
                                                <span>Autores do livro:</span>
                                                <span class="badge bg-success">{{ $item->total_autores_livro }}</span>
                                            </div>
                                        @endif
                                        @if($item->total_assuntos_livro > 0)
                                            <div class="d-flex justify-content-between">
                                                <span>Assuntos do livro:</span>
                                                <span
                                                    class="badge bg-secondary">{{ $item->total_assuntos_livro }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        Nenhum livro encontrado com os filtros aplicados.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginação -->
            @if($reportData?->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        {{ $reportData->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="modal fade" id="detailsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Book Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            color: #6c757d;
        }

        .badge {
            font-size: 0.75em;
        }

        .card {
            border-radius: 0.5rem;
        }

        @media print {
            .btn, .card-header, .modal {
                display: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }

            .table th {
                background-color: #f8f9fa !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Exportar para Excel
        function exportToExcel() {
            // Redirecionar para rota de exportação com os mesmos filtros
            const filters = {
                autor_id: document.getElementById('autor_id').value,
                assunto_id: document.getElementById('assunto_id').value,
                search: document.getElementById('search').value
            };

            const params = new URLSearchParams();
            Object.keys(filters).forEach(key => {
                if (filters[key]) {
                    params.append(key, filters[key]);
                }
            });

            {{--            window.location.href = '{{ route("reports.export") }}?' + params.toString();--}}
                window.location.href = '#?' + params.toString();
        }

        function showDetails(item) {
            const modalBody = document.getElementById('modalBody');

            let autoresHTML = '';
            if (item.todos_autores) {
                const autores = item.todos_autores.split('; ');
                autoresHTML = `
                <h6>Autores do Livro (${item.total_autores_livro})</h6>
                <div class="mb-3">
                    ${autores.map(autor =>
                    `<span class="badge bg-primary me-1 mb-1">${autor}</span>`
                ).join('')}
                </div>
            `;
            }

            let assuntosHTML = '';
            if (item.assuntos_agregados) {
                const assuntos = item.assuntos_agregados.split('; ');
                assuntosHTML = `
                <h6>Assuntos do Livro (${item.total_assuntos_livro})</h6>
                <div class="mb-3">
                    ${assuntos.map(assunto =>
                    `<span class="badge bg-info text-dark me-1 mb-1">${assunto}</span>`
                ).join('')}
                </div>
            `;
            }

            modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informações do Livro</h6>
                    <p><strong>Título:</strong> ${item.titulo_livro}</p>
                    <p><strong>Editora:</strong> ${item.editora}</p>
                    <p><strong>Edição:</strong> ${item.edicao}ª</p>
                    <p><strong>Ano:</strong> ${item.ano_publicacao}</p>
                    <p><strong>Preço:</strong> R$ ${parseFloat(item.preco).toFixed(2).replace('.', ',')}</p>
                </div>
                <div class="col-md-6">
                    <h6>Estatísticas</h6>
                    <p><strong>Total de livros do autor:</strong> ${item.total_livros_autor}</p>
                    <p><strong>Total de autores do livro:</strong> ${item.total_autores_livro}</p>
                    <p><strong>Total de assuntos do livro:</strong> ${item.total_assuntos_livro}</p>
                </div>
            </div>
            ${autoresHTML}
            ${assuntosHTML}
        `;

            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('#reportTable tbody tr[data-item]');
            rows.forEach(row => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function () {
                    const itemData = this.getAttribute('data-item');
                    if (itemData) {
                        const item = JSON.parse(itemData);
                        showDetails(item);
                    }
                });
            });
        });
    </script>
@endpush
