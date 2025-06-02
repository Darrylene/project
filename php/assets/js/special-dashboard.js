document.querySelectorAll(".sidemenu-item").forEach((item) => {
  item.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the default link behavior

    const target = this.getAttribute("data-target");
    const targetElement = document.getElementById(target);

    // Hide all content sections
    document.querySelectorAll(".main").forEach((section) => {
      section.style.display = "none";
      section.classList.remove("active");
    });

    // Hide all submenus and reset arrows
    document.querySelectorAll(".submenu").forEach((submenu) => {
      submenu.classList.remove("expanded");
      submenu.querySelectorAll(".submenu-item").forEach((item) => {
        item.classList.remove("active");
        item.style.display = "none"; // Hide submenu items
      });
    });
    document.querySelectorAll(".arrow").forEach((arrow) => {
      arrow.classList.remove("down");
    });

    // Add active class to the clicked menu item and target section
    if (target) {
      targetElement.style.display = "block";
      setTimeout(() => {
        targetElement.classList.add("active");
      }, 10);
      targetElement.classList.add("active");

      // Fetch and display users if the target is user-management
      if (target === "user-management") {
        fetchUsers();
      }
    }
  });
});

// Expandable menu for Inventory Management
const inventoryManagementToggle = document.getElementById(
  "inventoryManagementToggle"
);
const inventorySubmenu = document.getElementById("inventorySubmenu");
const arrowIcon = inventoryManagementToggle.querySelector(".arrow");

inventoryManagementToggle.addEventListener("click", (event) => {
  event.preventDefault();
  const isVisible = inventorySubmenu.classList.contains("expanded");
  inventorySubmenu.classList.toggle("expanded", !isVisible);
  arrowIcon.classList.toggle("down", !isVisible); // Toggle arrow direction

  const submenuItems = inventorySubmenu.querySelectorAll(".submenu-item");
  if (!isVisible) {
    submenuItems.forEach((item, index) => {
      setTimeout(() => {
        item.style.display = "block"; // Ensure submenu items are displayed
        item.classList.add("active");
      }, index * 100); // Delay each item by 100ms
    });
  } else {
    submenuItems.forEach((item) => {
      item.classList.remove("active");
      item.style.display = "none"; // Hide submenu items
    });
  }
});

// Handle submenu item clicks
document.querySelectorAll(".submenu-item").forEach((item) => {
  item.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the default link behavior

    const target = this.getAttribute("data-target");
    const targetElement = document.getElementById(target);

    // Hide all content sections
    document.querySelectorAll(".main").forEach((section) => {
      section.style.display = "none";
      section.classList.remove("active");
    });

    // Add active class to the clicked submenu item and target section
    if (target) {
      targetElement.style.display = "block";
      setTimeout(() => {
        targetElement.classList.add("active");
      }, 10);
      targetElement.classList.add("active");
    }
  });
});

// User Management
const userTable = document.getElementById("userTable").querySelector("tbody");
const addUserButton = document.getElementById("addUserButton");
const editUserModal = document.getElementById("editUserModal");
const closeModalButtons = document.querySelectorAll(".close");
console.log(closeModalButtons); // Debug log to check selected elements
const saveUserButton = document.getElementById("saveUserButton");
const resetPasswordButton = document.getElementById("resetPasswordButton");
const addUserForm = document
  .getElementById("addUserForm")
  .querySelector(".modal-content form"); // Ensure correct form element
const saveNewUserButton = document.getElementById("saveNewUserButton");
const refreshButton = document.getElementById("refreshButton");
const loadingSpinner = document.getElementById("loadingSpinner");
const coffeeLoadingScreen = document.getElementById("coffeeLoadingScreen");

const swalCustomStyles = {
  confirmButtonColor: getComputedStyle(document.documentElement)
    .getPropertyValue("--darkbrown")
    .trim(),
  cancelButtonColor: getComputedStyle(document.documentElement)
    .getPropertyValue("--brownhover")
    .trim(),
  background: getComputedStyle(document.documentElement)
    .getPropertyValue("--CoffeeLight")
    .trim(),
  color: getComputedStyle(document.documentElement)
    .getPropertyValue("--darkbrown")
    .trim(),
  iconColor: getComputedStyle(document.documentElement)
    .getPropertyValue("--darkbrown")
    .trim(),
};

// Function to enable the save button if any input changes
function enableSaveButton() {
  saveNewUserButton.disabled = false;
}

// Add event listeners to all input fields and select elements in the add user form
document
  .querySelectorAll("#addUserForm input, #addUserForm select")
  .forEach((input) => {
    input.addEventListener("input", enableSaveButton);
  });

let originalAddUserFormData; // Declare the variable here

// Function to enable or disable the save button based on form changes
function toggleSaveButton() {
  const currentFormData = new FormData(addUserForm);
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalAddUserFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveNewUserButton.disabled = !isChanged;
}

// Add event listeners to all input fields and select elements in the add user form
document
  .querySelectorAll("#addUserForm input, #addUserForm select")
  .forEach((input) => {
    input.addEventListener("input", toggleSaveButton);
  });

addUserButton.addEventListener("click", () => {
  console.log("Add New User button clicked");
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "get_next_user_id.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR readyState: ", xhr.readyState);
      if (xhr.status === 200) {
        console.log("XHR status: ", xhr.status);
        try {
          const response = JSON.parse(xhr.responseText);
          console.log("Response: ", response);
          if (response.success) {
            document.getElementById("newUserId").value = response.userId;
            document.getElementById("addUserForm").style.display = "block"; // Ensure the modal is displayed
            document.querySelector(
              "#addUserForm .modal-content"
            ).style.display = "block"; // Ensure modal content is displayed
            saveNewUserButton.disabled = true; // Ensure save button is disabled initially
            originalAddUserFormData = new FormData(addUserForm); // Update original form data
          } else {
            alert("Failed to fetch next User ID: " + response.message);
          }
        } catch (e) {
          console.error("Failed to parse response: ", e);
          alert("Failed to parse response: " + e.message);
        }
      } else {
        console.error("XHR status: ", xhr.status);
        alert("Failed to fetch next User ID: " + xhr.statusText);
      }
    }
  };
  xhr.send();
});

