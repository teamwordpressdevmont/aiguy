<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Tool Submission</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="{{ asset('js/tailwind.js') }}"></script>

</head>
<body class="bg-gray-900 text-white flex justify-center items-center min-h-screen">

    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-4">Submit AI Tool</h2>

        <form action="/ai-tool/store" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input type="text" name="slug" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="chatgpt-plugins" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="ChatGPT Plugins" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Category</label>
                {{-- <select name="category_id" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select> --}}
            </div>

            <div>
                <label class="block text-sm font-medium">Logo</label>
                <input type="file" name="logo" accept="image/*" class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium">Cover Image</label>
                <input type="file" name="cover" accept="image/*" class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition">Submit</button>
        </form>
    </div>

</body>
</html>