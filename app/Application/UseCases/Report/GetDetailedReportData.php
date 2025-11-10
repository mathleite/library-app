<?php

declare(strict_types=1);

namespace App\Application\UseCases\Report;

use App\Domain\Contracts\Persistence\BookReportRepository;

readonly class GetDetailedReportData
{
    public function __construct(
        private BookReportRepository $repository
    )
    {
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return array
     */
    public function execute(array $filters = [], int $perPage = 10): array
    {
        if (!$this->repository->viewExists()) {
            $this->repository->createOrReplaceView();
        }

        $reportData = $this->repository->getReportData($filters, $perPage);
        return array_merge($this->repository->getReportStats($filters), [
            'reportData' => $reportData,
            'autores' => $this->repository->getAuthorsForFilter(),
            'assuntos' => $this->repository->getSubjectsForFilter(),
            'filters' => $filters,
        ]);
    }
}
