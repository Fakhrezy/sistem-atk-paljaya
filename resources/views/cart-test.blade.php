<!DOCTYPE html>
<html>
<head>
    <title>Cart Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Cart Test Page</h1>

    @auth
        <p>User: {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>

        @if(auth()->user()->role === 'user')
            <p style="color: green;">✓ Logged in as user - Cart should work</p>

            <!-- Test Cart Add Button -->
            <button onclick="testCartAdd()">Test Add to Cart</button>
            <button onclick="testCartCount()">Test Cart Count</button>

            <div id="results" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>
        @else
            <p style="color: red;">✗ Logged in as admin - Cart won't work</p>
        @endif
    @else
        <p style="color: red;">✗ Not logged in - Cart won't work</p>
    @endauth

    <script>
        function testCartAdd() {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = 'Testing cart add...';

            const formData = new FormData();
            formData.append('id_barang', 'BRG-ATK-6FLF2'); // Use sample barang ID
            formData.append('quantity', 1);
            formData.append('bidang', 'umum');
            formData.append('keterangan', 'test from cart test page');

            fetch('/user/cart/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                resultsDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = `<div style="color: red;">Error: ${error.message}</div>`;
            });
        }

        function testCartCount() {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = 'Testing cart count...';

            fetch('/user/cart/count')
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = `<pre>Cart Count: ${JSON.stringify(data, null, 2)}</pre>`;
                })
                .catch(error => {
                    resultsDiv.innerHTML = `<div style="color: red;">Error: ${error.message}</div>`;
                });
        }
    </script>
</body>
</html>
