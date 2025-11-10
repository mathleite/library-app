<?php

declare(strict_types=1);

namespace App\Application\UseCases\Report;

use App\Domain\Contracts\Persistence\BookReportRepository;

readonly class RefreshBookReportView
{
    public function __construct(private BookReportRepository $repository)
    {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->repository->refreshView();
    }
}
