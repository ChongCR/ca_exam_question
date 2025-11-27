<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Services\ApiAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InvestorController extends Controller
{
    protected $authService;

    public function __construct(ApiAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        $investors = Investor::orderBy('created_at', 'asc')->get();
        
        return view('investor.index', compact('investors'));
    }

    /**
     * Show the form for creating a new investor
     */
    public function create()
    {
        return view('investor.create');
    }

    /**
     * Store a newly created investor in database and push to API
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        try {
            // Get authentication token
            $token = $this->authService->getToken();

            if (!$token) {
                return back()->with('error', 'Failed to authenticate with API');
            }

            // Push to API
            $baseUrl = config('services.api.base_url');
            $response = Http::withToken($token)
                ->post("{$baseUrl}/api/investor", $validated);

            if ($response->successful()) {
                $apiData = $response->json()['data'];

                // Save to local database
                Investor::create([
                    'id' => $apiData['id'],
                    'name' => $apiData['name'],
                    'email' => $apiData['email'],
                    'contact_number' => $apiData['contact_number'],
                ]);

                return redirect()->route('investor.index')
                    ->with('success', 'Investor created successfully');
            } else {
                Log::error('API investor creation failed', ['response' => $response->body()]);
                return back()->with('error', 'Failed to create investor on API')
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Investor creation exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing an investor
     */
    public function edit(Investor $investor)
    {
        return view('investor.edit', compact('investor'));
    }

    /**
     * Update the investor in database and push to API
     */
    public function update(Request $request, Investor $investor)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        try {
            // Get authentication token
            $token = $this->authService->getToken();

            if (!$token) {
                return back()->with('error', 'Failed to authenticate with API');
            }

            // Push to API
            $baseUrl = config('services.api.base_url');
            $response = Http::withToken($token)
                ->put("{$baseUrl}/api/investor/{$investor->id}", $validated);

            if ($response->successful()) {
                $apiData = $response->json()['data'];

                // Update local database
                $investor->update([
                    'name' => $apiData['name'],
                    'email' => $apiData['email'],
                    'contact_number' => $apiData['contact_number'],
                ]);

                return redirect()->route('investor.index')
                    ->with('success', 'Investor updated successfully');
            } else {
                Log::error('API investor update failed', ['response' => $response->body()]);
                return back()->with('error', 'Failed to update investor on API')
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Investor update exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }
}