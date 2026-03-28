document.addEventListener("DOMContentLoaded", () => {
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

    document.querySelectorAll("[data-toggle-group]").forEach((group) => {
        const options = group.querySelectorAll("[data-toggle-option]");

        options.forEach((option) => {
            option.addEventListener("click", () => {
                options.forEach((item) => item.classList.remove("active"));
                option.classList.add("active");
            });
        });
    });
});
