<?php

declare(strict_types=1);

use App\Application\UseCases\Report\RefreshBookReportView;
use App\Domain\Contracts\Persistence\BookReportRepository;
use Mockery as m;

it('should call refreshView on repository', function () {
    $repository = m::mock(BookReportRepository::class);
    $repository->shouldReceive('refreshView')->once();

    $useCase = new RefreshBookReportView($repository);

    $useCase->execute();

    expect(true)->toBeTrue();
});
