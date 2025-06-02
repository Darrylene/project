const categories = document.querySelectorAll('.category-button');
        const menuItems = document.querySelectorAll('.menu-item');

        categories.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');

                menuItems.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        document.getElementById('search').addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();

            menuItems.forEach(item => {
                const text = item.querySelector('p').textContent.toLowerCase();
                item.style.display = text.includes(query) ? 'block' : 'none';
            });
        });

        const categoryButtons = document.querySelectorAll('.category-button');
        
        // Add click event listener to each button
        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                // Remove 'active' class from all buttons
                categoryButtons.forEach((btn) => btn.classList.remove('active', 'default'));
        
                // Add 'active' class to the clicked button
                button.classList.add('active');
        
                // If the clicked button is not "All", reset "All" button's color
                if (button.dataset.category !== 'all') {
                    const allButton = document.querySelector('.category-button[data-category="all"]');
                    allButton.classList.remove('active');
                }
            });
        });
        
        // Set "All" button as the default active button on load
        document.querySelector('.category-button[data-category="all"]').classList.add('default', 'active');
        