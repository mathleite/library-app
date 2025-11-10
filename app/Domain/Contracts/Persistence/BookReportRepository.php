<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookReportRepository
{
    /**
     * @return void
     */
    public function createOrReplaceView(): void;

    /**
     * @return void
     */
    public function refreshView(): void;

    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getReportData(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * @return bool
     */
    public function viewExists(): bool;

    /**
     * @return string
     */
    public function getViewName(): string;

    /**
     * @param array $filters
     * @return array
     */
    public function getReportStats(array $filters = []): array;

    /**
     * @return array
     */
    public function getAuthorsForFilter(): array;

    /**
     * @return array
     */
    public function getSubjectsForFilter(): array;
}
