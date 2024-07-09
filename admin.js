document.addEventListener('DOMContentLoaded', () => {
    const categorySelect = document.getElementById('category');
    const beverageSelect = document.getElementById('beverage');

    categorySelect.addEventListener('change', () => {
        const selectedCategory = categorySelect.value;
        populateBeverages(selectedCategory, beverageSelect);
    });

    beverageSelect.addEventListener('change', () => {
        const selectedCategory = categorySelect.value;
        const selectedBeverage = beverageSelect.value;
        const priceInput = document.getElementById('price');
        const selectedItem = menuItems[selectedCategory].find(item => item.name === selectedBeverage);
        if (selectedItem) {
            priceInput.value = selectedItem.price;
        }
    });
});

function populateBeverages(category, element) {
    if (menuItems[category]) {
        menuItems[category].forEach(item => {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = `${item.name} - $${item.price.toFixed(2)}`;
            element.appendChild(option);
        });
    }
}
