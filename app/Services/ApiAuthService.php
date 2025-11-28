<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiAuthService
{
    /**
     * The API base URL
     */
    private string $baseUrl;

    /**
     * The candidate email for authentication
     */
    private string $email;

    /**
     * Cache key for storing the token
     */
    private const CACHE_KEY = 'api_auth_token';

    /**
     * Cache duration in seconds (24 hours)
     */
    private const CACHE_DURATION = 86400; // 24 hours = 60 * 60 * 24

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUrl = config('services.api.base_url', 'https://exam.cardinalalpha.com');
        $this->email = config('services.api.email', 'chongcr128@gmail.com');
    }

    /**
     * Get the authentication token
     * Returns cached token if available, otherwise generates a new one
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        // Check if token exists in cache
        $token = Cache::get(self::CACHE_KEY);

        if ($token) {
            return $token;
        }

        // Token not in cache or expired, generate a new one
        Log::info('Generating new API token');
        return $this->generateAndCacheToken();
    }

    /**
     * Generate a new token from the API and store it in cache
     *
     * @return string|null
     */
    private function generateAndCacheToken(): ?string
    {
        try {

            $response = Http::post("{$this->baseUrl}/api/generate-token", [
                'email' => $this->email,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if token was generated successfully
                if (isset($data['token']) && $data['message'] === 'Token Generated') {
                    $token = $data['token'];

                    // Store token in cache for 24 hours
                    Cache::put(self::CACHE_KEY, $token, self::CACHE_DURATION);

                    Log::info('New API token generated and cached successfully');
                    return $token;
                }

                // Handle invalid email response
                if (isset($data['message']) && $data['message'] === 'Invalid Email') {
                    Log::error('Invalid email provided for token generation', [
                        'email' => $this->email,
                    ]);
                    return null;
                }
            }

            Log::error('Failed to generate API token', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception occurred while generating API token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Clear the cached token
     *
     * @return void
     */
    public function clearToken(): void
    {
        Cache::forget(self::CACHE_KEY);
        Log::info('API token cache cleared');
    }

    /**
     * Check if a valid token exists in cache
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return Cache::has(self::CACHE_KEY);
    }

    /**
     * Force refresh the token
     *
     * @return string|null
     */
    public function refreshToken(): ?string
    {
        $this->clearToken();
        return $this->generateAndCacheToken();
    }
}