// Add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");
function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu toggle
let toggle = document.querySelector(".sidemenu-item");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");
let customermessages = document.querySelector(".customermessages");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
  toggle.classList.toggle("active");
  customermessages.classList.toggle("active");
};

const listItems = document.querySelectorAll(".sidebar-list li");

listItems.forEach((item) => {
  item.addEventListener("click", () => {
    let isActive = item.classList.contains("active");

    listItems.forEach((el1) => {
      el1.classList.remove("active");
    });

    if (isActive) item.classList.remove("active");
    else item.classList.add("active");
  });
});

document.querySelectorAll(".sidemenu-item").forEach((item) => {
  item.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the default link behavior

    const target = this.getAttribute("data-target");
    const targetElement = document.getElementById(target);

    // If the target section is already active, hide it
    if (targetElement.classList.contains("clicked")) {
      targetElement.style.display = "none";
      targetElement.classList.remove("clicked");
      this.classList.remove("clicked");
      navigation.classList.remove("active");
    } else {
      // Hide all content sections
      document.querySelectorAll(".main").forEach((section) => {
        section.style.display = "none";
        section.classList.remove("clicked");
      });
      // Remove active class from all menu items
      document
        .querySelectorAll(".sidemenu-item")
        .forEach((el) => el.classList.remove("clicked"));

      // Add active class to the clicked menu item and target section
      if (target) {
        targetElement.style.display = "block";
        setTimeout(() => {
          targetElement.classList.add("clicked");
        }, 10);
        targetElement.classList.add("clicked");
      }
      this.classList.add("clicked");
      navigation.classList.add("active");
    }
  });
});

// Handle message table row click event
document.querySelectorAll("#messagesTable tr").forEach((row, index) => {
  if (index === 0) return; // Skip the first row (header row)

  row.addEventListener("click", function () {
    const customerName = this.cells[0].innerText;
    const messageContent = this.cells[1].innerText;

    const messageBoxOverlay = document.getElementById("messageBoxOverlay");
    const customerNameHeading = document.getElementById("customerName");
    const messageContentParagraph = document.getElementById("messageContent");

    if (customerName && messageContent) {
      customerNameHeading.textContent = customerName;
      messageContentParagraph.textContent = messageContent;
      messageBoxOverlay.style.display = "flex";
      console.log("Message box displayed");
      console.log("Overlay Display:", messageBoxOverlay.style.display);
    }
  });
});

// Handle close button click event to hide the message box
document
  .getElementById("closeMessageBox")
  .addEventListener("click", function () {
    const messageBoxOverlay = document.getElementById("messageBoxOverlay");
    messageBoxOverlay.style.display = "none";
    console.log("Message box hidden");
  });

document
  .getElementById("replyButtonBox")
  .addEventListener("click", function () {
    const customerName = document.getElementById("customerName").innerText;
    const customerEmail = "ajhae16@outlook.com"; // Replace with actual customer email if available
    const subject = "Reply to Your Message";
    const body = `Dear ${customerName},\n\nThank you for reaching out to us. Here is our response:\n\n`;

    const email = `mailto:${customerEmail}?subject=${encodeURIComponent(
      subject
    )}&body=${encodeURIComponent(body)}`;
    window.location.href = email;
  });

document.querySelectorAll("#messagesTable tr").forEach((row, index) => {
  if (index === 0) return;

  const nameCell = row.cells[0];
  const cell = row.cells[1]; // Get the second cell (Subject column)
  if (nameCell) {
    nameCell.classList.add("blurred");
  }
  if (cell) {
    cell.classList.add("blurred");
  }
});