// Close modals when clicking the close button
document.addEventListener("DOMContentLoaded", () => {
  closeModalButtons.forEach((button) => {
    button.onclick = function () {
      console.log("Close button clicked"); // Debug log
      button.closest(".modal").style.display = "none";
    };
  });
});

// Close modals when clicking outside of them
document.addEventListener("DOMContentLoaded", () => {
  window.onclick = function (event) {
    if (event.target.classList.contains("modal")) {
      console.log("Outside modal clicked"); // Debug log
      event.target.style.display = "none";
    }
  };
});

// Example console log for checking z-index issues in developer tools
document.querySelectorAll(".modal").forEach((modal) => {
  console.log("Modal z-index:", window.getComputedStyle(modal).zIndex);
});

function highlightUnfilledFields(form) {
  const inputs = form.querySelectorAll("input, select");
  let allFilled = true;

  inputs.forEach((input) => {
    if (!input.value) {
      input.classList.add("input-error");
      allFilled = false;
    } else {
      input.classList.remove("input-error");
    }
  });

  return allFilled;
}

saveNewUserButton.addEventListener("click", () => {
  if (!highlightUnfilledFields(addUserForm)) {
    Swal.fire({
      icon: "warning",
      title: "Incomplete Fields",
      text: "Please fill in all fields to add a user.",
      ...swalCustomStyles,
    });
    return;
  }

  const userId = document.getElementById("newUserId").value;
  const userFirstName = document.getElementById("newUserFirstName").value;
  const userLastName = document.getElementById("newUserLastName").value;
  const userEmail = document.getElementById("newUserEmail").value;
  const userType = document.getElementById("newUserType").value;
  const userAddress = document.getElementById("newUserAddress").value;
  const userContact = document.getElementById("newUserContact").value;
  const userStatus = document.getElementById("newUserStatus").value;
  const passwordStatus = "Requested"; // Set PasswordStatus to Requested

  coffeeLoadingScreen.style.display = "flex"; // Show coffee loading screen
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "add_user.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      coffeeLoadingScreen.style.display = "none"; // Hide coffee loading screen
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            addUser(
              userId,
              userFirstName,
              userLastName,
              userEmail,
              userType,
              userAddress,
              userContact,
              userStatus,
              passwordStatus
            );
            addUserForm.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "User Added",
              text: "The user has been added successfully!",
              ...swalCustomStyles,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Add User",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to add user: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(
    `userId=${userId}&userFirstName=${userFirstName}&userLastName=${userLastName}&userEmail=${userEmail}&userType=${userType}&userAddress=${userAddress}&userContact=${userContact}&userStatus=${userStatus}&passwordStatus=${passwordStatus}`
  );
});

saveUserButton.addEventListener("click", () => {
  const userId = document.getElementById("editUserId").value;
  const userFirstName = document.getElementById("editUserFirstName").value;
  const userLastName = document.getElementById("editUserLastName").value;
  const userEmail = document.getElementById("editUserEmail").value;
  const userType = document.getElementById("editUserType").value; // Ensure this captures the correct value
  const userAddress = document.getElementById("editUserAddress").value;
  const userContact = document.getElementById("editUserContact").value;
  const userStatus = document.getElementById("editUserStatus").value;
  const passwordStatus = document.getElementById("editPasswordStatus").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_user.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            const rows = userTable.getElementsByTagName("tr");
            for (let i = 0; i < rows.length; i++) {
              const cells = rows[i].getElementsByTagName("td");
              if (cells[0].innerText === userId) {
                cells[1].innerText = userFirstName;
                cells[2].innerText = userLastName;
                cells[3].innerText = userEmail;
                cells[4].innerText = userType; // Update the table with the correct value
                cells[5].innerText = userAddress;
                cells[6].innerText = userContact;
                cells[7].innerText = userStatus;
                cells[8].innerText = passwordStatus;
                break;
              }
            }
            editUserModal.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "User Updated",
              text: "The user has been updated successfully!",
              ...swalCustomStyles,
              iconColor: swalCustomStyles.iconColor,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Update User",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to update user: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(
    `userId=${userId}&userFirstName=${userFirstName}&userLastName=${userLastName}&userEmail=${userEmail}&userType=${userType}&userAddress=${userAddress}&userContact=${userContact}&userStatus=${userStatus}&passwordStatus=${passwordStatus}`
  );
});

resetPasswordButton.addEventListener("click", () => {
  document.getElementById("editPasswordStatus").value = "Requested";
  const event = new Event("input", { bubbles: true });
  editUserForm.dispatchEvent(event); // Trigger input event to enable save button
});

document.addEventListener("DOMContentLoaded", () => {
  fetchUsers();
});

function fetchUsers() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_users.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          console.log("Parsed response: ", response);
          if (response.success) {
            userTable.innerHTML = ""; // Clear existing rows
            response.users.forEach((user) => {
              console.log("Adding user: ", user);
              addUser(
                user.ID,
                user.FirstName,
                user.LastName,
                user.Email,
                user.TypeOfUser,
                user.Address,
                user.ContactNumber,
                user.AccountStatus,
                user.PasswordStatus
              );
            });
          } else {
            alert("Failed to fetch users: " + response.message);
          }
        } catch (e) {
          console.error("Failed to parse response: ", e);
          alert("Failed to parse response: " + e.message);
        }
      } else {
        console.error("XHR status: ", xhr.status);
        alert("Failed to fetch users: " + xhr.statusText);
      }
    }
  };
  xhr.send();
}

