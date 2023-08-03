<x-app-layout>
    <div class="max-w-xl mx-auto mt-4">
        <div class="bg-white rounded p-4 px-6">
            <h5 class="font-semibold text-lg">New File</h5>

            <form action="{{ route('files') }}" class="grid gap-y-3 mt-4" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <button type="button" onclick="document.getElementById('file').click();" class="w-full rounded outline-dashed flex justify-center items-center gap-x-1 text-gray-600 font-semibold outline-2 py-12 outline-gray-300 hover:outline-gray-400">
                        <i class="fa-regular fa-square-plus"></i>
                        Upload a file
                    </button>
                    <input id="file" type="file" name="file" class="hidden">
                    @error('file')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input id="name" name="name" type="text" placeholder="Name" class="block w-full rounded border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="flex w-full justify-center rounded bg-indigo-500 px-3 py-2 font-semibold leading-6 text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create</button>
            </form>
        </div>
    </div>
</x-app-layout>