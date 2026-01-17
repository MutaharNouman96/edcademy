<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PDF Watermarker</title>
</head>

<body>
    <h1>PDF Watermarker</h1>
    <hr>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('watermark-pdf') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="document">Choose PDF File:</label><br>
            <input type="file" id="document" name="document" accept="application/pdf" required>
        </div>

        <br>

        <div>
            <button type="submit">Upload and Apply Watermark</button>
        </div>
    </form>

    <br>
    <hr>

    <h3>Instructions:</h3>
    <ul>
        <li>Select the original PDF document from your computer.</li>
        <li>Click "Upload and Apply Watermark".</li>
        <li>The watermarked version will be downloaded automatically.</li>
    </ul>

    <div id="preview-container" style="display:none;">
        <h3>Selected File Preview:</h3>
        <iframe id="pdf-preview" width="100%" height="500px"></iframe>
    </div>

    <script>
        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type === "application/pdf") {
                const fileURL = URL.createObjectURL(file);
                const preview = document.getElementById('pdf-preview');
                const container = document.getElementById('preview-container');

                preview.src = fileURL;
                container.style.display = 'block';
            }
        });
    </script>
</body>

</html>