function addUser(
  userId,
  userFirstName,
  userLastName,
  userEmail,
  userType,
  userAddress,
  userContact,
  userStatus,
  passwordStatus
) {
  console.log("Creating row for user: ", userId);
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
        <td>${userId}</td>
        <td>${userFirstName}</td>
        <td>${userLastName}</td>
        <td>${userEmail}</td>
        <td>${userType}</td>
        <td>${userAddress}</td>
        <td>${userContact}</td>
        <td>${userStatus}</td>
        <td>${passwordStatus}</td>
        <td><button class="btn editUser">Edit</button> <button class="btn deleteUser">Delete</button></td>
    `;

  userTable.appendChild(newRow);

  newRow.querySelector(".editUser").addEventListener("click", () => {
    document.getElementById("editUserId").value = userId;
    document.getElementById("editUserFirstName").value = userFirstName;
    document.getElementById("editUserLastName").value = userLastName;
    document.getElementById("editUserEmail").value = userEmail;
    document.getElementById("editUserType").value = userType;
    document.getElementById("editUserAddress").value = userAddress;
    document.getElementById("editUserContact").value = userContact;
    document.getElementById("editUserStatus").value = userStatus;
    document.getElementById("editPasswordStatus").value = passwordStatus;

    editUserModal.style.display = "block";
    originalFormData = new FormData(editUserForm); // Update original form data
    saveUserButton.disabled = true; // Disable save button initially
  });

  newRow.querySelector(".deleteUser").addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      ...swalCustomStyles,
    }).then((result) => {
      if (result.isConfirmed) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_user.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                newRow.remove();
                Swal.fire("Deleted!", "The user has been deleted.", "success", {
                  ...swalCustomStyles,
                  iconColor: swalCustomStyles.iconColor,
                });
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Failed to Delete User",
                  text: response.message,
                  ...swalCustomStyles,
                });
              }
            } catch (e) {
              console.error("Failed to parse response: ", xhr.responseText);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to parse response: " + xhr.responseText,
                ...swalCustomStyles,
              });
            }
          }
        };
        xhr.send(`userId=${userId}`);
      }
    });
  });
}

refreshButton.addEventListener("click", () => {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "check_deletion.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Records Updated",
            text: "The records have been updated successfully!",
            ...swalCustomStyles,
            iconColor: swalCustomStyles.iconColor,
          });
          fetchUsers(); // Refresh the user list
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed to Update Records",
            text: response.message,
            ...swalCustomStyles,
          });
        }
      } catch (e) {
        console.error("Failed to parse response: ", xhr.responseText);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to parse response: " + xhr.responseText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send();
});

const editUserForm = document.getElementById("editUserForm");
let originalFormData = new FormData(editUserForm);

editUserForm.addEventListener("input", () => {
  const currentFormData = new FormData(editUserForm);
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveUserButton.disabled = !isChanged;
});

// Products Management
const productsTable = document
  .getElementById("productsTable")
  .querySelector("tbody");
const addProductButton = document.getElementById("addProductButton");
const addProductForm = document.getElementById("addProductForm");
const editProductModal = document.getElementById("editProductModal");
const saveNewProductButton = document.getElementById("saveNewProductButton");
const saveProductButton = document.getElementById("saveProductButton");

addProductButton.addEventListener("click", () => {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "get_next_product_id.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            document.getElementById("newProductId").value = response.productId;
            addProductForm.style.display = "block";
            saveNewProductButton.disabled = true; // Ensure save button is disabled initially
            originalAddProductFormData = new FormData(
              addProductForm.querySelector("form")
            ); // Initialize original form data
          } else {
            alert("Failed to fetch next Product ID: " + response.message);
          }
        } catch (e) {
          console.error("Failed to parse response: ", e);
          alert("Failed to parse response: " + e.message);
        }
      } else {
        console.error("XHR status: ", xhr.status);
        alert("Failed to fetch next Product ID: " + xhr.statusText);
      }
    }
  };
  xhr.send();
});

closeModalButtons.forEach((button) => {
  button.onclick = function () {
    addProductForm.style.display = "none";
    editProductModal.style.display = "none";
  };
});

window.onclick = function (event) {
  if (event.target == addProductForm || event.target == editProductModal) {
    addProductForm.style.display = "none";
    editProductModal.style.display = "none";
  }
};

function highlightUnfilledFields(form) {
  const inputs = form.querySelectorAll("input");
  let allFilled = true;

  inputs.forEach((input) => {
    if (!input.value) {
      input.classList.add("input-error");
      allFilled = false;
    } else {
      input.classList.remove("input-error");
    }
  });

  return allFilled;
}

function enableSaveButton() {
  saveNewProductButton.disabled = false;
}

// Add event listeners to all input fields in the add product form
document.querySelectorAll("#addProductForm input").forEach((input) => {
  input.addEventListener("input", enableSaveButton);
});

let originalAddProductFormData; // Declare the variable here

// Function to enable or disable the save button based on form changes
function toggleSaveProductButton() {
  const currentFormData = new FormData(addProductForm.querySelector("form"));
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalAddProductFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveNewProductButton.disabled = !isChanged;
}

// Add event listeners to all input fields in the add product form
document.querySelectorAll("#addProductForm input").forEach((input) => {
  input.addEventListener("input", toggleSaveProductButton);
});

// Function to enable or disable the save button based on form changes for add product form
function toggleSaveNewProductButton() {
  const currentFormData = new FormData(addProductForm.querySelector("form"));
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalAddProductFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveNewProductButton.disabled = !isChanged;
}

// Add event listeners to all input fields in the add product form
document.querySelectorAll("#addProductForm input").forEach((input) => {
  input.addEventListener("input", toggleSaveNewProductButton);
});

// Add event listeners to all input fields and select elements in the add product form
document
  .querySelectorAll("#addProductForm input, #addProductForm select")
  .forEach((input) => {
    input.addEventListener("input", toggleSaveNewProductButton);
  });

saveNewProductButton.addEventListener("click", () => {
  if (!highlightUnfilledFields(addProductForm)) {
    Swal.fire({
      icon: "warning",
      title: "Incomplete Fields",
      text: "Please fill in all fields to add a product.",
      ...swalCustomStyles,
    });
    return;
  }

  const productId = document.getElementById("newProductId").value;
  const productName = document.getElementById("newProductName").value;
  const categoryId = document.getElementById("newCategoryId").value;
  const pricePerUnit = document.getElementById("newPricePerUnit").value;
  const unit = document.getElementById("newUnit").value;
  const stockLevel = document.getElementById("newStockLevel").value;
  const reorderLevel = document.getElementById("newReorderLevel").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "add_product.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            addProduct(
              productId,
              productName,
              categoryId,
              pricePerUnit,
              unit,
              stockLevel,
              reorderLevel
            );
            addProductForm.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "Product Added",
              text: "The product has been added successfully!",
              ...swalCustomStyles,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Add Product",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to add product: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(
    `productId=${productId}&productName=${productName}&categoryId=${categoryId}&pricePerUnit=${pricePerUnit}&unit=${unit}&stockLevel=${stockLevel}&reorderLevel=${reorderLevel}`
  );
});

saveProductButton.addEventListener("click", () => {
  const productId = document.getElementById("editProductId").value;
  const productName = document.getElementById("editProductName").value;
  const categoryId = document.getElementById("editCategoryId").value;
  const pricePerUnit = document.getElementById("editPricePerUnit").value;
  const unit = document.getElementById("editUnit").value;
  const stockLevel = document.getElementById("editStockLevel").value;
  const reorderLevel = document.getElementById("editReorderLevel").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_product.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            const rows = productsTable.getElementsByTagName("tr");
            for (let i = 0; i < rows.length; i++) {
              const cells = rows[i].getElementsByTagName("td");
              if (cells[0].innerText === productId) {
                cells[1].innerText = productName;
                cells[2].innerText = categoryId;
                cells[3].innerText = pricePerUnit;
                cells[4].innerText = unit;
                cells[5].innerText = stockLevel;
                cells[6].innerText = reorderLevel;
                break;
              }
            }
            editProductModal.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "Product Updated",
              text: "The product has been updated successfully!",
              ...swalCustomStyles,
              iconColor: swalCustomStyles.iconColor,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Update Product",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to update product: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(
    `productId=${productId}&productName=${productName}&categoryId=${categoryId}&pricePerUnit=${pricePerUnit}&unit=${unit}&stockLevel=${stockLevel}&reorderLevel=${reorderLevel}`
  );
});

// Function to enable or disable the save button based on form changes for edit product form
function toggleSaveProductButton() {
  const currentFormData = new FormData(editProductForm);
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalEditProductFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveProductButton.disabled = !isChanged;
}

// Add event listeners to all input fields in the edit product form
document.querySelectorAll("#editProductForm input").forEach((input) => {
  input.addEventListener("input", toggleSaveProductButton);
});

// Add event listeners to all input fields and select elements in the edit product form
document
  .querySelectorAll("#editProductForm input, #editProductForm select")
  .forEach((input) => {
    input.addEventListener("input", toggleSaveProductButton);
  });

function addProduct(
  productId,
  productName,
  categoryId,
  pricePerUnit,
  unit,
  stockLevel,
  reorderLevel
) {
  console.log("Creating row for product: ", productId);
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
        <td>${productId}</td>
        <td>${productName}</td>
        <td>${categoryId}</td>
        <td>${pricePerUnit}</td>
        <td>${unit}</td>
        <td>${stockLevel}</td>
        <td>${reorderLevel}</td>
        <td><button class="btn editProduct">Edit</button> <button class="btn deleteProduct">Delete</button></td>
    `;

  productsTable.appendChild(newRow);

  newRow.querySelector(".editProduct").addEventListener("click", () => {
    document.getElementById("editProductId").value = productId;
    document.getElementById("editProductName").value = productName;
    document.getElementById("editCategoryId").value = categoryId;
    document.getElementById("editPricePerUnit").value = pricePerUnit;
    document.getElementById("editUnit").value = unit;
    document.getElementById("editStockLevel").value = stockLevel;
    document.getElementById("editReorderLevel").value = reorderLevel;

    editProductModal.style.display = "block";
    originalEditProductFormData = new FormData(editProductForm); // Update original form data
    saveProductButton.disabled = true; // Disable save button initially
  });

  newRow.querySelector(".deleteProduct").addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      ...swalCustomStyles,
    }).then((result) => {
      if (result.isConfirmed) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_product.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                newRow.remove();
                Swal.fire(
                  "Deleted!",
                  "The product has been deleted.",
                  "success",
                  {
                    ...swalCustomStyles,
                    iconColor: swalCustomStyles.iconColor,
                  }
                );
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Failed to Delete Product",
                  text: response.message,
                  ...swalCustomStyles,
                });
              }
            } catch (e) {
              console.error("Failed to parse response: ", xhr.responseText);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to parse response: " + xhr.responseText,
                ...swalCustomStyles,
              });
            }
          }
        };
        xhr.send(`productId=${productId}`);
      }
    });
  });
}

