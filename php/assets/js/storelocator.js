document.querySelectorAll(".location-option").forEach((item) => {
  item.addEventListener("click", function () {
    const mapLink = this.getAttribute("data-map");
    const mapIframe = document.getElementById("google-map-iframe");
    if (mapIframe) {
      mapIframe.src = mapLink;
    } else {
      console.error(
        "Element with ID 'google-map-iframe' not found in the DOM."
      );
    }
  });
});

// Function to filter the location list based on search input
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("location-search");
  const locationList = document.querySelectorAll(".location-option");

  // Check if the search input exists
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const filter = searchInput.value.toLowerCase();

      locationList.forEach((location) => {
        const locationName = location.getAttribute("data-name").toLowerCase();

        if (locationName.includes(filter)) {
          location.style.display = "";
        } else {
          location.style.display = "none";
        }
      });
    });
  } else {
    console.warn("Element with ID 'location-search' not found in the DOM.");
  }

  const findNearbyButton = document.querySelector(
    '.circular-button ion-icon[name="navigate"]'
  )?.parentElement;

  // Check if the "Find Nearby" button exists
  if (findNearbyButton) {
    findNearbyButton.addEventListener("click", () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    });
  } else {
    console.warn("Find Nearby button not found in the DOM.");
  }

  function showPosition(position) {
    const userLatitude = position.coords.latitude;
    const userLongitude = position.coords.longitude;

    locationList.forEach((location) => {
      const mapLink = location.getAttribute("data-map");
      if (mapLink) {
        const url = new URL(mapLink);
        const searchParams = new URLSearchParams(url.search);
        const locationLatitude = parseFloat(searchParams.get("3d"));
        const locationLongitude = parseFloat(searchParams.get("3d"));

        const distance = calculateDistance(
          userLatitude,
          userLongitude,
          locationLatitude,
          locationLongitude
        );
        if (distance <= 10) {
          // Filter for locations within 10 km
          location.style.display = "";
        } else {
          location.style.display = "none";
        }
      }
    });
  }

  function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in km
    const dLat = (lat2 - lat1) * (Math.PI / 180);
    const dLon = (lon2 - lon1) * (Math.PI / 180);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(lat1 * (Math.PI / 180)) *
        Math.cos(lat2 * (Math.PI / 180)) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distance in km
    return distance;
  }

  // Modal-related code
  const messageInquiryModal = document.getElementById("messageInquiryModal");
  const openModalButton = document.querySelector(
    ".left-options li:nth-child(3)"
  ); // Adjust selector as needed
  const closeModalButton = document.querySelector(".modal .close");
  const cancelButton = document.getElementById("cancelButton");
  const messageInquiryForm = document.getElementById("messageInquiryForm");
  const dateInput = document.getElementById("date");

  // Check if the modal-related elements exist
  if (
    messageInquiryModal &&
    openModalButton &&
    closeModalButton &&
    cancelButton &&
    messageInquiryForm
  ) {
    openModalButton.addEventListener("click", () => {
      messageInquiryModal.style.display = "block";
      const today = new Date().toISOString().split("T")[0];
      if (dateInput) {
        dateInput.value = today; // Set the current date in the date input
      }
    });

    closeModalButton.addEventListener("click", () => {
      messageInquiryModal.style.display = "none";
    });

    cancelButton.addEventListener("click", () => {
      messageInquiryModal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
      if (event.target == messageInquiryModal) {
        messageInquiryModal.style.display = "none";
      }
    });

    messageInquiryForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const formData = new FormData(messageInquiryForm);
      const fullName = formData.get("fullName");
      const email = formData.get("email");
      const message = formData.get("message");
      const date = formData.get("date");

      const inquiries = JSON.parse(localStorage.getItem("inquiries")) || [];
      inquiries.push({ fullName, email, message, date });
      localStorage.setItem("inquiries", JSON.stringify(inquiries));

      messageInquiryModal.style.display = "none";
      messageInquiryForm.reset();
      loadInquiries();
    });
  } else {
    console.warn("One or more modal-related elements not found in the DOM.");
  }

  const someElement = document.getElementById("someElementId"); // Replace with the actual element ID
  if (someElement) {
    someElement.addEventListener("click", () => {
      // ...existing code...
    });
  } else {
    console.warn("Element with ID 'someElementId' not found in the DOM.");
  }
});
