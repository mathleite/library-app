<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Console\Commands;

use App\Application\UseCases\Report\RefreshBookReportView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshBookReportViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:refresh-book-view
                            {--force : Force refresh without confirmation}
                            {--schedule : Run in schedule mode (no output)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the materialized book report view';

    public function __construct(private RefreshBookReportView $useCase)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            if ($this->option('schedule')) {
                $this->refreshSilently();
                return self::SUCCESS;
            }

            if ($this->option('force') || $this->confirm('Are you sure you want to refresh the book report view?')) {
                $this->refreshWithOutput();
            } else {
                $this->info('Refresh cancelled.');
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Failed to refresh book report view: {$e->getMessage()}");
            Log::error('Book report view refresh failed', ['exception' => $e]);
            return self::FAILURE;
        }
    }

    /**
     * @return void
     */
    private function refreshSilently(): void
    {
        $start = microtime(true);
        $this->useCase->execute();
        $duration = round(microtime(true) - $start, 2);

        Log::info("Book report view refreshed successfully in {$duration}s");
    }

    /**
     * @return void
     */
    private function refreshWithOutput(): void
    {
        $this->info('Refreshing book report view...');

        $start = microtime(true);
        $this->useCase->execute();
        $duration = round(microtime(true) - $start, 2);

        $this->info("Book report view refreshed successfully in {$duration}s");
    }
}