// Fetch products when the page loads
document.addEventListener("DOMContentLoaded", () => {
  fetchProducts();
});

function fetchProducts() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_products.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          console.log("Parsed response: ", response);
          if (response.success) {
            productsTable.innerHTML = ""; // Clear existing rows
            response.products.forEach((product) => {
              console.log("Adding product: ", product);
              addProduct(
                product.product_id,
                product.product_name,
                product.category_id,
                product.price_per_unit,
                product.unit,
                product.stock_level,
                product.reorder_level
              );
            });
          } else {
            alert("Failed to fetch products: " + response.message);
          }
        } catch (e) {
          console.error("Failed to parse response: ", e);
          alert("Failed to parse response: " + e.message);
        }
      } else {
        console.error("XHR status: ", xhr.status);
        alert("Failed to fetch products: " + xhr.statusText);
      }
    }
  };
  xhr.send();
}

document.addEventListener("DOMContentLoaded", () => {
  // Ensure the modal is displayed correctly
  const editProductModal = document.getElementById("editProductModal");
  const closeModalButtons = document.querySelectorAll(".close");

  closeModalButtons.forEach((button) => {
    button.onclick = function () {
      button.closest(".modal").style.display = "none";
    };
  });

  window.onclick = function (event) {
    if (event.target.classList.contains("modal")) {
      event.target.style.display = "none";
    }
  };
});

