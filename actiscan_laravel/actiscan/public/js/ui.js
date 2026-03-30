document.addEventListener("DOMContentLoaded", () => {
    // Mobile sidebar toggle
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    if (menuToggle && sidebar && sidebarOverlay) {
        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            sidebarOverlay.classList.toggle("show");
        });

        sidebarOverlay.addEventListener("click", () => {
            sidebar.classList.remove("open");
            sidebarOverlay.classList.remove("show");
        });
    }

    // Dropdown "Crear" button
    const createMenuBtn = document.getElementById("createMenuBtn");
    const createMenu = document.getElementById("createMenuDropdown");
    if (createMenuBtn && createMenu) {
        createMenuBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            createMenu.classList.toggle("open");
        });
        document.addEventListener("click", () => createMenu.classList.remove("open"));
    }

    // Toggle groups: radio-like (only one active at a time)
    document.querySelectorAll("[data-toggle-group]").forEach((group) => {
        const options = group.querySelectorAll("[data-toggle-option]");
        options.forEach((option) => {
            option.addEventListener("click", () => {
                options.forEach((item) => item.classList.remove("active"));
                option.classList.add("active");
            });
        });
    });

    // Toggle independent: checkbox-like (each toggles on its own)
    document.querySelectorAll("[data-toggle-independent]").forEach((group) => {
        const options = group.querySelectorAll("[data-toggle-option]");
        options.forEach((option) => {
            option.addEventListener("click", () => {
                option.classList.toggle("active");
            });
        });
    });
});
