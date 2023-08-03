<x-app-layout>
    <div class="max-w-7xl mx-auto mt-4">
        <div class="bg-white rounded p-4 px-6 flex justify-between items-center">
            <h5 class="font-semibold text-lg">Files</h5>
            <a href="{{ route('files.create') }}" class="inline-flex items-center rounded gap-x-1 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <i class="fa-solid fa-plus"></i>
                New File
            </a>
        </div>

        <div class="grid grid-cols-4 mt-4 gap-x-4">
            @foreach ($files as $file)
                <div class="bg-white shadow rounded grid text-sm text-gray-700 divide-y">
                    <div class="grid gap-y-1 p-6">
                        <p class="mb-2">
                            <span class="bg-indigo-100 text-indigo-500 px-3 py-1 rounded-full font-bold">
                                {{ $file->extension }}
                            </span>
                        </p>
                        <p class="font-bold">
                            {{ $file->name }}
                        </p>
                        <p class="text-slate-500 font-semibold">
                            {{ $file->size }}
                        </p>
                    </div>
                    <div class="grid grid-cols-2 divide-x">
                        <a href="" class="text-center py-2 text-slate-600 hover:text-indigo-600 font-bold"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form action="{{ route('files.destroy', $file) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button href="" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>