const refreshProductsButton = document.getElementById("refreshProductsButton");
const refreshCategoriesButton = document.getElementById(
  "refreshCategoriesButton"
);

refreshProductsButton.addEventListener("click", () => {
  fetchProducts(); // Refresh the products list
  Swal.fire({
    title: "Refreshed!",
    text: "The products table has been refreshed.",
    icon: "success",
    confirmButtonColor: swalCustomStyles.confirmButtonColor,
    background: swalCustomStyles.background,
    color: swalCustomStyles.color,
    iconColor: swalCustomStyles.iconColor,
  });
});

document.addEventListener("DOMContentLoaded", () => {
  fetchCategories();
});

refreshCategoriesButton.addEventListener("click", () => {
  fetchCategories();
  Swal.fire({
    title: "Refreshed!",
    text: "The categories table has been refreshed.",
    icon: "success",
    confirmButtonColor: swalCustomStyles.confirmButtonColor,
    background: swalCustomStyles.background,
    color: swalCustomStyles.color,
    iconColor: swalCustomStyles.iconColor,
  });
});

function fetchCategories() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_categories.php", true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        console.log("Fetched categories response:", response);

        if (response.success && Array.isArray(response.categories)) {
          // Update the dropdowns
          populateCategoryDropdowns(response.categories);

          // Update the categories table if it exists
          if (categoriesTable) {
            categoriesTable.innerHTML = ""; // Clear existing rows
            response.categories.forEach((category) => {
              console.log("Adding category to table:", category);
              addCategory(category.category_id, category.category_name);
            });
          }
        } else {
          console.error("Unexpected response structure:", response);
        }
      } catch (e) {
        console.error("Failed to parse response:", xhr.responseText);
        alert("Failed to parse response: " + e.message);
      }
    } else {
      console.error("XHR error, status:", xhr.statusText);
      alert("Failed to fetch categories: " + xhr.statusText);
    }
  };

  xhr.onerror = function () {
    console.error("Network error occurred.");
  };

  xhr.send();
}

function populateCategoryDropdowns(categories) {
  console.log("populateCategoryDropdowns received:", categories);
  console.log("Type:", typeof categories);

  // If categories are wrapped in an object, extract it:
  if (!Array.isArray(categories) && categories && categories.categories) {
    console.log("Extracting categories from nested object.");
    categories = categories.categories;
  }

  if (!Array.isArray(categories)) {
    console.error(
      "populateCategoryDropdowns: categories is not an array",
      categories
    );
    return;
  }

  const newCategoryIdDropdown = document.getElementById("newCategoryId");
  const editCategoryIdDropdown = document.getElementById("editCategoryId");

  // Clear existing options if needed:
  newCategoryIdDropdown.innerHTML = "";
  editCategoryIdDropdown.innerHTML = "";

  categories.forEach((category) => {
    const optionText = `${category.category_id} - ${category.category_name}`;
    const newOption = new Option(optionText, category.category_id);
    const editOption = new Option(optionText, category.category_id);
    newCategoryIdDropdown.add(newOption);
    editCategoryIdDropdown.add(editOption);
  });

  console.log("Dropdowns updated successfully.");
}

// Categories Management
const categoriesTable = document
  .getElementById("categoriesTable")
  .querySelector("tbody");
const addCategoryButton = document.getElementById("addCategoryButton");
const addCategoryForm = document.getElementById("addCategoryForm");
const editCategoryModal = document.getElementById("editCategoryModal");
const saveNewCategoryButton = document.getElementById("saveNewCategoryButton");
const saveCategoryButton = document.getElementById("saveCategoryButton");

addCategoryButton.addEventListener("click", () => {
  console.log("Add New Category button clicked");

  // Create a new XMLHttpRequest object
  const xhr = new XMLHttpRequest(); // <-- IMPORTANT: Declare it here

  xhr.open("GET", "get_next_category_id.php", true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          const newCategoryIdTableInput =
            document.getElementById("newCategoryIdTable");
          if (newCategoryIdTableInput) {
            newCategoryIdTableInput.value = response.categoryId;
            console.log(
              "New Category ID for table is set to:",
              response.categoryId
            );
          } else {
            console.error("Element with ID 'newCategoryIdTable' not found.");
          }
          addCategoryForm.style.display = "block";
          saveNewCategoryButton.disabled = true;
          originalAddCategoryFormData = new FormData(
            addCategoryForm.querySelector("form")
          );
        } else {
          alert("Failed to fetch next Category ID: " + response.message);
        }
      } catch (e) {
        console.error("Failed to parse response: ", e, xhr.responseText);
        alert("Failed to parse response: " + e.message);
      }
    } else {
      console.error("XHR status: ", xhr.status);
      alert("Failed to fetch next Category ID: " + xhr.statusText);
    }
  };

  xhr.onerror = function () {
    console.error("Network error during get_next_category_id request");
  };

  // Finally, send the request
  xhr.send();
});

closeModalButtons.forEach((button) => {
  button.onclick = function () {
    addCategoryForm.style.display = "none";
    editCategoryModal.style.display = "none";
  };
});

window.onclick = function (event) {
  if (event.target == addCategoryForm || event.target == editCategoryModal) {
    addCategoryForm.style.display = "none";
    editCategoryModal.style.display = "none";
  }
};

function highlightUnfilledFields(form) {
  const inputs = form.querySelectorAll("input");
  let allFilled = true;

  inputs.forEach((input) => {
    if (!input.value) {
      input.classList.add("input-error");
      allFilled = false;
    } else {
      input.classList.remove("input-error");
    }
  });

  return allFilled;
}

function enableSaveButton() {
  saveNewCategoryButton.disabled = false;
}

// Add event listeners to all input fields in the add category form
document.querySelectorAll("#addCategoryForm input").forEach((input) => {
  input.addEventListener("input", enableSaveButton);
});

let originalAddCategoryFormData; // Declare the variable here

// Function to enable or disable the save button based on form changes
function toggleSaveCategoryButton() {
  const currentFormData = new FormData(addCategoryForm.querySelector("form"));
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalAddCategoryFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveNewCategoryButton.disabled = !isChanged;
}

