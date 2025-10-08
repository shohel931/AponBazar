
// Side menu toggle
// Select elements
const menuBtn = document.querySelector(".mobile-menu");
const closeBtn = document.querySelector(".close-btn");
const sideMenu = document.getElementById("sideMenu");
const overlay = document.getElementById("overlay");

// Open sidebar
menuBtn.addEventListener("click", () => {
  sideMenu.classList.add("active");
  overlay.classList.add("active");
});

// Close sidebar
closeBtn.addEventListener("click", () => {
  sideMenu.classList.remove("active");
  overlay.classList.remove("active");
});

// Close sidebar when clicking overlay
overlay.addEventListener("click", () => {
  sideMenu.classList.remove("active");
  overlay.classList.remove("active");
});


// Search toggle
const searchToggle = document.querySelector(".search-toggle");
const dropdownSearch = document.getElementById("dropdownSearch");

// Toggle search bar visibility
searchToggle.addEventListener("click", () => {
  dropdownSearch.classList.toggle("active");
});
searchToggle.addEventListener("click", () => {
  slider.classList.toggle("moveTop");
});




//  Search 
// 🔍 Live Search AJAX
const searchInput = document.getElementById('searchInput');
const searchResult = document.getElementById('searchResult');

if (searchInput) {
  searchInput.addEventListener('keyup', () => {
    const query = searchInput.value.trim();
    if (query.length > 1) {
      fetch(`ajax-search.php?q=${encodeURIComponent(query)}`)
        .then(res => res.text())
        .then(data => {
          searchResult.innerHTML = data;
          searchResult.style.display = 'block';
        });
    } else {
      searchResult.style.display = 'none';
    }
  });

  document.addEventListener('click', (e) => {
    if (!searchResult.contains(e.target) && e.target !== searchInput) {
      searchResult.style.display = 'none';
    }
  });
}
