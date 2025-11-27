<?php

namespace App\Console\Commands;

use App\Models\Fund;
use App\Models\Investor;
use App\Models\Investment;
use App\Services\ApiAuthService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data (Funds, Investors, Investments) from API';

    /**
     * Execute the console command.
     */
    public function handle(ApiAuthService $authService): int
    {
        $this->info('Starting data synchronization...');
        $this->newLine();

        // Step 1: Authentication
        $this->info('Step 1: Authenticating...');
        $token = $authService->getToken();

        if (!$token) {
            $this->error('Failed to authenticate. Please check your API credentials.');
            return 1;
        }

        $this->info('✓ Authentication successful');
        $this->newLine();

        $baseUrl = config('services.api.base_url');

        // Step 2: Sync Funds
        $this->info('Step 2: Syncing Funds...');
        if (!$this->syncFunds($token, $baseUrl)) {
            return 1;
        }

        // Step 3: Sync Investors
        $this->info('Step 3: Syncing Investors...');
        if (!$this->syncInvestors($token, $baseUrl)) {
            return 1;
        }

        // Step 4: Sync Investments
        $this->info('Step 4: Syncing Investments...');
        if (!$this->syncInvestments($token, $baseUrl)) {
            return 1;
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('✓ Data synchronization completed successfully!');
        $this->info('========================================');

        return 0;
    }

    /**
     * Sync funds from API
     */
    private function syncFunds(string $token, string $baseUrl): bool
    {
        try {
            $response = Http::withToken($token)
                ->get("{$baseUrl}/api/fund");

            if (!$response->successful()) {
                $this->error('Failed to fetch funds from API');
                Log::error('Fund sync failed', ['response' => $response->body()]);
                return false;
            }

            $funds = $response->json()['data'] ?? [];

            foreach ($funds as $fundData) {
                Fund::updateOrCreate(
                    ['id' => $fundData['id']],
                    [
                        'name' => $fundData['name'],
                        'created_at' => $fundData['created_at'],
                        'updated_at' => $fundData['updated_at'],
                    ]
                );
            }

            $this->info("✓ Synced " . count($funds) . " funds");
            $this->newLine();

            return true;
        } catch (\Exception $e) {
            $this->error('Error syncing funds: ' . $e->getMessage());
            Log::error('Fund sync exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Sync investors from API
     */
    private function syncInvestors(string $token, string $baseUrl): bool
    {
        try {
            $response = Http::withToken($token)
                ->get("{$baseUrl}/api/investor");

            if (!$response->successful()) {
                $this->error('Failed to fetch investors from API');
                Log::error('Investor sync failed', ['response' => $response->body()]);
                return false;
            }

            $investors = $response->json()['data'] ?? [];

            foreach ($investors as $investorData) {
                Investor::updateOrCreate(
                    ['id' => $investorData['id']],
                    [
                        'name' => $investorData['name'],
                        'email' => $investorData['email'],
                        'contact_number' => $investorData['contact_number'],
                        'created_at' => $investorData['created_at'] ?? now(),
                        'updated_at' => $investorData['updated_at'] ?? now(),
                    ]
                );
            }

            $this->info("✓ Synced " . count($investors) . " investors");
            $this->newLine();

            return true;
        } catch (\Exception $e) {
            $this->error('Error syncing investors: ' . $e->getMessage());
            Log::error('Investor sync exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Sync investments from API
     */
    private function syncInvestments(string $token, string $baseUrl): bool
    {
        try {
            $response = Http::withToken($token)
                ->get("{$baseUrl}/api/investments");

            if (!$response->successful()) {
                $this->error('Failed to fetch investments from API');
                Log::error('Investment sync failed', ['response' => $response->body()]);
                return false;
            }

            $investments = $response->json()['data'] ?? [];

            foreach ($investments as $investmentData) {
                Investment::updateOrCreate(
                    ['id' => $investmentData['id']],
                    [
                        'uid' => $investmentData['uid'],
                        'start_date' => $investmentData['start_date'],
                        'capital_amount' => $investmentData['capital_amount'],
                        'status' => $investmentData['status'],
                        'fund_id' => $investmentData['fund']['id'],
                        'investor_id' => $investmentData['investor']['id'],
                        'created_at' => $investmentData['created_at'],
                        'updated_at' => $investmentData['updated_at'],
                    ]
                );
            }

            $this->info("✓ Synced " . count($investments) . " investments");
            $this->newLine();

            return true;
        } catch (\Exception $e) {
            $this->error('Error syncing investments: ' . $e->getMessage());
            Log::error('Investment sync exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}