// Add event listeners to all input fields in the add category form
document.querySelectorAll("#addCategoryForm input").forEach((input) => {
  input.addEventListener("input", toggleSaveCategoryButton);
});

saveNewCategoryButton.addEventListener("click", () => {
  if (!highlightUnfilledFields(addCategoryForm)) {
    Swal.fire({
      icon: "warning",
      title: "Incomplete Fields",
      text: "Please fill in all fields to add a category.",
      ...swalCustomStyles,
    });
    return;
  }

  const categoryName = document.getElementById("newCategoryName").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "add_category.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            addCategory(categoryName);
            addCategoryForm.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "Category Added",
              text: "The category has been added successfully!",
              ...swalCustomStyles,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Add Category",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to add category: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(`categoryName=${categoryName}`);
});

saveCategoryButton.addEventListener("click", () => {
  const categoryId = document.getElementById("editCategoryId").value;
  const categoryName = document.getElementById("editCategoryName").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_category.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("XHR response: ", xhr.responseText);
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            const rows = categoriesTable.getElementsByTagName("tr");
            for (let i = 0; i < rows.length; i++) {
              const cells = rows[i].getElementsByTagName("td");
              if (cells[0].innerText === categoryId) {
                cells[1].innerText = categoryName;
                break;
              }
            }
            editCategoryModal.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "Category Updated",
              text: "The category has been updated successfully!",
              ...swalCustomStyles,
              iconColor: swalCustomStyles.iconColor,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Update Category",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        console.error("XHR status: ", xhr.status);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to update category: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(`categoryId=${categoryId}&categoryName=${categoryName}`);
});

function addCategory(categoryName) {
  console.log("Creating row for category: ", categoryName);
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
        <td>${categoryName}</td>
        <td><button class="btn editCategory">Edit</button> <button class="btn deleteCategory">Delete</button></td>
    `;

  categoriesTable.appendChild(newRow);

  newRow.querySelector(".editCategory").addEventListener("click", () => {
    document.getElementById("editCategoryName").value = categoryName;

    editCategoryModal.style.display = "block";
    originalEditCategoryFormData = new FormData(editCategoryForm); // Update original form data
    saveCategoryButton.disabled = true; // Disable save button initially

    // Add event listener to enable save button on form change
    editCategoryForm.addEventListener("input", () => {
      const currentFormData = new FormData(editCategoryForm);
      let isChanged = false;

      for (let [key, value] of currentFormData.entries()) {
        if (value !== originalEditCategoryFormData.get(key)) {
          isChanged = true;
          break;
        }
      }

      saveCategoryButton.disabled = !isChanged;
    });
  });

  newRow.querySelector(".deleteCategory").addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      ...swalCustomStyles,
    }).then((result) => {
      if (result.isConfirmed) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_category.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                newRow.remove();
                Swal.fire(
                  "Deleted!",
                  "The category has been deleted.",
                  "success",
                  {
                    ...swalCustomStyles,
                    iconColor: swalCustomStyles.iconColor,
                  }
                );
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Failed to Delete Category",
                  text: response.message,
                  ...swalCustomStyles,
                });
              }
            } catch (e) {
              console.error("Failed to parse response: ", xhr.responseText);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to parse response: " + xhr.responseText,
                ...swalCustomStyles,
              });
            }
          }
        };
        xhr.send(`categoryName=${categoryName}`);
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const editCategoryForm = document.getElementById("editCategoryForm");
  const saveCategoryButton = document.getElementById("saveCategoryButton");

  // Attach an input listener to the edit form.
  editCategoryForm.addEventListener("input", () => {
    console.log("Edit form input triggered.");

    // Retrieve the original data stored as a data attribute.
    const originalDataJSON = editCategoryForm.getAttribute("data-original");
    if (!originalDataJSON) {
      console.warn("Original edit data not set yet.");
      return;
    }
    const originalData = JSON.parse(originalDataJSON);

    // Get current values from the form fields.
    const currentCategoryId =
      document.getElementById("editCategoryTable").value;
    const currentCategoryName =
      document.getElementById("editCategoryName").value;

    console.log("Current values:", currentCategoryId, currentCategoryName);
    console.log(
      "Original values:",
      originalData.category_id,
      originalData.category_name
    );

    // Compare the current values with the original data.
    if (
      currentCategoryId !== originalData.category_id ||
      currentCategoryName !== originalData.category_name
    ) {
      saveCategoryButton.disabled = false;
      console.log("Save button ENABLED.");
    } else {
      saveCategoryButton.disabled = true;
      console.log("Save button DISABLED.");
    }
  });
});

refreshCategoriesButton.addEventListener("click", () => {
  fetchCategories(); // Refresh the categories list
  Swal.fire({
    title: "Refreshed!",
    text: "The categories table has been refreshed.",
    icon: "success",
    confirmButtonColor: swalCustomStyles.confirmButtonColor,
    background: swalCustomStyles.background,
    color: swalCustomStyles.color,
    iconColor: swalCustomStyles.iconColor,
  });
});

// Fetch categories when the page loads
document.addEventListener("DOMContentLoaded", () => {
  fetchCategories();
});

function addCategory(categoryId, categoryName) {
  console.log("Creating row for category: ", categoryId);
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
        <td>${categoryId}</td>
        <td>${categoryName}</td>
        <td><button class="btn editCategory">Edit</button> <button class="btn deleteCategory">Delete</button></td>
    `;

  categoriesTable.appendChild(newRow);

  newRow.querySelector(".editCategory").addEventListener("click", () => {
    document.getElementById("editCategoryTable").value = categoryId;
    document.getElementById("editCategoryName").value = categoryName;

    editCategoryModal.style.display = "block";
    originalEditCategoryFormData = new FormData(editCategoryForm); // Update original form data
    saveCategoryButton.disabled = true; // Disable save button initially

    // Add event listener to enable save button on form change
    editCategoryForm.addEventListener("input", () => {
      const currentFormData = new FormData(editCategoryForm);
      let isChanged = false;

      for (let [key, value] of currentFormData.entries()) {
        if (value !== originalEditCategoryFormData.get(key)) {
          isChanged = true;
          break;
        }
      }

      saveCategoryButton.disabled = !isChanged;
    });
  });

  newRow.querySelector(".deleteCategory").addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      ...swalCustomStyles,
    }).then((result) => {
      if (result.isConfirmed) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_category.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                newRow.remove();
                Swal.fire(
                  "Deleted!",
                  "The category has been deleted.",
                  "success",
                  {
                    ...swalCustomStyles,
                    iconColor: swalCustomStyles.iconColor,
                  }
                );
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Failed to Delete Category",
                  text: response.message,
                  ...swalCustomStyles,
                });
              }
            } catch (e) {
              console.error("Failed to parse response: ", xhr.responseText);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to parse response: " + xhr.responseText,
                ...swalCustomStyles,
              });
            }
          }
        };
        xhr.send(`categoryId=${categoryId}`);
      }
    });
  });
}

