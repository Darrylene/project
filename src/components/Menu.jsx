import React, { useState, useEffect } from "react";
import axios from "axios";
import "./menu.css";

const categories = [
  { id: "all", name: "All", img: "espresso.png" },
  { id: "1", name: "Hot Coffee", img: "caffèmericano.png" },
  { id: "2", name: "Iced Coffee", img: "ice.png" },
  { id: "3", name: "Frappé", img: "frappe.png" },
  { id: "4", name: "Milkshake", img: "milkshake.png" },
  { id: "5", name: "Tea", img: "tea.png" },
  { id: "6", name: "Hot Choco", img: "choco.png" },
  { id: "7", name: "Dessert", img: "dessert.png" },
];

export default function Menu() {
  const [items, setItems] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");

  useEffect(() => {
    axios
      .get("http://localhost/your-folder/menu_api.php", { withCredentials: true })
      .then((res) => {
        setItems(res.data);
      })
      .catch((err) => console.error("API Error:", err));
  }, []);

  const filteredItems = items.filter((item) => {
    const matchCategory = selectedCategory === "all" || item.menu_category_id === selectedCategory;
    const matchSearch = item.menu_item_name.toLowerCase().includes(searchQuery.toLowerCase());
    return matchCategory && matchSearch;
  });

  const redirectTo = (item) => {
    const page = item.menu_category_id === "7" ? "dessert" : "drink";
    window.location.href = `http://localhost/your-folder/${page}.php?id=${item.menu_item_id}`;
  };

  return (
    <div className="container mt-4">
      <a href="homepage.php" className="back-button">
        <i className="bi bi-arrow-left"></i>
      </a>

      <div className="d-flex mb-3">
        <input
          type="text"
          className="form-control me-2 search-input"
          placeholder="Search..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
        />
        <button className="btn custom-search-button">Search</button>
      </div>

      <div className="d-flex justify-content-center mb-4">
        {categories.map((cat) => (
          <button
            key={cat.id}
            className={`category-button ${selectedCategory === cat.id ? "active" : ""} ${
              cat.id === "all" && selectedCategory === "all" ? "default" : ""
            }`}
            onClick={() => setSelectedCategory(cat.id)}
          >
            <img src={`/images/${cat.img}`} alt={cat.name} />
            <p>{cat.name}</p>
          </button>
        ))}
      </div>

      <div className="menu-container">
        <div className="row row-cols-2 row-cols-md-5 menu-row" id="menu">
          {filteredItems.map((item) => (
            <button
              key={item.menu_item_id}
              className="menu-button col menu-item"
              onClick={() => redirectTo(item)}
            >
              <img
                src={`/images/${item.image_url}`}
                alt={item.menu_item_name}
                className="img-fluid"
                style={{ width: "130px", height: "130px" }}
              />
              <p>{item.menu_item_name}</p>
              <p className="menu-price">₱{item.price}</p>
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
