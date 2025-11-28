<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fund') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Fund Table Here") }}

                    <table class="min-w-full border-collapse border border-gray-300 mt-5">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">No</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Created At</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($funds as $fund)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $fund->id }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $fund->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $fund->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $fund->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                        No funds found
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