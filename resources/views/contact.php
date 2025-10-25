<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Kontak' ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .container { background: #f5f5f5; padding: 30px; border-radius: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        
        <form method="POST" action="/contact" id="contactForm">
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Pesan:</label>
                <textarea name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Kirim Pesan</button>
        </form>
        
        <div style="margin-top: 20px;">
            <a href="/">← Kembali ke Home</a>
        </div>
    </div>

    <script>
        // Simple form handling
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const response = await fetch('/contact', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            alert(result.message);
        });
    </script>
</body>
</html>