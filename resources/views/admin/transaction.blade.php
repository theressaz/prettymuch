<x-app-layout>
    <x-slot name="slot">
        {{-- item table --}}
        <div class="max-w-7xl mx-auto my-4 overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            User email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Payment Method
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $tr)
                        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $tr->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $tr->user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $tr->payment_method }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $tr->total_price }}
                            </td>
                            <td>
                                {{ $tr->status }}
                            </td>
                            <td>

                                <!-- Modal toggle -->
                                <a href="{{route('admin.transaction.accept', $tr)}}" class="font-medium text-green-600 dark:text-green-500 hover:underline mr-4">accept</a>
                                <a href="{{route('admin.transaction.reject', $tr)}}" class="font-medium text-red-600 dark:text-red-500 hover:underline">reject</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-slot>
</x-app-layout>
