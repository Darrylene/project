<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brew & Blend Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
  <style>
    html, body {
      height: 100%;
    }

    body {
      background-color: #f8f4ec;
    }

    form label {
      color: #f8f4ec;
    }

    form input {
      border: 2px solid #EAD8C0;
      border-radius: 15px;
    }

    form input:focus {
      outline: none;
      border-color: #f8f4ec;
      box-shadow: 0 0 10px #7c6246;
    }

    .bg-custom {
      background-color: #8C785E;
    }

    .text-custom {
      color: #f8f4ec;
    }

    .logo {
      width: 50px;
      height: 50px;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Full viewport height */
      width: 100vw;  /* Full viewport width */
    }

    .row {
      border-radius: 30px;
      cursor: pointer;
      width: 100%;
      max-width: 1800px;
      height: 90%;
      max-height: 1800px;
      margin: 0 auto;
    }

    .login-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border-radius: 50px;
      padding: 30px;
    }

    .logo-title-wrapper {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .login-title {
      margin-top: 0;
      color: white;
    }

    .forgot-password-link {
      font-size: 1.1rem;
      color: #f8f4ec;
      margin-top: 8px;
    }

    .forgot-password-link:hover {
      text-decoration: underline;
    }

    .login-form-container {
      margin-top: 50px;
    }
    .custom-btn {
      background-color: #EAD8C0;
      color: #835f29f8;
      border-radius: 15px;
    }
    .custom-btn:hover {
      background-color: #d2c8a2;
    }

    /* Add rotating coffee cup animation */
    @keyframes rotate {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    /* Add revolving coffee cup animation */
    @keyframes revolve {
      0% {
        transform: rotateY(0deg);
      }
      100% {
        transform: rotateY(360deg);
      }
    }

    #loadingOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7); /* Adjusted transparency */
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .coffee-cup {
      width: 100px;
      height: 100px;
      animation: revolve 2s linear infinite;
      transform-style: preserve-3d;
    }

    #lottieCoffee {
      width: 500px; /* Increased width */
      height: 500px; /* Increased height */
    }
  </style>
</head>
<body>
  <div id="loadingOverlay">
    <div id="lottieCoffee"></div>
  </div>
  <div class="container">
    <div class="row shadow-lg rounded" style="background-color: #8C785E;">
      <!-- Left Section: Registration Form -->
      <div class="col-md-6 p-4 login-form-container">
        <div class="login-container">
          <div class="logo-title-wrapper">
            <img src="b&blogo.png" alt="Brew & Blend Logo" class="logo">
            <h2 class="login-title mb-0">Verification Code</h2>
          </div>
        </div>
        <form id="verifyCodeForm" method="POST" action="verifycode.php">
          <p class="text-center mb-4 text-white">
            You’re almost there!<br>
            We've sent a six-digit code to your email.<br>
            If it's not there, you may check your spam/junk folder.
          </p>
          <!-- Hidden Email Input -->
          <?php
          session_start();
          if (isset($_SESSION['email'])): ?>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
          <?php endif; ?>
          <!-- Code Input -->
          <div class="mb-3">
            <label for="code" class="form-label text-custom">Enter Code</label>
            <input type="text" class="form-control" id="code" name="otp" placeholder="Enter your code" required>
            <div id="codeError" class="error"></div>
          </div>
          <!-- Resend Code Button -->
          <button type="button" class="btn custom-btn w-100" id="resendCodeButton">Resend Code</button>
          <!-- Confirm Button -->
          <button type="submit" class="btn custom-btn w-100 mt-3" id="confirmButton">Confirm</button>
        </form>

        <div class="text-center mt-3">
          <p class="text-custom">Return back to <a href="log in.html" class="text-custom">Sign In</a></p>
        </div>
      </div>

      <!-- Right Section: Image -->
      <div class="col-md-6 image-container">
        <img src="verify.png" class="d-block mx-auto" alt="Blueberry Belgian Waffles" style="width: 670px; height: 905px;">
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Load Lottie animation
    document.addEventListener("DOMContentLoaded", function () {
      const loadingOverlay = document.getElementById("loadingOverlay");
      loadingOverlay.style.display = "none";

      lottie.loadAnimation({
        container: document.getElementById('lottieCoffee'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'coffee-cup.json' // Replace with the JSON file path from LottieFiles
      });
    });

    document.getElementById("resendCodeButton").addEventListener("click", function () {
      const emailInput = document.querySelector('input[name="email"]').value;

      const loadingOverlay = document.getElementById("loadingOverlay");
      loadingOverlay.style.display = "flex"; // Show loading overlay
      document.body.style.pointerEvents = "none"; // Disable interactions

      fetch('resendcode.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(emailInput)}`,
      })
      .then(response => response.text())
      .then(data => {
        loadingOverlay.style.display = "none"; // Hide loading overlay
        document.body.style.pointerEvents = "auto"; // Enable interactions

        // Show fancy confirmation modal with updated color palette
        const modalHtml = `
          <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content" style="background-color: #8C785E; color: #f8f4ec;">
                <div class="modal-header" style="border-bottom: 1px solid #EAD8C0;">
                  <h5 class="modal-title" id="successModalLabel">Success!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #EAD8C0;" onclick="window.location.href='verification code.php';"></button>
                </div>
                <div class="modal-body text-center">
                  <p>✅ Another One-Time Pin has been sent to your email.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #EAD8C0;">
                  <button type="button" class="btn custom-btn" data-bs-dismiss="modal" onclick="window.location.href='verification code.php';">OK</button>
                </div>
              </div>
            </div>
          </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      })
      .catch(error => {
        loadingOverlay.style.display = "none"; // Hide loading overlay
        document.body.style.pointerEvents = "auto"; // Enable interactions
        console.error('Error:', error);
        alert("❌ Failed to resend the code. Please try again.");
      });
    });

    document.getElementById("verifyCodeForm").addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent default form submission
      const codeInput = document.getElementById("code").value.trim();
      const codeError = document.getElementById("codeError");

      // Clear previous error messages
      codeError.textContent = "";

      // Validate the code
      if (codeInput === "") {
        codeError.textContent = "Please enter the verification code.";
        return;
      } else if (codeInput.length !== 6 || isNaN(codeInput)) {
        codeError.textContent = "Invalid code! Please enter a 6-digit numeric code.";
        return;
      }

      // Submit the form to verifycode.php without handling the modal here
      this.submit();
    });
  </script>
</body>
</html>
