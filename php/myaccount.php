<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log in.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT FirstName, LastName, Email, Address, ContactNumber FROM user WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Check if user details were found
if (!$user) {
    error_log("User not found for user_id: $user_id");
    header("Location: log in.html");
    exit();
}

// Make user details available
$first_name = htmlspecialchars($user['FirstName']);
$last_name = htmlspecialchars($user['LastName']);
$email = htmlspecialchars($user['Email']);
$address = htmlspecialchars($user['Address']);
$contact_number = htmlspecialchars($user['ContactNumber']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo:wght@400;700&family=Bricolage+Grotesque:wght@400;700&family=Open+Sans:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #543310;
            color: #fff2e1;
            margin: 0;
            padding: 0;
        }

        .account-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #8C785E;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .account-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .account-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .account-header p {
            font-family: 'Open Sans', sans-serif;
            font-size: 1rem;
            color: #d2b48c;
        }

        .account-details {
            margin-bottom: 20px;
        }

        .account-details p {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .account-details span {
            font-weight: bold;
            color: #d2b48c;
        }

        .account-details input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #d2b48c;
            border-radius: 5px;
            background-color: #F4E6D4;
            color: #543310;
            font-size: 1rem;
        }

        .account-details input:focus {
            outline: none;
            border-color: #c0a078;
            box-shadow: 0 0 5px rgba(192, 160, 120, 0.8);
        }

        .btn-logout {
            background-color: #d2b48c;
            color: #543310;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c0a078;
        }

        .btn-cancel {
            background-color: #d2b48c;
            color: #fff2e1;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-cancel:hover {
            background-color: #a32b3f;
        }

        .back-to-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #d2b48c;
            text-decoration: none;
            font-size: 1rem;
        }

        .back-to-home:hover {
            text-decoration: underline;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #8C785E;
            padding: 10px 20px;
            border-bottom: 2px solid #d2b48c;
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            height: 60px;
            margin-right: 10px;
        }

        .header .title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #fff2e1;
        }

        .header .menu-icon {
            font-size: 1.5rem;
            color: #fff2e1;
            margin-right: 15px;
            cursor: pointer;
        }

        .header .menu-icon:hover {
            color: #d2b48c;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 350px;
            height: 100%;
            background-color: #8C785E;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            padding: 20px 0;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 60px;
        }

        .sidebar .logo img {
            height: 150px; /* Increase the height of the logo */
        }

        .sidebar .title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.90rem;
            color: #543310;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 60px; /* Add space below the title */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 0;
            border-bottom: 1px solid #d2b48c;
        }

        .sidebar ul li:last-child {
            border-bottom: none;
        }

        .sidebar ul li a {
            padding: 10px 20px;
            display: block;
            text-align: left;
            color: #fff2e1;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar ul li a:hover {
            color: #d2b48c;
        }

        .sidebar ul li a.active {
            background-color: #d2b48c; /* Highlight background */
            color: #543310; /* Highlight text color */
            font-weight: bold; /* Optional: Make the text bold */
        }

        .content {
            margin-left: 320px; /* Leave space for the sidebar */
            padding: 20px;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            min-height: 100vh; /* Ensure full height for vertical alignment */
        }

        .section {
            display: none; /* Hide all sections by default */
            width: 100%; /* Ensure sections take full width */
            max-width: 800px; /* Optional: Limit the width of sections */
            text-align: center; /* Center text inside sections */
        }

        .section.active {
            display: flex; /* Use flexbox for alignment */
            flex-direction: column; /* Stack elements vertically */
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
        }

        .account-container {
            margin-top: 20px; /* Add spacing between the <p> and the container */
            text-align: left; /* Align the container content to the left */
            width: 100%; /* Ensure it aligns with the section width */
            max-width: 600px; /* Optional: Limit the width of the container */
        }
    </style>
    <!-- Add Bootstrap modal for save confirmation -->
    <div class="modal fade" id="saveConfirmationModal" tabindex="-1" aria-labelledby="saveConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #FFF2E1; color: #543310;">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveConfirmationModalLabel">Confirm Save</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to save the changes?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSaveButton" style="background-color: #8C785E; border-color: #8C785E;">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap modal for success message -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #FFF2E1; color: #543310;">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Account Updated Successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background-color: #8C785E; border-color: #8C785E;">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap modal for deactivation confirmation -->
    <div class="modal fade" id="deactivationModal" tabindex="-1" aria-labelledby="deactivationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #FFF2E1; color: #543310;">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivationModalLabel">Confirm Deactivation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to deactivate your account? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeactivationButton" onclick="deactivateAccount()">Deactivate</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.section');
            const links = document.querySelectorAll('.sidebar ul li a');

            // Hide all sections and remove active class from all links
            sections.forEach(section => section.classList.remove('active'));
            links.forEach(link => link.classList.remove('active'));

            // Show the selected section and highlight the corresponding link
            document.getElementById(sectionId).classList.add('active');
            document.querySelector(`.sidebar ul li a[onclick="showSection('${sectionId}')"]`).classList.add('active');

            // Fetch privacy settings if the "Account Manager" section is clicked
            if (sectionId === 'account-manager') {
                fetch('get_privacy_settings.php', { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('emailNotifications').checked = data.emailNotifications;
                            document.getElementById('dataSharing').checked = data.dataSharing;
                        } else {
                            alert('Failed to retrieve privacy settings.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching privacy settings:', error);
                        alert('An error occurred while fetching privacy settings.');
                    });
            }
        }

        function enableEdit() {
            // Replace display spans with styled input fields
            document.getElementById('firstNameDisplay').innerHTML = `<input type="text" id="firstNameInput" value="<?php echo $first_name; ?>">`;
            document.getElementById('lastNameDisplay').innerHTML = `<input type="text" id="lastNameInput" value="<?php echo $last_name; ?>">`;
            document.getElementById('emailDisplay').innerHTML = `<input type="email" id="emailInput" value="<?php echo $email; ?>">`;
            document.getElementById('addressDisplay').innerHTML = `<input type="text" id="addressInput" value="<?php echo $address; ?>">`;
            document.getElementById('contactNumberDisplay').innerHTML = `<input type="text" id="contactNumberInput" value="<?php echo $contact_number; ?>">`;

            // Show the save and cancel buttons, hide the edit button
            document.getElementById('editButton').style.display = 'none';
            document.getElementById('saveButton').style.display = 'inline-block';
            document.getElementById('cancelButton').style.display = 'inline-block';
        }

        function cancelEdit() {
            // Reload the page to discard changes
            location.reload();
        }

        function saveInformation() {
            // Show the save confirmation modal
            const saveModal = new bootstrap.Modal(document.getElementById('saveConfirmationModal'));
            saveModal.show();

            // Handle save confirmation
            document.getElementById('confirmSaveButton').onclick = function () {
                // Collect updated values
                const updatedData = {
                    firstName: document.getElementById('firstNameInput').value,
                    lastName: document.getElementById('lastNameInput').value,
                    email: document.getElementById('emailInput').value,
                    address: document.getElementById('addressInput').value,
                    contactNumber: document.getElementById('contactNumberInput').value
                };

                // Send updated data to the server
                fetch('update_user_info.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide the save confirmation modal
                        saveModal.hide();

                        // Show the success modal
                        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();

                        // Reload the page after the success modal is dismissed
                        successModal._element.addEventListener('hidden.bs.modal', () => {
                            location.reload();
                        });
                    } else {
                        alert('Failed to update information. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your information.');
                });
            };
        }

        function sendOtp() {
            // Show spinner animation
            const sendOtpButton = document.getElementById('sendOtpButton');
            sendOtpButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            sendOtpButton.disabled = true;

            fetch('send_otp.php', { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('OTP sent successfully!');
                        document.getElementById('otpInput').disabled = false;
                        document.getElementById('verifyOtpButton').disabled = false;
                    } else {
                        alert(`Failed to send OTP: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while sending OTP. Please check the console for details.');
                })
                .finally(() => {
                    // Restore button state
                    sendOtpButton.innerHTML = 'Send OTP';
                    sendOtpButton.disabled = false;
                });
        }

        function verifyOtp() {
            const otp = document.getElementById('otpInput').value;
            fetch('verify_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ otp })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OTP verified successfully!');
                        document.getElementById('sendOtpButton').disabled = true;
                        document.getElementById('otpInput').disabled = true;
                        document.getElementById('verifyOtpButton').disabled = true;
                        document.getElementById('newPasswordInput').disabled = false;
                        document.getElementById('reenterPasswordInput').disabled = false;
                        document.getElementById('savePasswordButton').disabled = false;
                    } else {
                        alert('Invalid OTP. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while verifying OTP.');
                });
        }

        function saveNewPassword() {
            const newPassword = document.getElementById('newPasswordInput').value;
            const reenterPassword = document.getElementById('reenterPasswordInput').value;

            if (newPassword !== reenterPassword) {
                alert('Passwords do not match. Please try again.');
                return;
            }

            fetch('update_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ newPassword, reenterPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password updated successfully!');
                    location.reload();
                } else {
                    alert(`Failed to update password: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the password.');
            });
        }

        function logout() {
            fetch('logout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => { // Added parentheses around 'data'
                    if (data.success) {
                        window.location.href = 'log in.html';
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                });
        }

        function confirmDeactivation() {
            const deactivationModal = new bootstrap.Modal(document.getElementById('deactivationModal'));
            deactivationModal.show();
        }

        function deactivateAccount() {
            fetch('deactivate_account.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Your account has been deactivated.');
                        window.location.href = 'log in.html';
                    } else {
                        alert('Failed to deactivate account. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deactivating your account.');
                });
        }

        function savePrivacySettings() {
            const emailNotifications = document.getElementById('emailNotifications').checked;
            const dataSharing = document.getElementById('dataSharing').checked;

            fetch('update_privacy_settings.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ emailNotifications, dataSharing })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Privacy settings updated successfully!');
                } else {
                    alert('Failed to update privacy settings. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating privacy settings.');
            });
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="b&blogo.png" alt="Logo">
            <div class="title">Brew & Blend Cafe</div>
        </div>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="#" onclick="showSection('personal-info')">Personal Information ></a></li>
            <li><a href="#" onclick="showSection('account-info')">Account Information ></a></li>
            <li><a href="#" onclick="showSection('payment-methods')">Payment Methods ></a></li>
            <li><a href="#" onclick="showSection('account-manager')">Account Manager ></a></li>
            <li><a href="#" onclick="showSection('membership-status')">Membership Status ></a></li>
            <li><a href="#" onclick="showSection('refer-friend')">Refer-a-Friend ></a></li>
            <li><a href="#" onclick="showSection('help-center')">Help Center ></a></li>
        </ul>
    </div>
    <div class="content">
        <div id="personal-info" class="section" style="min-height: 600px;">
            <h3>Personal Information</h3>
            <p>Manage your personal details such as name, address, and contact information.</p>
            <div class="account-container">
                <div class="account-header">
                    <h2>My Account</h2>
                    <p>Manage your account details</p>
                </div>
                <div class="account-details">
                    <p><span>First Name:</span> <span id="firstNameDisplay"><?php echo $first_name; ?></span></p>
                    <p><span>Last Name:</span> <span id="lastNameDisplay"><?php echo $last_name; ?></span></p>
                    <p><span>Email:</span> <span id="emailDisplay"><?php echo $email; ?></span></p>
                    <p><span>Address:</span> <span id="addressDisplay"><?php echo $address; ?></span></p>
                    <p><span>Contact Number:</span> <span id="contactNumberDisplay"><?php echo $contact_number; ?></span></p>
                </div>
                <div class="text-center">
                    <button class="btn-logout" id="editButton" onclick="enableEdit()">Edit Information</button>
                    <button class="btn-logout" id="saveButton" onclick="saveInformation()" style="display: none;">Save</button>
                    <button class="btn-cancel" id="cancelButton" onclick="cancelEdit()" style="display: none;">Cancel</button>
                </div>
                <a href="homepage.php" class="back-to-home">Back to Home</a>
            </div>
        </div>
        <div id="account-info" class="section" style="min-height: 600px;">
            <h3>Account Information</h3>
            <p>Update your account settings, username, and password.</p>
            <div class="account-container">
                <div class="account-header">
                    <h2>Change Password</h2>
                </div>
                <div class="account-details">
                    <button class="btn-logout" id="sendOtpButton" onclick="sendOtp()">Send OTP</button>
                    <div style="margin-top: 15px;">
                        <input type="text" id="otpInput" placeholder="Enter Verification Code" disabled>
                        <button class="btn-logout" id="verifyOtpButton" onclick="verifyOtp()" disabled>Verify OTP</button>
                    </div>
                    <div style="margin-top: 15px;">
                        <input type="password" id="newPasswordInput" placeholder="Enter New Password" disabled>
                        <input type="password" id="reenterPasswordInput" placeholder="Re-enter New Password" disabled>
                    </div>
                    <button class="btn-logout" id="savePasswordButton" onclick="saveNewPassword()" disabled>Save</button>
                </div>
            </div>
        </div>
        <div id="payment-methods" class="section" style="min-height: 600px;">
            <h3>Payment Methods</h3>
            <p>Manage your saved payment methods, including credit cards and bank accounts.</p>
        </div>
        <div id="account-manager" class="section" style="min-height: 600px;">
            <h3>Account Manager</h3>
            <p>Access tools to manage your account effectively, including privacy settings.</p>
            <div class="account-container">
                <div class="account-header">
                    <h2>Deactivate Account</h2>
                </div>
                <div class="account-details">
                    <p>Deactivating your account will disable your access and remove your data from our system.</p>
                    <button class="btn-logout" id="deactivateAccountButton" onclick="confirmDeactivation()">Deactivate Account</button>
                </div>
                <div class="account-header" style="margin-top: 30px;">
                    <h2>Privacy Settings</h2>
                </div>
                <div class="account-details">
                    <form id="privacySettingsForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">
                                Receive Email Notifications
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="dataSharing">
                            <label class="form-check-label" for="dataSharing">
                                Allow Data Sharing with Third Parties
                            </label>
                        </div>
                        <button type="button" class="btn-logout" style="margin-top: 15px;" onclick="savePrivacySettings()">Save Privacy Settings</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="membership-status" class="section" style="min-height: 600px;">
            <h3>Membership Status</h3>
            <p>Check your membership details, renewal dates, and benefits.</p>
        </div>
        <div id="refer-friend" class="section" style="min-height: 600px;">
            <h3>Refer-a-Friend</h3>
            <p>Invite friends to join and earn rewards for successful referrals.</p>
        </div>
        <div id="help-center" class="section" style="min-height: 600px;">
            <h3>Help Center</h3>
            <p>Find answers to frequently asked questions or contact support for assistance.</p>
        </div>
    </div>
    <script>
        function logout() {
            fetch('logout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => { // Added parentheses around 'data'
                    if (data.success) {
                        window.location.href = 'log in.html';
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
