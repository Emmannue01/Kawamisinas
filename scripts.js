document.addEventListener('DOMContentLoaded', function() {
    const sections = [
        { id: 'cervezas-list', items: menuItems.cervezas },
        { id: 'caguamas-list', items: menuItems.caguamas },
        { id: 'micheladas-list', items: menuItems.micheladas },
        { id: 'azulitos-list', items: menuItems.azulitos },
        { id: 'cantaritos-list', items: menuItems.cantaritos },
        {id : 'refrescos-list', items: menuItems.refrescos},
        { id: 'comida-list', items: menuItems.comida }

    ];

    sections.forEach(section => {
        const list = document.getElementById(section.id);
        section.items.forEach(item => {
            const li = document.createElement('li');
            if (item.type) {
                li.textContent = `${item.name} - $${item.price.toFixed(2)}`;
            } else {
                li.textContent = `${item.name} - $${item.price.toFixed(2)}`;
            }
            list.appendChild(li);
        });
    });
});