// Menu Management
const menuTable = document.getElementById("menuTable").querySelector("tbody");
const addMenuItemButton = document.getElementById("addMenuItemButton");
const refreshMenuButton = document.getElementById("refreshMenuButton");

refreshMenuButton.addEventListener("click", () => {
  if (addMenuItemForm.style.display === "block") return; // Do nothing if modal is open
  fetchMenuItems(); // Refresh the menu items list
  Swal.fire({
    title: "Refreshed!",
    text: "The menu table has been refreshed.",
    icon: "success",
    confirmButtonColor: swalCustomStyles.confirmButtonColor,
    background: swalCustomStyles.background,
    color: swalCustomStyles.color,
    iconColor: swalCustomStyles.iconColor,
  });
});

function fetchMenuItems() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_menu_items.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            menuTable.innerHTML = ""; // Clear existing rows
            response.menuItems.forEach((menuItem) => {
              addMenuItem(
                menuItem.menu_item_id,
                menuItem.menu_item_name,
                menuItem.description,
                menuItem.price,
                menuItem.image_url,
                menuItem.menu_category_id
              );
            });
          } else {
            alert("Failed to fetch menu items: " + response.message);
          }
        } catch (e) {
          console.error("Failed to parse response: ", e);
          alert("Failed to parse response: " + e.message);
        }
      } else {
        alert("Failed to fetch menu items: " + xhr.statusText);
      }
    }
  };
  xhr.send();
}

