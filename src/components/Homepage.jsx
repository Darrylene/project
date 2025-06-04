import React, { useEffect, useState } from "react";
import "./Homepage.css";

export default function Homepage() {
  const [selectedRating, setSelectedRating] = useState(0);
  const [hoverRating, setHoverRating] = useState(0);
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [carousel1, setCarousel1] = useState(null);
  const [carousel2, setCarousel2] = useState(null);

  const stars = [1, 2, 3, 4, 5];

  const handleStarClick = (index) => {
    setSelectedRating(selectedRating === index ? 0 : index);
  };

  const toggleSidebar = () => {
    setSidebarOpen((prev) => !prev);
  };

  // ✅ UPDATED LOGOUT FUNCTION
  const handleLogout = () => {
    fetch("http://localhost/yourproject/logout.php", {
      method: "POST",
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          alert("You have been logged out!");
          window.location.href = "log in.html"; // update path kung ibang filename
        } else {
          alert("Logout failed.");
        }
      })
      .catch(() => {
        alert("Logout failed due to network error.");
      });
  };

  // ✅ Sync carousel
  useEffect(() => {
    if (window.bootstrap) {
      const coffee = new window.bootstrap.Carousel("#carousel1", {
        interval: 3000,
        ride: "carousel",
      });
      const dessert = new window.bootstrap.Carousel("#carousel2", {
        interval: 3000,
        ride: "carousel",
      });
      setCarousel1(coffee);
      setCarousel2(dessert);

      const syncInterval = setInterval(() => {
        coffee.next();
        dessert.next();
      }, 3000);

      return () => clearInterval(syncInterval);
    }
  }, []);

  // ✅ Pause imageSlider on last image (if used)
  useEffect(() => {
    const carouselElement = document.querySelector("#imageSlider");
    if (!carouselElement || !window.bootstrap) return;

    const carouselInstance = window.bootstrap.Carousel.getOrCreateInstance(carouselElement);
    const handleSlide = () => {
      const items = carouselElement.querySelectorAll(".carousel-item");
      const activeItem = carouselElement.querySelector(".carousel-item.active");
      if (items[items.length - 1] === activeItem) {
        carouselInstance.pause();
      }
    };

    carouselElement.addEventListener("slid.bs.carousel", handleSlide);
    return () => carouselElement.removeEventListener("slid.bs.carousel", handleSlide);
  }, []);

  // ✅ Fetch data from PHP on page load (if needed)
  useEffect(() => {
    fetch("http://localhost/yourproject/homepage.php", {
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => {
        console.log("Data from backend:", data);
      })
      .catch((err) => {
        console.error("Error fetching PHP data:", err);
      });
  }, []);

  return (
    <>
      {/* Sidebar */}
      <div
        id="sidebar"
        style={{
          left: sidebarOpen ? "0px" : "-250px",
          transition: "left 0.3s",
          position: "fixed",
          top: 0,
          bottom: 0,
          width: "250px",
          backgroundColor: "#333",
          color: "#fff",
          zIndex: 1000,
        }}
      >
        <button className="toggle-btn" onClick={toggleSidebar} style={{ margin: "10px" }}>
          {sidebarOpen ? "Close Sidebar" : "Open Sidebar"}
        </button>
        {/* Add your sidebar items here */}
      </div>

      {/* Main content */}
      <div className="main-content" style={{ marginLeft: sidebarOpen ? "250px" : "0", transition: "margin-left 0.3s" }}>
        {/* Star Rating */}
        <div className="star-rating">
          {stars.map((star) => (
            <span
              key={star}
              className={`star ${star <= (hoverRating || selectedRating) ? "highlight" : ""} ${star <= selectedRating ? "selected" : ""}`}
              onMouseEnter={() => setHoverRating(star)}
              onMouseLeave={() => setHoverRating(0)}
              onClick={() => handleStarClick(star)}
              style={{ cursor: "pointer", fontSize: "24px", color: "#ffb400" }}
            >
              &#9733;
            </span>
          ))}
        </div>

        {/* Carousel 1 */}
        <div id="carousel1" className="carousel slide" data-bs-ride="carousel" style={{ maxWidth: "600px", margin: "20px auto" }}>
          <div className="carousel-inner">
            <div className="carousel-item active">
              <img src="coffee1.jpg" className="d-block w-100" alt="Coffee 1" />
            </div>
            <div className="carousel-item">
              <img src="coffee2.jpg" className="d-block w-100" alt="Coffee 2" />
            </div>
          </div>
        </div>

        {/* Carousel 2 */}
        <div id="carousel2" className="carousel slide" data-bs-ride="carousel" style={{ maxWidth: "600px", margin: "20px auto" }}>
          <div className="carousel-inner">
            <div className="carousel-item active">
              <img src="dessert1.jpg" className="d-block w-100" alt="Dessert 1" />
            </div>
            <div className="carousel-item">
              <img src="dessert2.jpg" className="d-block w-100" alt="Dessert 2" />
            </div>
          </div>
        </div>

        {/* ✅ Logout Button */}
        <button id="logoutBtn" onClick={handleLogout} className="btn btn-danger">
          Logout
        </button>
      </div>
    </>
  );
}
