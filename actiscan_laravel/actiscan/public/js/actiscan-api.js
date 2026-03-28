(function () {
    const CONFIGURED_API_BASE = window.ACTISCAN_API_BASE || "/api-bridge";
    const page = document.body?.dataset?.page || "";
    let API_BASE = CONFIGURED_API_BASE;

    async function resolveApiBase() {
        if (CONFIGURED_API_BASE.startsWith("/")) {
            API_BASE = CONFIGURED_API_BASE.replace(/\/$/, "");
            return;
        }

        const hostname = window.location.hostname || "127.0.0.1";
        const candidates = [
            CONFIGURED_API_BASE,
            `http://${hostname}:8001/api`,
            "http://127.0.0.1:8001/api",
            "http://localhost:8001/api",
            `http://${hostname}:8000/api`,
            "http://127.0.0.1:8000/api",
            "http://localhost:8000/api",
        ].filter(Boolean);

        const uniqueCandidates = [...new Set(candidates)];

        for (const base of uniqueCandidates) {
            try {
                const normalizedBase = base.replace(/\/$/, "");
                const rootBase = normalizedBase.replace(/\/api$/, "");
                const response = await fetch(`${rootBase}/health`, { method: "GET" });
                if (response.ok) {
                    API_BASE = normalizedBase;
                    return;
                }
            } catch (_error) {
                // Try next candidate.
            }
        }
    }

    async function fetchJson(url, options) {
        let response;
        try {
            response = await fetch(url, options);
        } catch (_error) {
            throw new Error(
                "No se pudo conectar con la API. Verifica que FastAPI este ejecutandose y accesible."
            );
        }

        const contentType = response.headers.get("content-type") || "";
        const data = contentType.includes("application/json") ? await response.json() : null;

        if (!response.ok) {
            const detail = data && data.detail ? data.detail : "Error de comunicacion con la API";
            throw new Error(detail);
        }
        return data;
    }

    function escapeHtml(value) {
        return String(value ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;");
    }

    function getAssetIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const parsed = Number(params.get("id"));
        return Number.isFinite(parsed) && parsed > 0 ? parsed : 1;
    }

    async function loadLookups() {
        return fetchJson(`${API_BASE}/lookups`);
    }

    async function handleLoginPage() {
        const form = document.getElementById("loginForm");
        const message = document.getElementById("loginMessage");
        if (!form || !message) return;

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            message.textContent = "Validando credenciales...";

            const email = document.getElementById("usuario").value.trim();
            const password = document.getElementById("password").value;

            try {
                const payload = await fetchJson(`${API_BASE}/auth/login`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email, password }),
                });

                localStorage.setItem("actiscan_user", JSON.stringify(payload.user));
                message.textContent = "Inicio de sesion correcto. Redirigiendo...";
                window.location.href = "/capturist/dashboard";
            } catch (error) {
                message.textContent = error.message;
            }
        });
    }

    function renderAssetRows(items) {
        return items
            .map((asset) => {
                const isMaintenance = String(asset.estado).toLowerCase().includes("mantenimiento");
                const statusClass = isMaintenance ? "warning" : "success";
                return `
                    <tr>
                        <td>
                            <div class="asset-name">${escapeHtml(asset.nombre)}</div>
                            <div class="asset-code">${escapeHtml(asset.codigo_interno)}</div>
                        </td>
                        <td><span class="badge-soft">${escapeHtml(asset.categoria)}</span></td>
                        <td>
                            <span class="status-text ${statusClass}">
                                <span class="status-dot"></span>
                                ${escapeHtml(String(asset.estado).toUpperCase())}
                            </span>
                        </td>
                        <td>${escapeHtml(asset.ubicacion)}</td>
                        <td class="option-icons">
                            <a href="/capturist/assets/show?id=${asset.id}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                            <a href="/capturist/assets/qr?id=${asset.id}" title="Generar QR"><i class="fa-solid fa-qrcode"></i></a>
                        </td>
                    </tr>
                `;
            })
            .join("");
    }

    async function handleDashboardPage() {
        const totalNode = document.getElementById("dashboardTotalAssets");
        if (!totalNode) return;

        const obsNode = document.getElementById("dashboardAssetsObs");
        const catNode = document.getElementById("dashboardCategories");
        const tableBody = document.getElementById("dashboardAssetsTableBody");
        const searchInput = document.getElementById("dashboardSearchInput");
        const categoryFilter = document.getElementById("dashboardCategoryFilter");
        const statusFilter = document.getElementById("dashboardStatusFilter");
        const applyFilterBtn = document.getElementById("dashboardApplyFilter");

        const summary = await fetchJson(`${API_BASE}/dashboard/summary`);
        totalNode.textContent = summary.stats.total_activos;
        obsNode.textContent = summary.stats.activos_con_observaciones;
        catNode.textContent = summary.stats.categorias_activas;
        tableBody.innerHTML = renderAssetRows(summary.recent_assets);

        const lookups = await loadLookups();
        lookups.categories.forEach((item) => {
            categoryFilter.insertAdjacentHTML("beforeend", `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`);
        });
        lookups.statuses.forEach((item) => {
            statusFilter.insertAdjacentHTML("beforeend", `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`);
        });

        applyFilterBtn.addEventListener("click", async () => {
            const params = new URLSearchParams();
            if (searchInput.value.trim()) params.set("q", searchInput.value.trim());
            if (categoryFilter.value) params.set("category_id", categoryFilter.value);
            if (statusFilter.value) params.set("status_id", statusFilter.value);

            const assets = await fetchJson(`${API_BASE}/assets?${params.toString()}`);
            tableBody.innerHTML = renderAssetRows(assets);
        });
    }

    async function handleAssetsPage() {
        const tableBody = document.getElementById("assetsTableBody");
        if (!tableBody) return;

        const searchInput = document.getElementById("assetsSearchInput");
        const searchButton = document.getElementById("assetsSearchButton");

        async function loadAssets() {
            const query = searchInput.value.trim();
            const params = query ? `?q=${encodeURIComponent(query)}` : "";
            const assets = await fetchJson(`${API_BASE}/assets${params}`);
            tableBody.innerHTML = assets.length ? renderAssetRows(assets) : "<tr><td colspan='5'>Sin resultados</td></tr>";
        }

        await loadAssets();
        searchButton.addEventListener("click", loadAssets);
    }

    async function handleCategoriesPage() {
        const tableBody = document.getElementById("categoriesTableBody");
        if (!tableBody) return;

        const searchInput = document.getElementById("categoriesSearchInput");
        const searchButton = document.getElementById("categoriesSearchButton");

        async function loadCategories() {
            const rows = await fetchJson(`${API_BASE}/categories`);
            const q = searchInput.value.trim().toLowerCase();
            const filtered = q
                ? rows.filter((row) => String(row.nombre).toLowerCase().includes(q) || String(row.descripcion || "").toLowerCase().includes(q))
                : rows;

            tableBody.innerHTML = filtered
                .map(
                    (row) => `
                        <tr>
                            <td><i class="fa-solid fa-layer-group"></i></td>
                            <td><span class="badge-soft">${escapeHtml(row.nombre)}</span></td>
                            <td>${escapeHtml(row.descripcion || "-")}</td>
                            <td>${row.total_activos}</td>
                            <td class="option-icons">
                                <a href="/capturist/categories/create" title="Editar categoria"><i class="fa-regular fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                    `
                )
                .join("");

            if (!filtered.length) {
                tableBody.innerHTML = "<tr><td colspan='5'>Sin categorias registradas</td></tr>";
            }
        }

        await loadCategories();
        searchButton.addEventListener("click", loadCategories);
    }

    async function handleCreateCategoryPage() {
        const form = document.getElementById("createCategoryForm");
        if (!form) return;

        const nameInput = document.getElementById("category_name");
        const descriptionInput = document.getElementById("category_description");
        const message = document.getElementById("createCategoryMessage");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            message.textContent = "Guardando categoria...";

            try {
                await fetchJson(`${API_BASE}/categories`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        nombre: nameInput.value.trim(),
                        descripcion: descriptionInput.value.trim() || null,
                    }),
                });

                window.location.href = "/capturist/categories";
            } catch (error) {
                message.textContent = error.message;
            }
        });
    }

    async function handleCreateAssetPage() {
        const form = document.getElementById("createAssetForm");
        if (!form) return;

        const categorySelect = document.getElementById("asset_category");
        const statusSelect = document.getElementById("asset_status");
        const locationSelect = document.getElementById("asset_location");
        const message = document.getElementById("createAssetMessage");

        const lookups = await loadLookups();
        lookups.categories.forEach((item) => {
            categorySelect.insertAdjacentHTML("beforeend", `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`);
        });
        lookups.statuses.forEach((item) => {
            statusSelect.insertAdjacentHTML("beforeend", `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`);
        });
        lookups.locations.forEach((item) => {
            locationSelect.insertAdjacentHTML("beforeend", `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`);
        });

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            message.textContent = "Guardando activo...";

            try {
                await fetchJson(`${API_BASE}/assets`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        nombre: document.getElementById("asset_name").value.trim(),
                        marca: document.getElementById("asset_brand").value || null,
                        modelo: null,
                        numero_serie: document.getElementById("asset_serial").value.trim() || null,
                        codigo_interno: null,
                        descripcion: null,
                        id_categoria: Number(categorySelect.value),
                        id_estado_activo: Number(statusSelect.value),
                        id_ubicacion: Number(locationSelect.value),
                        id_usuario: null,
                    }),
                });

                window.location.href = "/capturist/assets";
            } catch (error) {
                message.textContent = error.message;
            }
        });
    }

    async function handleShowAssetPage() {
        const detailNode = document.getElementById("assetDetailList");
        if (!detailNode) return;

        const assetId = getAssetIdFromUrl();
        const data = await fetchJson(`${API_BASE}/assets/${assetId}`);

        detailNode.innerHTML = `
            <div class="detail-item"><strong>Nombre:</strong> ${escapeHtml(data.nombre)}</div>
            <div class="detail-item"><strong>No. serial:</strong> ${escapeHtml(data.numero_serie || "-")}</div>
            <div class="detail-item"><strong>Marca:</strong> ${escapeHtml(data.marca || "-")}</div>
            <div class="detail-item"><strong>Categoria:</strong> ${escapeHtml(data.categoria)}</div>
            <div class="detail-item"><strong>Estado:</strong> ${escapeHtml(data.estado)}</div>
            <div class="detail-item"><strong>Ubicacion:</strong> ${escapeHtml(data.ubicacion)}</div>
        `;

        document.getElementById("assetQrLink").setAttribute("href", `/capturist/assets/qr?id=${assetId}`);
        document.getElementById("assetPreviewTitle").textContent = data.nombre;
        document.getElementById("assetPreviewSubtitle").textContent = data.codigo_interno;
    }

    async function handleGenerateQrPage() {
        const button = document.getElementById("generateQrButton");
        if (!button) return;

        const message = document.getElementById("generateQrMessage");
        const assetId = getAssetIdFromUrl();
        const backLink = document.querySelector(".page-toolbar .ghost-button");
        if (backLink) {
            backLink.setAttribute("href", `/capturist/assets/show?id=${assetId}`);
        }
        const data = await fetchJson(`${API_BASE}/assets/${assetId}`);

        document.getElementById("qrPreviewName").textContent = data.nombre.toUpperCase();
        document.getElementById("qrPreviewCode").textContent = data.codigo_interno;
        document.getElementById("qrPreviewMeta").textContent = `SN: ${data.numero_serie || "-"}   LOC: ${data.ubicacion}`;

        button.addEventListener("click", async () => {
            message.textContent = "Generando QR...";
            try {
                const qrData = await fetchJson(`${API_BASE}/assets/${assetId}/qr`, { method: "POST" });
                message.textContent = `QR generado: ${qrData.qr}`;
            } catch (error) {
                message.textContent = error.message;
            }
        });
    }

    const handlers = {
        login: handleLoginPage,
        dashboard: handleDashboardPage,
        assets: handleAssetsPage,
        categories: handleCategoriesPage,
        "create-category": handleCreateCategoryPage,
        "create-asset": handleCreateAssetPage,
        "show-asset": handleShowAssetPage,
        "generate-qr": handleGenerateQrPage,
    };

    if (handlers[page]) {
        resolveApiBase()
            .then(() => handlers[page]())
            .catch((error) => {
            console.error(error);
            });
    }
})();
