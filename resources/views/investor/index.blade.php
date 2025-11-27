<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Investor List</h3>
                        <a href="{{ route('investor.create') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-small py-2 px-4 rounded">
                             Create New Investor
                        </a>
                    </div>

                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Contact Number</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investors as $investor)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $investor->id }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investor->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investor->email }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $investor->contact_number }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="{{ route('investor.edit', $investor) }}" 
                                           class="bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium py-1 px-3 rounded">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                        No investors found
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