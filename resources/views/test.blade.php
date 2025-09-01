<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold text-blue-600">Test Halaman</h1>
        <p class="text-gray-700 mt-4">Ini adalah test untuk melihat apakah CSS dan JS dimuat dengan benar.</p>

        <div class="mt-6 p-4 bg-white rounded-lg shadow">
            <p>Jika Anda melihat styling yang benar (background putih, rounded corners, shadow), maka Tailwind CSS bekerja.</p>
        </div>

        <button onclick="alert('JavaScript bekerja!')" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Test JavaScript
        </button>
    </div>
</body>
</html>
