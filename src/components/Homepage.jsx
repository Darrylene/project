import React, { useEffect, useState } from "react";
import { Carousel } from "bootstrap";
import './HomePage.css';

export default function Homepage() {
  useEffect(() => {
    const coffeeCarousel = new Carousel("#carousel1", {
      interval: 3000,
      ride: "carousel",
    });

    const dessertCarousel = new Carousel("#carousel2", {
      interval: 3000,
      ride: "carousel",
    });

    const syncCarousels = () => {
      coffeeCarousel.next();
      dessertCarousel.next();
    };

    const interval = setInterval(syncCarousels, 3000);

    const carouselElement = document.querySelector("#imageSlider");
    const carouselInstance = Carousel.getOrCreateInstance(carouselElement);
    carouselElement?.addEventListener("slid.bs.carousel", () => {
      const items = carouselElement.querySelectorAll(".carousel-item");
      const activeItem = carouselElement.querySelector(".carousel-item.active");
      if (items[items.length - 1] === activeItem) {
        carouselInstance.pause();
      }
    });

    return () => clearInterval(interval);
  }, []);

  const handleLogout = () => {
    alert("You have been logged out!");
    window.location.href = "log in.html";
  };

  const toggleSidebar = () => {
    const sidebar = document.getElementById("sidebar");
    const isOpen = sidebar.style.left === "0px";
    sidebar.style.left = isOpen ? "-250px" : "0px";
    document.querySelector(".toggle-btn").setAttribute("aria-expanded", !isOpen);
  };

  function FeedbackSection() {
    const [selectedRating, setSelectedRating] = useState(0);
    const [hoverRating, setHoverRating] = useState(0);
    const [feedback, setFeedback] = useState("");
    const [anonymous, setAnonymous] = useState(false);

    const stars = [1, 2, 3, 4, 5];

    return (
      <div className="feedback-section mb-5">
        <h2>We value your opinion!</h2>
        <p>How would you rate your overall experience?</p>
        <div className="star-rating">
          {stars.map((star) => (
            <i
              key={star}
              className={`fa-star ${
                (hoverRating || selectedRating) >= star ? "fas text-warning" : "far"
              }`}
              onClick={() =>
                selectedRating === star ? setSelectedRating(0) : setSelectedRating(star)
              }
              onMouseOver={() => setHoverRating(star)}
              onMouseOut={() => setHoverRating(0)}
              style={{ cursor: "pointer", fontSize: "1.8rem", marginRight: 5 }}
              role="button"
              aria-label={`${star} star`}
              tabIndex={0}
              onKeyDown={(e) => {
                if (e.key === "Enter" || e.key === " ") {
                  selectedRating === star ? setSelectedRating(0) : setSelectedRating(star);
                }
              }}
            ></i>
          ))}
        </div>
        <p className="mt-2">Kindly take a moment to tell us what you think.</p>
        <textarea
          className="form-control mb-3"
          rows="4"
          placeholder="Share your feedback"
          value={feedback}
          onChange={(e) => setFeedback(e.target.value)}
        ></textarea>

        <div className="form-check mb-3">
          <input
            type="checkbox"
            id="anonymous"
            className="form-check-input"
            checked={anonymous}
            onChange={() => setAnonymous(!anonymous)}
          />
          <label htmlFor="anonymous" className="form-check-label">
            Submit anonymously
          </label>
        </div>

        <button
          className="btn btn-primary"
          onClick={() => {
            alert(
              `Rating: ${selectedRating}\nFeedback: ${feedback}\nAnonymous: ${anonymous}`
            );
            setSelectedRating(0);
            setFeedback("");
            setAnonymous(false);
          }}
        >
          Share your feedback
        </button>
      </div>
    );
  }

  function CommentSection() {
    const comments = [
      {
        img: "alice.png",
        name: "Alice Guo",
        rating: 5,
        comment:
          "Beautiful ambiance, fast delivery, and welcoming staff make this store a gem! Perfect waffles and lattes!",
      },
      {
        img: "wilkins.png",
        name: "Wilkins Custodio",
        rating: 5,
        comment:
          "Brew & Blend's cozy atmosphere and affordable prices make it my favorite place to visit!",
      },
      {
        img: "dem.png",
        name: "Demrose Gangan",
        rating: 5,
        comment:
          "The ambiance is cozy, and the staff is quick and friendly. Highly recommended!",
      },
    ];

    return (
      <div className="container comment-section py-5">
        <h2>Comment Section</h2>
        <div className="row">
          {comments.map(({ img, name, rating, comment }, i) => (
            <div
              key={i}
              className="col-md-4 mb-4 d-flex flex-column align-items-center"
            >
              <div className="comment-card w-100 border p-3 text-center">
                <img
                  src={`/images/${img}`}
                  alt={name}
                  className="rounded-circle mb-3"
                  style={{ width: 130, height: 130, objectFit: "cover" }}
                />
                <h4>{name}</h4>
                <div className="stars text-warning mb-2" style={{ fontSize: "1.2rem" }}>
                  {[...Array(rating)].map((_, i) => (
                    <i key={i} className="fas fa-star"></i>
                  ))}
                </div>
                <p>{comment}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  function WaveDivider() {
    return (
      <div className="wave">
        <svg
          viewBox="0 0 1440 320"
          preserveAspectRatio="none"
          style={{ width: "100%", height: 100 }}
        >
          <path
            fill="#8C785E"
            d="M0,160L60,186.7C120,213,240,267,360,277.3C480,288,600,256,720,245.3C840,235,960,245,1080,256C1200,267,1320,277,1380,282.7L1440,288V320H0Z"
          ></path>
        </svg>
      </div>
    );
  }

  function Footer({ handleLogout }) {
    return (
      <footer className="footer bg-dark text-white py-4">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-4 footer-logo text-start">
              <img
                src="/images/b&blogo.png"
                alt="Brew & Blend Logo"
                style={{ maxWidth: "150px" }}
              />
            </div>
            <div className="col-md-4 text-center">
              <p>
                <a href="#" className="text-white me-2">About Us</a> |{" "}
                <a href="#" className="text-white mx-2">Feedback</a> |{" "}
                <a href="#" className="text-white mx-2">Terms of Service</a> |{" "}
                <a href="#" className="text-white mx-2">FAQs</a> |{" "}
                <a href="#" className="text-white mx-2">Returns & Refunds</a> |{" "}
                <a href="#" className="text-white ms-2">Privacy Policy</a>
              </p>
            </div>
            <div className="col-md-4 text-end">
              <p>Follow us on:</p>
              <a href="https://www.facebook.com/" className="text-white me-3" target="_blank" rel="noreferrer" aria-label="Facebook"><i className="fab fa-facebook fa-lg"></i></a>
              <a href="https://www.instagram.com/" className="text-white me-3" target="_blank" rel="noreferrer" aria-label="Instagram"><i className="fab fa-instagram fa-lg"></i></a>
              <a href="https://www.tiktok.com/" className="text-white me-3" target="_blank" rel="noreferrer" aria-label="TikTok"><i className="fab fa-tiktok fa-lg"></i></a>
              <a href="https://twitter.com/i/moments" className="text-white" target="_blank" rel="noreferrer" aria-label="Twitter"><i className="fab fa-twitter fa-lg"></i></a>
            </div>
          </div>
          <button id="logoutBtn" onClick={handleLogout} className="btn btn-danger mt-3">Logout</button>
        </div>
      </footer>
    );
  }

  return (
    <div>
      <div className="spacer"></div>
      <div className="divider"></div>

      <button
        className="toggle-btn btn btn-secondary mb-3"
        onClick={toggleSidebar}
        aria-expanded="false"
        aria-controls="sidebar"
      >
        Toggle Sidebar
      </button>

      {/* Sidebar */}
      <nav
        id="sidebar"
        style={{
          position: "fixed",
          left: "-250px",
          top: 0,
          width: "250px",
          height: "100%",
          backgroundColor: "#ccc",
          transition: "left 0.3s ease",
          zIndex: 1000,
          padding: "1rem",
        }}
        aria-label="Sidebar navigation"
      >
        <h3>Sidebar</h3>
        <ul className="list-unstyled">
          <li><a href="#">Menu Item 1</a></li>
          <li><a href="#">Menu Item 2</a></li>
          <li><a href="#">Menu Item 3</a></li>
        </ul>
      </nav>

      {/* Coffee Carousel */}
      <div id="carousel1" className="carousel slide mb-4" data-bs-ride="carousel" aria-label="Coffee Carousel">
        <div className="carousel-inner">
          <div className="carousel-item active">
            <img src="/images/coffee1.jpg" className="d-block w-100" alt="Coffee 1" />
          </div>
          <div className="carousel-item">
            <img src="/images/coffee2.jpg" className="d-block w-100" alt="Coffee 2" />
          </div>
        </div>
      </div>

      {/* Dessert Carousel */}
      <div id="carousel2" className="carousel slide mb-4" data-bs-ride="carousel" aria-label="Dessert Carousel">
        <div className="carousel-inner">
          <div className="carousel-item active">
            <img src="/images/dessert1.jpg" className="d-block w-100" alt="Dessert 1" />
          </div>
          <div className="carousel-item">
            <img src="/images/dessert2.jpg" className="d-block w-100" alt="Dessert 2" />
          </div>
        </div>
      </div>

      {/* Main Image Slider */}
      <div id="imageSlider" className="carousel slide mb-4" data-bs-ride="carousel" aria-label="Image Slider">
        <div className="carousel-inner">
          <div className="carousel-item active">
            <img src="/images/image1.jpg" className="d-block w-100" alt="Image 1" />
          </div>
          <div className="carousel-item">
            <img src="/images/image2.jpg" className="d-block w-100" alt="Image 2" />
          </div>
        </div>
      </div>

      <FeedbackSection />
      <CommentSection />
      <WaveDivider />
      <Footer handleLogout={handleLogout} />
    </div>
  );
}
