<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Provide Your Feedback</h1>

        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success d-none" role="alert">
            Thank you for your feedback!
        </div>

        <form id="feedbackForm" action="submit-feedback.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
            </div>
            <div class="mb-3">
                <label for="feedback" class="form-label">Your Feedback</label>
                <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Feedback</button>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // Add an event listener to the form
        document.getElementById('feedbackForm').addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Display the success alert
            const successAlert = document.getElementById('successAlert');
            successAlert.classList.remove('d-none');

            // Optionally, reset the form
            this.reset();

            // Uncomment the following line to actually submit the form after showing the alert
            // this.submit();
        });
    </script>
</body>
</html>
