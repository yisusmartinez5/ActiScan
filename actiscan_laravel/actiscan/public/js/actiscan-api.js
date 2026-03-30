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

    function getAssetPhotoDataUrl(asset) {
        const raw = asset && asset.foto_base64 ? String(asset.foto_base64) : "";
        if (!raw) return null;
        if (raw.startsWith("data:image")) return raw;
        return `data:image/png;base64,${raw}`;
    }

    function showToast(message, type) {
        const toast = document.getElementById("actiscan-toast");
        if (!toast) return;
        toast.textContent = message;
        toast.style.background =
            type === "success" ? "#57d163" :
            type === "error" ? "#ff6b6b" : "#4a90e2";
        toast.style.color = "#fff";
        toast.classList.add("show");
        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(() => toast.classList.remove("show"), 2600);
    }

    function getAssetIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const parsed = Number(params.get("id"));
        return Number.isFinite(parsed) && parsed > 0 ? parsed : null;
    }

    function blobToDataUrl(blob) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = () => reject(new Error("No se pudo procesar la imagen del QR."));
            reader.readAsDataURL(blob);
        });
    }

    async function downloadQrPdf(assetId, assetData) {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            throw new Error("La libreria de PDF no esta disponible.");
        }

        const qrImageResponse = await fetch(`${API_BASE}/assets/${assetId}/qr/latest/image`);
        if (!qrImageResponse.ok) {
            throw new Error("No se pudo obtener la imagen del QR para exportar.");
        }

        const qrBlob = await qrImageResponse.blob();
        const qrDataUrl = await blobToDataUrl(qrBlob);

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

        const title = "Etiqueta QR de Activo";
        const assetName = assetData?.nombre || "Activo";
        const assetCode = assetData?.codigo_interno || `ID-${assetId}`;
        const serial = assetData?.numero_serie || "-";
        const location = assetData?.ubicacion || "-";

        pdf.setFont("helvetica", "bold");
        pdf.setFontSize(18);
        pdf.text(title, 20, 20);

        pdf.setFont("helvetica", "normal");
        pdf.setFontSize(11);
        pdf.text(`Nombre: ${assetName}`, 20, 32);
        pdf.text(`Codigo: ${assetCode}`, 20, 39);
        pdf.text(`Serie: ${serial}`, 20, 46);
        pdf.text(`Ubicacion: ${location}`, 20, 53);

        pdf.addImage(qrDataUrl, "PNG", 55, 66, 100, 100);
        pdf.setFontSize(9);
        pdf.text("ActiScan - Etiqueta generada automaticamente", 20, 176);

        const safeCode = String(assetCode).replace(/[^a-zA-Z0-9_-]+/g, "_");
        pdf.save(`QR_${safeCode}.pdf`);
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
        if (!items || !items.length) {
            return "<tr><td colspan='5'>Sin activos registrados</td></tr>";
        }
        return items
            .map((asset) => {
                const isMaintenance = String(asset.estado).toLowerCase().includes("mantenimiento");
                const statusClass = isMaintenance ? "warning" : "success";
                const photoUrl = getAssetPhotoDataUrl(asset);
                return `
                    <tr>
                        <td>
                            <div class="asset-cell-with-photo">
                                ${photoUrl
                                    ? `<img class="asset-thumb" src="${escapeHtml(photoUrl)}" alt="Foto de ${escapeHtml(asset.nombre || "activo")}">`
                                    : '<div class="asset-thumb asset-thumb-empty"><i class="fa-regular fa-image"></i></div>'}
                                <div>
                                    <div class="asset-name">${escapeHtml(asset.nombre)}</div>
                                    <div class="asset-code">${escapeHtml(asset.codigo_interno || "")}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-soft">${escapeHtml(asset.categoria || "-")}</span></td>
                        <td>
                            <span class="status-text ${statusClass}">
                                <span class="status-dot"></span>
                                ${escapeHtml(String(asset.estado || "").toUpperCase())}
                            </span>
                        </td>
                        <td>${escapeHtml(asset.ubicacion || "-")}</td>
                        <td class="option-icons">
                            <a href="/capturist/assets/show?id=${asset.id}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                            <a href="/capturist/assets/qr?id=${asset.id}" title="Generar QR"><i class="fa-solid fa-qrcode"></i></a>
                            <a href="/capturist/assets/create?id=${asset.id}" title="Editar activo"><i class="fa-regular fa-pen-to-square"></i></a>
                            <button type="button" class="icon-btn" data-action="delete-asset" data-id="${asset.id}" title="Eliminar activo"><i class="fa-regular fa-trash-can"></i></button>
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

        try {
            const summary = await fetchJson(`${API_BASE}/dashboard/summary`);
            totalNode.textContent = summary.stats.total_activos ?? 0;
            if (obsNode) obsNode.textContent = summary.stats.activos_con_observaciones ?? 0;
            if (catNode) catNode.textContent = summary.stats.categorias_activas ?? 0;
            tableBody.innerHTML = renderAssetRows(summary.recent_assets || []);
        } catch (error) {
            tableBody.innerHTML = `<tr><td colspan='5'>${escapeHtml(error.message)}</td></tr>`;
        }

        try {
            const lookups = await loadLookups();
            lookups.categories.forEach((item) => {
                categoryFilter.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`
                );
            });
            lookups.statuses.forEach((item) => {
                statusFilter.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${item.id}">${escapeHtml(item.nombre)}</option>`
                );
            });
        } catch (_e) {
            // Silently ignore lookup errors
        }

        if (applyFilterBtn) {
            applyFilterBtn.addEventListener("click", async () => {
                const params = new URLSearchParams();
                if (searchInput.value.trim()) params.set("q", searchInput.value.trim());
                if (categoryFilter.value) params.set("category_id", categoryFilter.value);
                if (statusFilter.value) params.set("status_id", statusFilter.value);

                try {
                    const assets = await fetchJson(`${API_BASE}/assets?${params.toString()}`);
                    tableBody.innerHTML = renderAssetRows(assets);
                    totalNode.textContent = assets.length;
                } catch (error) {
                    tableBody.innerHTML = `<tr><td colspan='5'>${escapeHtml(error.message)}</td></tr>`;
                }
            });
        }
    }

    async function handleAssetsPage() {
        const tableBody = document.getElementById("assetsTableBody");
        if (!tableBody) return;

        const searchInput = document.getElementById("assetsSearchInput");
        const searchButton = document.getElementById("assetsSearchButton");

        async function loadAssets() {
            try {
                const query = searchInput.value.trim();
                const params = query ? `?q=${encodeURIComponent(query)}` : "";
                const assets = await fetchJson(`${API_BASE}/assets${params}`);
                tableBody.innerHTML = renderAssetRows(assets);
            } catch (error) {
                tableBody.innerHTML = `<tr><td colspan='5'>${escapeHtml(error.message)}</td></tr>`;
            }
        }

        if (!tableBody.dataset.deleteBound) {
            tableBody.addEventListener("click", async (event) => {
                const btn = event.target.closest("[data-action='delete-asset']");
                if (!btn) return;
                const assetId = btn.dataset.id;
                if (!assetId) return;
                if (!window.confirm("Eliminar este activo? Esta accion no se puede deshacer.")) return;
                try {
                    await fetchJson(`${API_BASE}/assets/${assetId}`, { method: "DELETE" });
                    showToast("Activo eliminado.", "success");
                    await loadAssets();
                } catch (error) {
                    showToast(error.message, "error");
                }
            });
            tableBody.dataset.deleteBound = "1";
        }

        await loadAssets();
        if (searchButton) searchButton.addEventListener("click", loadAssets);
        if (searchInput) {
            searchInput.addEventListener("keydown", (e) => {
                if (e.key === "Enter") loadAssets();
            });
        }
    }

    async function handleCategoriesPage() {
        const tableBody = document.getElementById("categoriesTableBody");
        if (!tableBody) return;

        const searchInput = document.getElementById("categoriesSearchInput");
        const searchButton = document.getElementById("categoriesSearchButton");

        async function loadCategories() {
            try {
                const rows = await fetchJson(`${API_BASE}/categories`);
                const q = searchInput ? searchInput.value.trim().toLowerCase() : "";
                const filtered = q
                    ? rows.filter(
                          (row) =>
                              String(row.nombre).toLowerCase().includes(q) ||
                              String(row.descripcion || "").toLowerCase().includes(q)
                      )
                    : rows;

                if (!filtered.length) {
                    tableBody.innerHTML = "<tr><td colspan='5'>Sin categorias registradas</td></tr>";
                    return;
                }

                tableBody.innerHTML = filtered
                    .map((row) => {
                        const iconClass = escapeHtml(row.icono || "fa-solid fa-layer-group");
                        const color = escapeHtml(row.color || "#4a90e2");
                        return `
                            <tr>
                                <td><i class="${iconClass}" style="color:${color}; font-size:1.2rem;"></i></td>
                                <td><span class="badge-soft" style="border-left: 3px solid ${color};">${escapeHtml(row.nombre)}</span></td>
                                <td>${escapeHtml(row.descripcion || "-")}</td>
                                <td>${row.total_activos ?? 0}</td>
                                <td class="option-icons">
                                    <a href="/capturist/categories/create?id=${row.id}" title="Editar categoria"><i class="fa-regular fa-pen-to-square"></i></a>
                                </td>
                            </tr>
                        `;
                    })
                    .join("");
            } catch (error) {
                tableBody.innerHTML = `<tr><td colspan='5'>${escapeHtml(error.message)}</td></tr>`;
            }
        }

        await loadCategories();
        if (searchButton) searchButton.addEventListener("click", loadCategories);
        if (searchInput) {
            searchInput.addEventListener("keydown", (e) => {
                if (e.key === "Enter") loadCategories();
            });
        }
    }

    async function handleCreateCategoryPage() {
        const form = document.getElementById("createCategoryForm");
        if (!form) return;

        const nameInput = document.getElementById("category_name");
        const descriptionInput = document.getElementById("category_description");
        const message = document.getElementById("createCategoryMessage");
        const previewIconEl = document.getElementById("categoryPreviewIcon");
        const previewIconBox = document.getElementById("categoryPreviewIconBox");
        const previewName = document.getElementById("categoryPreviewName");
        const previewDesc = document.getElementById("categoryPreviewDesc");
        const pageTitle = document.getElementById("createCategoryTitle");
        const submitBtn = document.getElementById("createCategorySubmitBtn");

        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get("id") ? Number(urlParams.get("id")) : null;

        function getSelectedIcon() {
            const active = document.querySelector("[data-toggle-group='category-icon'] [data-toggle-option].active");
            if (!active) return "fa-solid fa-layer-group";
            if (active.dataset.value) return active.dataset.value;
            const icon = active.querySelector("i");
            return icon ? icon.className : "fa-solid fa-layer-group";
        }

        function getSelectedColor() {
            const active = document.querySelector("[data-toggle-group='category-color'] [data-toggle-option].active");
            if (!active) return "#4a90e2";
            return active.dataset.value || active.style.backgroundColor || "#4a90e2";
        }

        function updatePreview() {
            const name = nameInput ? nameInput.value.trim() : "";
            const desc = descriptionInput ? descriptionInput.value.trim() : "";
            const iconClass = getSelectedIcon();
            const color = getSelectedColor();

            if (previewName) previewName.textContent = name || "Nombre de la categoria";
            if (previewDesc) previewDesc.textContent = desc || "Descripcion de la categoria";
            if (previewIconEl) previewIconEl.className = iconClass;
            if (previewIconBox) previewIconBox.style.backgroundColor = color;
        }

        if (nameInput) nameInput.addEventListener("input", updatePreview);
        if (descriptionInput) descriptionInput.addEventListener("input", updatePreview);

        document.querySelectorAll("[data-toggle-group='category-icon'] [data-toggle-option]").forEach((btn) => {
            btn.addEventListener("click", updatePreview);
        });
        document.querySelectorAll("[data-toggle-group='category-color'] [data-toggle-option]").forEach((btn) => {
            btn.addEventListener("click", updatePreview);
        });

        if (editId) {
            if (pageTitle) pageTitle.textContent = "Editar Categoria";
            if (submitBtn) submitBtn.textContent = "Actualizar categoria";
            try {
                const cat = await fetchJson(`${API_BASE}/categories/${editId}`);
                if (nameInput) nameInput.value = cat.nombre || "";
                if (descriptionInput) descriptionInput.value = cat.descripcion || "";

                if (cat.icono) {
                    document.querySelectorAll("[data-toggle-group='category-icon'] [data-toggle-option]").forEach((btn) => {
                        const matches = btn.dataset.value === cat.icono ||
                            (btn.querySelector("i") && btn.querySelector("i").className === cat.icono);
                        if (matches) {
                            document.querySelectorAll("[data-toggle-group='category-icon'] [data-toggle-option]").forEach((b) => b.classList.remove("active"));
                            btn.classList.add("active");
                        }
                    });
                }

                if (cat.color) {
                    document.querySelectorAll("[data-toggle-group='category-color'] [data-toggle-option]").forEach((btn) => {
                        if (btn.dataset.value === cat.color) {
                            document.querySelectorAll("[data-toggle-group='category-color'] [data-toggle-option]").forEach((b) => b.classList.remove("active"));
                            btn.classList.add("active");
                        }
                    });
                }

                updatePreview();
            } catch (error) {
                if (message) message.textContent = `Error al cargar categoria: ${error.message}`;
            }
        }

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (message) message.textContent = editId ? "Actualizando categoria..." : "Guardando categoria...";

            const iconValue = getSelectedIcon();
            const colorValue = getSelectedColor();

            try {
                const url = editId ? `${API_BASE}/categories/${editId}` : `${API_BASE}/categories`;
                const method = editId ? "PUT" : "POST";

                await fetchJson(url, {
                    method,
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        nombre: nameInput ? nameInput.value.trim() : "",
                        descripcion: descriptionInput ? descriptionInput.value.trim() || null : null,
                        icono: iconValue || null,
                        color: colorValue || null,
                    }),
                });
                if (message) {
                    message.style.color = "#57d163";
                    message.textContent = editId
                        ? "Categoria actualizada correctamente."
                        : "Categoria agregada correctamente.";
                }
                showToast(editId ? "Categoria actualizada." : "Categoria creada.", "success");
                setTimeout(() => {
                    window.location.href = "/capturist/categories";
                }, 650);
            } catch (error) {
                if (message) message.textContent = error.message;
                showToast(error.message, "error");
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
        const pageTitle = document.getElementById("createAssetTitle");
        const submitBtn = document.getElementById("createAssetSubmitBtn");
        const editId = getAssetIdFromUrl();
        const photoInput = document.getElementById("asset_photo");
        const photoPreview = document.getElementById("asset_photo_preview");
        const photoPlaceholder = document.getElementById("photoBoxPlaceholder");
        const photoBox = document.getElementById("photoBox");
        let currentPhotoBase64 = null;

        if (editId) {
            if (pageTitle) pageTitle.textContent = "Editar activo";
            if (submitBtn) submitBtn.textContent = "Guardar cambios";
        }

        if (photoBox && photoInput) {
            photoBox.addEventListener("click", () => photoInput.click());
            photoInput.addEventListener("change", () => {
                const file = photoInput.files[0];
                if (file && photoPreview) {
                    photoPreview.src = URL.createObjectURL(file);
                    photoPreview.style.display = "block";
                    if (photoPlaceholder) photoPlaceholder.style.display = "none";
                }
            });
        }

        try {
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
        } catch (error) {
            if (message) message.textContent = `Error al cargar opciones: ${error.message}`;
        }

        if (editId) {
            try {
                const data = await fetchJson(`${API_BASE}/assets/${editId}`);
                const nameEl = document.getElementById("asset_name");
                const serialEl = document.getElementById("asset_serial");
                const brandEl = document.getElementById("asset_brand");
                const modelEl = document.getElementById("asset_model");
                const obsEl = document.getElementById("asset_observations");

                if (nameEl) nameEl.value = data.nombre || "";
                if (serialEl) serialEl.value = data.numero_serie || "";
                if (brandEl) brandEl.value = data.marca || "";
                if (modelEl) modelEl.value = data.modelo || "";
                if (obsEl) obsEl.value = data.observaciones || "";
                if (data.foto_base64) {
                    currentPhotoBase64 = data.foto_base64;
                    if (photoPreview) {
                        photoPreview.src = getAssetPhotoDataUrl(data);
                        photoPreview.style.display = "block";
                    }
                    if (photoPlaceholder) photoPlaceholder.style.display = "none";
                }

                if (data.id_categoria) categorySelect.value = String(data.id_categoria);
                if (data.id_estado) statusSelect.value = String(data.id_estado);
                if (data.id_ubicacion) locationSelect.value = String(data.id_ubicacion);
            } catch (error) {
                if (message) message.textContent = `Error al cargar activo: ${error.message}`;
            }
        }

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (message) message.textContent = editId ? "Actualizando activo..." : "Guardando activo...";

            let fotoBase64 = null;
            if (photoInput && photoInput.files[0]) {
                fotoBase64 = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result.split(",")[1]);
                    reader.readAsDataURL(photoInput.files[0]);
                });
            } else if (editId) {
                fotoBase64 = currentPhotoBase64;
            }

            try {
                const method = editId ? "PUT" : "POST";
                const endpoint = editId ? `${API_BASE}/assets/${editId}` : `${API_BASE}/assets`;
                const createdAsset = await fetchJson(endpoint, {
                    method,
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        nombre: document.getElementById("asset_name").value.trim(),
                        marca: document.getElementById("asset_brand").value || null,
                        modelo: document.getElementById("asset_model") ? document.getElementById("asset_model").value.trim() || null : null,
                        numero_serie: document.getElementById("asset_serial").value.trim() || null,
                        codigo_interno: null,
                        descripcion: null,
                        observaciones: document.getElementById("asset_observations") ? document.getElementById("asset_observations").value.trim() || null : null,
                        id_categoria: Number(categorySelect.value),
                        id_estado_activo: Number(statusSelect.value),
                        id_ubicacion: Number(locationSelect.value),
                        id_usuario: null,
                        foto_base64: fotoBase64,
                    }),
                });
                if (editId) {
                    showToast("Activo actualizado.", "success");
                    setTimeout(() => {
                        window.location.href = "/capturist/assets";
                    }, 450);
                    return;
                }
                let targetAssetId = createdAsset && createdAsset.id ? createdAsset.id : null;
                if (targetAssetId) {
                    try {
                        await fetchJson(`${API_BASE}/assets/${targetAssetId}/qr`, {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                        });
                    } catch (_qrError) {
                        // El activo se crea aunque falle la etiqueta QR.
                    }
                    showToast("Activo creado. Redirigiendo a QR.", "success");
                    setTimeout(() => {
                        window.location.href = `/capturist/assets/qr?id=${targetAssetId}`;
                    }, 450);
                } else {
                    showToast("Activo creado.", "success");
                    setTimeout(() => {
                        window.location.href = "/capturist/assets";
                    }, 450);
                }
            } catch (error) {
                if (message) message.textContent = error.message;
                showToast(error.message, "error");
            }
        });
    }

    async function handleShowAssetPage() {
        const detailNode = document.getElementById("assetDetailList");
        if (!detailNode) return;

        const assetId = getAssetIdFromUrl();
        if (!assetId) {
            detailNode.innerHTML = "<p>No se especifico un activo. <a href='/capturist/assets'>Volver al inventario</a></p>";
            return;
        }

        try {
            const data = await fetchJson(`${API_BASE}/assets/${assetId}`);

            detailNode.innerHTML = `
                <div class="detail-item"><strong>Nombre:</strong> ${escapeHtml(data.nombre)}</div>
                <div class="detail-item"><strong>No. serial:</strong> ${escapeHtml(data.numero_serie || "-")}</div>
                <div class="detail-item"><strong>Marca:</strong> ${escapeHtml(data.marca || "-")}</div>
                <div class="detail-item"><strong>Modelo:</strong> ${escapeHtml(data.modelo || "-")}</div>
                <div class="detail-item"><strong>Categoria:</strong> ${escapeHtml(data.categoria || "-")}</div>
                <div class="detail-item"><strong>Estado:</strong> ${escapeHtml(data.estado || "-")}</div>
                <div class="detail-item"><strong>Ubicacion:</strong> ${escapeHtml(data.ubicacion || "-")}</div>
                ${data.observaciones ? `<div class="detail-item"><strong>Observaciones:</strong> ${escapeHtml(data.observaciones)}</div>` : ""}
            `;

            const qrLink = document.getElementById("assetQrLink");
            if (qrLink) qrLink.setAttribute("href", `/capturist/assets/qr?id=${assetId}`);

            const previewTitle = document.getElementById("assetPreviewTitle");
            if (previewTitle) previewTitle.textContent = data.nombre;

            const previewSubtitle = document.getElementById("assetPreviewSubtitle");
            if (previewSubtitle) previewSubtitle.textContent = data.codigo_interno || `ID-${data.id || assetId}`;

            const previewBox = document.getElementById("assetPreviewBox");
            if (previewBox) {
                const photoUrl = getAssetPhotoDataUrl(data);
                if (photoUrl) {
                    previewBox.innerHTML = `
                        <img src="${escapeHtml(photoUrl)}" alt="Foto de ${escapeHtml(data.nombre || "activo")}" style="width:100%;max-height:320px;object-fit:contain;border-radius:12px;">
                    `;
                }
            }
        } catch (error) {
            detailNode.innerHTML = `<p>Error al cargar activo: ${escapeHtml(error.message)}</p>`;
        }
    }

    async function handleGenerateQrPage() {
        const button = document.getElementById("generateQrButton");
        if (!button) return;

        const message = document.getElementById("generateQrMessage");
        const assetId = getAssetIdFromUrl();

        if (!assetId) {
            if (message) message.textContent = "Selecciona un activo desde el inventario para personalizar su etiqueta QR.";
            button.disabled = true;
            return;
        }

        const backLink = document.querySelector(".page-toolbar .ghost-button");
        if (backLink) {
            backLink.setAttribute("href", `/capturist/assets/show?id=${assetId}`);
        }

        let assetData;
        try {
            assetData = await fetchJson(`${API_BASE}/assets/${assetId}`);
            const previewName = document.getElementById("qrPreviewName");
            const previewCode = document.getElementById("qrPreviewCode");
            const previewMeta = document.getElementById("qrPreviewMeta");

            if (previewName) previewName.textContent = (assetData.nombre || "ACTIVO").toUpperCase();
            if (previewCode) previewCode.textContent = assetData.codigo_interno || `ID-${assetData.id || assetId}`;
            if (previewMeta) previewMeta.textContent = `SN: ${assetData.numero_serie || "-"}   LOC: ${assetData.ubicacion || "-"}`;
        } catch (error) {
            if (message) message.textContent = `Error al cargar activo: ${error.message}`;
            button.disabled = true;
            return;
        }

        button.addEventListener("click", async () => {
            if (message) message.textContent = "Generando QR...";
            button.disabled = true;

            const selectedFields = [];
            document.querySelectorAll("[data-toggle-independent='qr-fields'] [data-toggle-option].active").forEach((btn) => {
                const span = btn.querySelector("span");
                if (span) selectedFields.push(span.textContent.trim());
            });

            const selectedSize = document.querySelector("[data-toggle-group='qr-size'] [data-toggle-option].active");
            const size = selectedSize ? selectedSize.textContent.trim() : "Estandar";

            try {
                const qrData = await fetchJson(`${API_BASE}/assets/${assetId}/qr`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ campos: selectedFields, tamano: size }),
                });

                await downloadQrPdf(assetId, assetData);

                if (message) {
                    message.textContent = qrData.qr
                        ? `QR generado y PDF descargado: ${qrData.qr}`
                        : "QR generado y PDF descargado con exito.";
                }
                showToast("PDF del QR descargado correctamente.", "success");
            } catch (error) {
                if (message) message.textContent = `Error al generar QR: ${error.message}`;
                showToast(`Error al generar PDF del QR: ${error.message}`, "error");
            } finally {
                button.disabled = false;
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