document.getElementById("searchBar").addEventListener("input", function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll("#messagesTable tr");

  rows.forEach((row, index) => {
    if (index === 0) return;

    const cell = row.cells[0]; // Get the first cell (Sender column)
    if (cell) {
      const textValue = cell.textContent || cell.innerText;
      if (textValue.toLowerCase().indexOf(filter) > -1) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const ordersTable = document
    .getElementById("ordersTable")
    .querySelector("tbody");
  const inTransitTable = document
    .getElementById("inTransitTable")
    .querySelector("tbody");
  const cancelledTable = document
    .getElementById("cancelledTable")
    .querySelector("tbody");
  const deliveredTable = document
    .getElementById("deliveredTable")
    .querySelector("tbody");

  // Function to add a new order row to a specific table
  function addOrderRow(order, targetTable) {
    const newRow = document.createElement("tr");
    newRow.setAttribute("data-order-id", order.order_id);

    newRow.innerHTML = `
      <td>${order.order_id}</td>
      <td>${order.order_date}</td>
      <td>${order.customer_name}</td>
      <td>${order.ordered_items}</td>
      <td>${order.quantity}</td>
      <td>₱${order.total_price}</td>
      <td>${order.order_source}</td>
      <td>
        <span class="status ${order.order_status.toLowerCase()}">${
      order.order_status
    }</span>
      </td>
      <td>${order.payment_method}</td>
      <td class="actions-cell">
        ${
          ["In-Transit", "Delivered", "Cancelled"].includes(order.order_status)
            ? '<span class="done-label">Done</span>'
            : `
          <button class="btn dispatchButton">Dispatch</button>
          <button class="btn cancelOrderButton">Cancel Order</button>
        `
        }
      </td>
    `;

    // Add event listeners for status changes
    const dispatchButton = newRow.querySelector(".dispatchButton");
    const cancelOrderButton = newRow.querySelector(".cancelOrderButton");

    if (dispatchButton) {
      dispatchButton.addEventListener("click", () => {
        showConfirmationDialog(
          "Are you sure you want to dispatch this order?",
          () => updateOrderStatus(order.order_id, "In-Transit", newRow)
        );
      });
    }

    if (cancelOrderButton) {
      cancelOrderButton.addEventListener("click", () => {
        showConfirmationDialog(
          "Are you sure you want to cancel this order?",
          () => updateOrderStatus(order.order_id, "Cancelled", newRow)
        );
      });
    }

    targetTable.appendChild(newRow);
  }

  // Function to update the order status in the database and move the row
  function updateOrderStatus(orderId, newStatus, row) {
    fetch("update_order_status.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ order_id: orderId, new_status: newStatus }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update the status in the row
          const statusCell = row.querySelector(".status");
          statusCell.textContent = newStatus;
          statusCell.className = `status ${newStatus.toLowerCase()}`;

          // Move the row to the appropriate table
          moveRowToTable(row, newStatus);

          // Remove action buttons and replace with "Done" label
          const actionsCell = row.querySelector(".actions-cell");
          actionsCell.innerHTML = '<span class="done-label">Done</span>';
        } else {
          alert("Failed to update order status.");
        }
      })
      .catch((error) => console.error("Error updating order status:", error));
  }

  // Function to move a row to the appropriate table based on its status
  function moveRowToTable(row, status) {
    if (status === "In-Transit") {
      inTransitTable.appendChild(row);
    } else if (status === "Cancelled") {
      cancelledTable.appendChild(row);
    } else if (status === "Delivered") {
      deliveredTable.appendChild(row);
    } else {
      ordersTable.appendChild(row);
    }
  }

  // Function to show a confirmation dialog
  function showConfirmationDialog(message, onConfirm) {
    const confirmationOverlay = document.createElement("div");
    confirmationOverlay.className = "confirmation-overlay";

    const confirmationBox = document.createElement("div");
    confirmationBox.className = "confirmation-box";

    confirmationBox.innerHTML = `
      <p>${message}</p>
      <div class="confirmation-buttons">
        <button class="btn confirmButton">Yes</button>
        <button class="btn cancelButton">No</button>
      </div>
    `;

    confirmationOverlay.appendChild(confirmationBox);
    document.body.appendChild(confirmationOverlay);

    // Handle confirmation
    confirmationBox
      .querySelector(".confirmButton")
      .addEventListener("click", () => {
        onConfirm();
        document.body.removeChild(confirmationOverlay);
      });

    // Handle cancellation
    confirmationBox
      .querySelector(".cancelButton")
      .addEventListener("click", () => {
        document.body.removeChild(confirmationOverlay);
      });
  }

  // Function to fetch orders and update the tables
  function fetchAndUpdateOrders() {
    fetch("fetch_orders.php")
      .then((response) => response.json())
      .then((data) => {
        const existingOrderIds = new Set(
          Array.from(document.querySelectorAll("tr[data-order-id]")).map(
            (row) => row.getAttribute("data-order-id")
          )
        );

        data.forEach((order) => {
          if (!existingOrderIds.has(order.order_id)) {
            // Fetch customer name using the ID
            fetch(`fetch_user.php?id=${order.ID}`)
              .then((response) => response.json())
              .then((user) => {
                const customerName = `${user.FirstName} ${user.LastName}`;
                const orderRowData = {
                  order_id: order.order_id,
                  order_date: order.order_date,
                  customer_name: customerName,
                  ordered_items: order.menu_orders,
                  quantity: order.quantity,
                  total_price: order.total_price,
                  order_source: order.order_source,
                  order_status: order.order_status,
                  payment_method: order.payment_method,
                };

                // Distribute orders to the respective tables based on their status
                if (order.order_status === "In-Transit") {
                  addOrderRow(orderRowData, inTransitTable);
                } else if (order.order_status === "Cancelled") {
                  addOrderRow(orderRowData, cancelledTable);
                } else if (order.order_status === "Delivered") {
                  addOrderRow(orderRowData, deliveredTable);
                } else {
                  addOrderRow(orderRowData, ordersTable);
                }
              })
              .catch((error) =>
                console.error("Error fetching user data:", error)
              );
          }
        });

        // Poll again after a delay
        setTimeout(fetchAndUpdateOrders, 5000); // Poll every 5 seconds
      })
      .catch((error) => {
        console.error("Error fetching orders:", error);
        setTimeout(fetchAndUpdateOrders, 5000); // Retry after 5 seconds
      });
  }

  // Function to show a fancy dialog
  function showFancyDialog(message) {
    const dialogOverlay = document.createElement("div");
    dialogOverlay.className = "dialog-overlay";

    const dialogBox = document.createElement("div");
    dialogBox.className = "dialog-box";

    dialogBox.innerHTML = `
      <p>${message}</p>
    `;

    dialogOverlay.appendChild(dialogBox);
    document.body.appendChild(dialogOverlay);

    // Automatically remove the dialog after 2 seconds
    setTimeout(() => {
      document.body.removeChild(dialogOverlay);
    }, 500);
  }

  // Add event listener to the refresh button
  const refreshOrdersButton = document.getElementById("refreshOrdersButton");
  if (refreshOrdersButton) {
    refreshOrdersButton.addEventListener("click", () => {
      fetchAndUpdateOrders(); // Refresh the orders
      showFancyDialog("Data is refreshed!"); // Show the fancy dialog
    });
  }

  // Start polling for updates
  fetchAndUpdateOrders();
});