function addMenuItem(
  menuId,
  menuName,
  description,
  price,
  imageUrl,
  categoryId
) {
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
        <td>${menuId}</td>
        <td>${menuName}</td>
        <td>${description}</td>
        <td>${price}</td>
        <td><img src="${imageUrl}" alt="${menuName}" width="50"></td>
        <td>${categoryId}</td>
        <td>
            <button class="btn editMenuItem">Edit</button>
            <button class="btn deleteMenuItem">Delete</button>
        </td>
    `;

  menuTable.appendChild(newRow);

  newRow.querySelector(".editMenuItem").addEventListener("click", () => {
    populateMenuCategories(); // Populate categories dynamically
    populateEditMenuItemModal({
      menu_item_id: menuId,
      menu_item_name: menuName,
      description: description,
      price: price,
      image_url: imageUrl,
      menu_category_id: categoryId,
    });
    editMenuItemForm.style.display = "block";
  });

  newRow.querySelector(".deleteMenuItem").addEventListener("click", () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      ...swalCustomStyles,
    }).then((result) => {
      if (result.isConfirmed) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_menu_item.php", true); // Ensure the path is correct
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                newRow.remove();
                Swal.fire(
                  "Deleted!",
                  "The menu item has been deleted.",
                  "success"
                );
              } else {
                Swal.fire("Error", response.message, "error");
              }
            } catch (e) {
              Swal.fire("Error", "Failed to parse response.", "error");
            }
          }
        };
        xhr.send(`menuId=${menuId}`);
      }
    });
  });
}

// Fetch menu items when the page loads
document.addEventListener("DOMContentLoaded", () => {
  fetchMenuItems();
});

const addMenuItemForm = document.getElementById("addMenuItemForm");
const saveNewMenuItemButton = document.getElementById("saveNewMenuItemButton");

// Track original form data for "Add New Menu Item"
let originalAddMenuItemFormData;

// Function to fetch and populate menu categories
function populateMenuCategories() {
  const menuCategoryDropdown = document.getElementById("newMenuCategoryId");

  // Clear existing options
  menuCategoryDropdown.innerHTML =
    '<option value="" disabled selected>Select a category</option>';

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_menu_categories.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          response.categories.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.menu_category_id;
            option.textContent = category.menu_category_name;
            menuCategoryDropdown.appendChild(option);
          });
        } else {
          console.error("Failed to fetch menu categories:", response.message);
        }
      } catch (e) {
        console.error("Error parsing menu categories response:", e);
      }
    }
  };
  xhr.send();
}

// Open the "Add New Menu Item" modal
addMenuItemButton.addEventListener("click", () => {
  populateMenuCategories();
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "get_next_menu_item_id.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          document.getElementById("newMenuItemId").value = response.menuItemId;
          addMenuItemForm.style.display = "block";
          saveNewMenuItemButton.disabled = true; // Disable save button initially
          originalAddMenuItemFormData = new FormData(
            addMenuItemForm.querySelector("form")
          ); // Store original form data
        } else {
          alert("Failed to fetch next Menu Item ID: " + response.message);
        }
      } catch (e) {
        console.error("Failed to parse response: ", e);
        alert("Failed to parse response: " + e.message);
      }
    }
  };
  xhr.send();
});

// Close the modal
addMenuItemForm.querySelector(".close").addEventListener("click", () => {
  addMenuItemForm.style.display = "none";
});

// Enable the save button when any input changes
document
  .querySelectorAll(
    "#addMenuItemForm input, #addMenuItemForm textarea, #addMenuItemForm select"
  )
  .forEach((input) => {
    input.addEventListener("input", toggleSaveMenuItemButton);
  });

// Define the toggleSaveMenuItemButton function
function toggleSaveMenuItemButton() {
  const currentFormData = new FormData(addMenuItemForm.querySelector("form"));
  let isChanged = false;

  for (let [key, value] of currentFormData.entries()) {
    if (value !== originalAddMenuItemFormData.get(key)) {
      isChanged = true;
      break;
    }
  }

  saveNewMenuItemButton.disabled = !isChanged;
}

// Close the modal
addMenuItemForm.querySelector(".close").addEventListener("click", () => {
  addMenuItemForm.style.display = "none";
});

// Save the new menu item
saveNewMenuItemButton.addEventListener("click", () => {
  const menuItemId = document.getElementById("newMenuItemId").value;
  const menuItemName = document.getElementById("newMenuItemName").value;
  const description = document.getElementById("newDescription").value;
  const price = document.getElementById("newPrice").value;
  const imageUrl = document.getElementById("newImageUrl").value;
  const menuCategoryId = document.getElementById("newMenuCategoryId").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "add_menu_item.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            addMenuItem(
              menuItemId,
              menuItemName,
              description,
              price,
              imageUrl,
              menuCategoryId
            );
            addMenuItemForm.style.display = "none";
            Swal.fire({
              icon: "success",
              title: "Menu Item Added",
              text: "The menu item has been added successfully!",
              ...swalCustomStyles,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed to Add Menu Item",
              text: response.message,
              ...swalCustomStyles,
            });
          }
        } catch (e) {
          console.error("Failed to parse response: ", xhr.responseText);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to parse response: " + xhr.responseText,
            ...swalCustomStyles,
          });
        }
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to add menu item: " + xhr.statusText,
          ...swalCustomStyles,
        });
      }
    }
  };
  xhr.send(
    `menuItemId=${menuItemId}&menuItemName=${menuItemName}&description=${description}&price=${price}&imageUrl=${imageUrl}&menuCategoryId=${menuCategoryId}`
  );
});

const editMenuItemForm = document.getElementById("editMenuItemForm");
const saveMenuItemButton = document.getElementById("saveMenuItemButton");
let originalEditMenuItemFormData;

// Function to populate the "Edit Menu Item" modal
function populateEditMenuItemModal(menuItem) {
  document.getElementById("editMenuItemId").value = menuItem.menu_item_id;
  document.getElementById("editMenuItemName").value = menuItem.menu_item_name;
  document.getElementById("editDescription").value = menuItem.description;
  document.getElementById("editPrice").value = menuItem.price;
  document.getElementById("editImageUrl").value = menuItem.image_url;

  const menuCategoryDropdown = document.getElementById("editMenuCategoryId");

  // Clear existing options
  menuCategoryDropdown.innerHTML =
    '<option value="" disabled>Select a category</option>';

  // Fetch menu categories and populate the dropdown
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_menu_categories.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          response.categories.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.menu_category_id;
            option.textContent = category.menu_category_name;

            // Set the selected option if it matches the menu item's category
            if (category.menu_category_id === menuItem.menu_category_id) {
              option.selected = true;
            }

            menuCategoryDropdown.appendChild(option);
          });
        } else {
          console.error("Failed to fetch menu categories:", response.message);
        }
      } catch (e) {
        console.error("Error parsing menu categories response:", e);
      }
    }
  };
  xhr.send();

  originalEditMenuItemFormData = new FormData(
    editMenuItemForm.querySelector("form")
  );
  saveMenuItemButton.disabled = true; // Disable the save button initially
}

// Ensure the element exists before accessing it
if (editMenuItemForm) {
  const formElement = editMenuItemForm.querySelector("form");
  if (formElement) {
    editMenuItemForm
      .querySelectorAll("input, textarea, select")
      .forEach((input) => {
        input.addEventListener("input", () => {
          const currentFormData = new FormData(formElement);
          let isChanged = false;

          for (let [key, value] of currentFormData.entries()) {
            if (value !== originalEditMenuItemFormData.get(key)) {
              isChanged = true;
              break;
            }
          }

          saveMenuItemButton.disabled = !isChanged;
        });
      });

    // Close the modal when the close button is clicked
    const closeButton = editMenuItemForm.querySelector(".close");
    if (closeButton) {
      closeButton.addEventListener("click", () => {
        editMenuItemForm.style.display = "none";
      });
    }
  } else {
    console.error("Form element inside editMenuItemForm is not found.");
  }
} else {
  console.error("editMenuItemForm is not found in the DOM.");
}

// Ensure the element exists before attaching the event listener
if (saveMenuItemButton) {
  saveMenuItemButton.addEventListener("click", () => {
    const menuItemId = document.getElementById("editMenuItemId").value;
    const menuItemName = document.getElementById("editMenuItemName").value;
    const description = document.getElementById("editDescription").value;
    const price = document.getElementById("editPrice").value;
    const imageUrl = document.getElementById("editImageUrl").value;
    const menuCategoryId = document.getElementById("editMenuCategoryId").value;

    if (
      !menuItemName ||
      !description ||
      !price ||
      !imageUrl ||
      !menuCategoryId
    ) {
      Swal.fire({
        icon: "warning",
        title: "Incomplete Fields",
        text: "Please fill in all fields to save the menu item.",
        ...swalCustomStyles,
      });
      return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_menu_item.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
              fetchMenuItems(); // Refresh the menu table
              editMenuItemForm.style.display = "none";
              Swal.fire({
                icon: "success",
                title: "Menu Item Updated",
                text: "The menu item has been updated successfully!",
                ...swalCustomStyles,
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Failed to Update Menu Item",
                text: response.message,
                ...swalCustomStyles,
              });
            }
          } catch (e) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to parse response: " + xhr.responseText,
              ...swalCustomStyles,
            });
          }
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to update menu item: " + xhr.statusText,
            ...swalCustomStyles,
          });
        }
      }
    };
    xhr.send(
      `menuItemId=${menuItemId}&menuItemName=${menuItemName}&description=${description}&price=${price}&imageUrl=${imageUrl}&menuCategoryId=${menuCategoryId}`
    );
  });
}
