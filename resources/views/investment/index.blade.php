<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Investment Table Here") }}

                    <table class="min-w-full border-collapse border border-gray-300 mt-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">UID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Fund</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Investor</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Start Date</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Capital Amount</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investments as $investment)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $investment->id }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investment->uid }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investment->fund->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investment->investor->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $investment->start_date->format('Y-m-d') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        ${{ number_format($investment->capital_amount, 2) }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <span
                                            class="px-2 py-1 text-xs rounded {{ $investment->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($investment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                        No investments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>