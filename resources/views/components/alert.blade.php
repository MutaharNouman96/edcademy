<div>
    @php
        $colors = [
            'success' => 'alert alert-success alert-dismissible fade show',
            'error' => 'alert alert-danger alert-dismissible fade show',
            'warning' => 'alert alert-warning alert-dismissible fade show',
            'info' => 'alert alert-info alert-dismissible fade show',
            'message' => 'alert alert-info alert-dismissible fade show',
        ];
    @endphp

    <div class="{{ $colors[$type] }}" role="alert" id="alertBox">
        {{ $message }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<script>
    setTimeout(() => {
        let alertEl = document.getElementById('alertBox');
        if (alertEl) {
            let alert = new bootstrap.Alert(alertEl);
            alert.close();
        }
    }, 5000);
</script>
