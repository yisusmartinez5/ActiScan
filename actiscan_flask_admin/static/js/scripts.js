(function () {
    "use strict";

    const API_BASE = (window.ACTISCAN_API_BASE || "/api-bridge").replace(/\/$/, "");
    const page = document.body?.dataset?.page || "";

    // ── Sidebar mobile toggle ─────────────────────────────────────────────────
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

    // ── Dropdown "Crear" menu ─────────────────────────────────────────────────
    const createMenuBtn = document.getElementById("createMenuBtn");
    const createMenu = document.getElementById("createMenu");
    if (createMenuBtn && createMenu) {
        createMenuBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            createMenu.classList.toggle("open");
        });
        document.addEventListener("click", () => createMenu.classList.remove("open"));
    }

    // ── User pill from localStorage ───────────────────────────────────────────
    const userPillName = document.getElementById("userName");
    const userBadge = document.getElementById("userBadge");
    try {
        const stored = localStorage.getItem("actiscan_user");
        if (stored && userPillName) {
            const u = JSON.parse(stored);
            const name = u.nombre || u.name || u.email || "Admin";
            userPillName.textContent = name;
            if (userBadge) userBadge.textContent = name.slice(0, 2).toUpperCase();
        }
    } catch (_) {}

    // ── Logout ────────────────────────────────────────────────────────────────
    const logoutLink = document.getElementById("logoutLink");
    if (logoutLink) {
        logoutLink.addEventListener("click", () => localStorage.removeItem("actiscan_user"));
    }

    // ── Toast notifications ───────────────────────────────────────────────────
    function showAlert(message, type) {
        const toast = document.getElementById("actiscan-toast");
        if (!toast) return;
        toast.textContent = message;
        toast.style.background =
            type === "success" ? "#57d163" :
            type === "error"   ? "#ff6b6b" : "#4a90e2";
        toast.style.color = "#fff";
        toast.classList.add("show");
        clearTimeout(toast._t);
        toast._t = setTimeout(() => toast.classList.remove("show"), 3500);
    }

    // ── API helper ────────────────────────────────────────────────────────────
    async function fetchJson(url, options) {
        options = options || {};
        try {
            const u = JSON.parse(localStorage.getItem("actiscan_user") || "{}");
            const token = u.token || u.access_token;
            if (token) {
                options.headers = options.headers || {};
                options.headers["Authorization"] = "Bearer " + token;
            }
        } catch (_) {}

        let response;
        try {
            response = await fetch(url, options);
        } catch (_) {
            throw new Error("No se pudo conectar con la API. Verifica que FastAPI este ejecutandose.");
        }

        const ct = response.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await response.json() : null;

        if (!response.ok) {
            const detail = data && data.detail ? data.detail : "Error de comunicacion con la API";
            throw new Error(detail);
        }
        return data;
    }

    function escapeHtml(v) {
        return String(v == null ? "" : v)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;");
    }

    function getIdFromUrl() {
        const p = new URLSearchParams(window.location.search);
        const n = Number(p.get("id"));
        return Number.isFinite(n) && n > 0 ? n : null;
    }

    function getJsPdfCtor() {
        return window.jspdf && window.jspdf.jsPDF ? window.jspdf.jsPDF : null;
    }

    // ── QR modal helpers ─────────────────────────────────────────────────────
    function openQrModal(assetId, assetName, qrValue) {
        const modal = document.getElementById("qrModal");
        if (!modal) return;
        const assetNode = document.getElementById("qrModalAsset");
        const valueNode = document.getElementById("qrModalValue");
        const visualNode = document.getElementById("qrVisual");
        if (assetNode) assetNode.textContent = assetName || "Activo";
        if (valueNode) valueNode.textContent = qrValue || "-";

        if (visualNode) {
            visualNode.innerHTML = "";
            if (qrValue && qrValue !== "-") {
                if (assetId) {
                    visualNode.textContent = "Cargando QR...";
                    const img = document.createElement("img");
                    img.alt = "QR del activo";
                    img.src = `${API_BASE}/assets/${assetId}/qr/latest/image?t=${Date.now()}`;
                    img.onload = () => {
                        visualNode.innerHTML = "";
                        visualNode.appendChild(img);
                    };
                    img.onerror = () => {
                        visualNode.textContent = "No se pudo renderizar QR";
                    };
                } else if (typeof QRCode !== "undefined") {
                    new QRCode(visualNode, {
                        text: qrValue,
                        width: 180,
                        height: 180,
                        colorDark: "#102a43",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M,
                    });
                } else {
                    visualNode.textContent = "No se pudo renderizar QR";
                }
            } else {
                visualNode.textContent = "QR no disponible";
            }
        }

        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");
    }

    function closeQrModal() {
        const modal = document.getElementById("qrModal");
        if (!modal) return;
        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
    }

    function setupQrModal() {
        const modal = document.getElementById("qrModal");
        if (!modal) return;

        modal.querySelectorAll("[data-close-qr-modal]").forEach((el) => {
            el.addEventListener("click", closeQrModal);
        });

        const copyBtn = document.getElementById("qrCopyBtn");
        if (copyBtn) {
            copyBtn.addEventListener("click", async () => {
                const valueNode = document.getElementById("qrModalValue");
                const qrText = valueNode ? valueNode.textContent : "";
                if (!qrText || qrText === "-") return;
                try {
                    await navigator.clipboard.writeText(qrText);
                    showAlert("QR copiado al portapapeles.", "success");
                } catch (_) {
                    showAlert("No se pudo copiar el QR.", "error");
                }
            });
        }

        const qrPdfBtn = document.getElementById("qrPdfBtn");
        if (qrPdfBtn) {
            qrPdfBtn.addEventListener("click", () => {
                const JsPdf = getJsPdfCtor();
                if (!JsPdf) {
                    showAlert("No se pudo cargar el generador de PDF.", "error");
                    return;
                }

                const assetName = (document.getElementById("qrModalAsset") || {}).textContent || "Activo";
                const qrValue = (document.getElementById("qrModalValue") || {}).textContent || "-";
                const visualNode = document.getElementById("qrVisual");

                const doc = new JsPdf({ unit: "mm", format: "a4" });
                doc.setFontSize(16);
                doc.text("ActiScan - Codigo QR", 15, 16);
                doc.setFontSize(11);
                doc.text(`Activo: ${assetName}`, 15, 24);
                doc.text(`Valor QR: ${qrValue}`, 15, 30);

                try {
                    let dataUrl = null;
                    const imgEl = visualNode ? visualNode.querySelector("img") : null;
                    const canvasEl = visualNode ? visualNode.querySelector("canvas") : null;
                    if (canvasEl) {
                        dataUrl = canvasEl.toDataURL("image/png");
                    } else if (imgEl && imgEl.complete) {
                        const tmpCanvas = document.createElement("canvas");
                        tmpCanvas.width = imgEl.naturalWidth || 180;
                        tmpCanvas.height = imgEl.naturalHeight || 180;
                        const ctx = tmpCanvas.getContext("2d");
                        if (ctx) {
                            ctx.drawImage(imgEl, 0, 0);
                            dataUrl = tmpCanvas.toDataURL("image/png");
                        }
                    }
                    if (dataUrl) {
                        doc.addImage(dataUrl, "PNG", 15, 36, 65, 65);
                    }
                } catch (_) {
                    // Si no se puede incrustar imagen, el PDF igual se descarga con el texto QR.
                }

                const safeAsset = String(assetName).replace(/[^a-z0-9\-_]+/gi, "_").slice(0, 40) || "activo";
                doc.save(`QR_${safeAsset}.pdf`);
                showAlert("PDF de QR descargado.", "success");
            });
        }
    }

    setupQrModal();

    // ── Page: login ───────────────────────────────────────────────────────────
    function handleLoginPage() {
        const btn = document.getElementById("loginBtn");
        const msg = document.getElementById("loginMessage");
        if (!btn) return;

        btn.addEventListener("click", async () => {
            const email = document.getElementById("loginEmail").value.trim();
            const pass  = document.getElementById("loginPass").value;
            if (!email || !pass) { if (msg) msg.textContent = "Completa todos los campos."; return; }
            if (msg) msg.textContent = "Validando...";
            try {
                const payload = await fetchJson(`${API_BASE}/auth/login`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email, password: pass }),
                });
                localStorage.setItem("actiscan_user", JSON.stringify(payload.user || payload));
                window.location.href = "/admin/dashboard";
            } catch (e) {
                if (msg) msg.textContent = e.message;
            }
        });

        // Allow Enter key
        document.getElementById("loginPass").addEventListener("keydown", (e) => {
            if (e.key === "Enter") btn.click();
        });
    }

    // ── Page: dashboard ───────────────────────────────────────────────────────
    async function handleDashboardPage() {
        const tbody = document.getElementById("dashTableBody");
        if (!tbody) return;

        try {
            const summary = await fetchJson(`${API_BASE}/dashboard/summary`);
            const s = summary.stats || {};
            const el = (id, val) => { const n = document.getElementById(id); if (n) n.textContent = val ?? "-"; };
            el("dashTotalActivos",  s.total_activos ?? 0);
            el("dashAuditorias",    s.auditorias_realizadas ?? s.total_auditorias ?? 0);
            el("dashActObs",        s.activos_con_observaciones ?? 0);
            el("dashTotalUsuarios", s.total_usuarios ?? 0);
        } catch (_) {}

        try {
            const lookups = await fetchJson(`${API_BASE}/lookups`);
            const catSel = document.getElementById("dashCatFilter");
            const stSel  = document.getElementById("dashStatusFilter");
            (lookups.categories || []).forEach(c =>
                catSel.insertAdjacentHTML("beforeend", `<option value="${c.id}">${escapeHtml(c.nombre)}</option>`)
            );
            (lookups.statuses || []).forEach(s =>
                stSel.insertAdjacentHTML("beforeend", `<option value="${s.id}">${escapeHtml(s.nombre)}</option>`)
            );
        } catch (_) {}

        async function loadTable() {
            const params = new URLSearchParams();
            const q   = (document.getElementById("dashSearch") || {}).value || "";
            const cat = (document.getElementById("dashCatFilter") || {}).value || "";
            const st  = (document.getElementById("dashStatusFilter") || {}).value || "";
            if (q.trim())   params.set("q", q.trim());
            if (cat) params.set("category_id", cat);
            if (st)  params.set("status_id", st);

            try {
                const assets = await fetchJson(`${API_BASE}/assets?${params}`);
                if (!assets.length) {
                    tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>Sin activos registrados</td></tr>";
                    return;
                }
                tbody.innerHTML = assets.map(a => renderActivoRow(a, false)).join("");
                attachActivoActions(tbody, null);
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan='5' style='color:#e55;padding:16px;'>${escapeHtml(e.message)}</td></tr>`;
            }
        }

        await loadTable();
        const applyBtn = document.getElementById("dashApplyFilter");
        if (applyBtn) applyBtn.addEventListener("click", loadTable);
        const search = document.getElementById("dashSearch");
        if (search) search.addEventListener("keydown", e => { if (e.key === "Enter") loadTable(); });
    }

    // ── Page: activos ─────────────────────────────────────────────────────────
    async function handleActivosPage() {
        const tbody = document.getElementById("activosTableBody");
        if (!tbody) return;

        try {
            const lookups = await fetchJson(`${API_BASE}/lookups`);
            const catSel = document.getElementById("activosCatFilter");
            const stSel  = document.getElementById("activosStatusFilter");
            (lookups.categories || []).forEach(c =>
                catSel.insertAdjacentHTML("beforeend", `<option value="${c.id}">${escapeHtml(c.nombre)}</option>`)
            );
            (lookups.statuses || []).forEach(s =>
                stSel.insertAdjacentHTML("beforeend", `<option value="${s.id}">${escapeHtml(s.nombre)}</option>`)
            );
        } catch (_) {}

        async function loadActivos() {
            const params = new URLSearchParams();
            const q   = (document.getElementById("activosSearch") || {}).value || "";
            const cat = (document.getElementById("activosCatFilter") || {}).value || "";
            const st  = (document.getElementById("activosStatusFilter") || {}).value || "";
            if (q.trim())   params.set("q", q.trim());
            if (cat) params.set("category_id", cat);
            if (st)  params.set("status_id", st);

            try {
                const assets = await fetchJson(`${API_BASE}/assets?${params}`);
                if (!assets.length) {
                    tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>Sin activos registrados</td></tr>";
                    return;
                }
                tbody.innerHTML = assets.map(a => renderActivoRow(a, true)).join("");
                attachActivoActions(tbody, loadActivos);
            } catch (e) {
                tbody.innerHTML = `<tr><td colspan='5' style='color:#e55;padding:16px;'>${escapeHtml(e.message)}</td></tr>`;
            }
        }

        await loadActivos();
        const applyBtn = document.getElementById("activosApplyFilter");
        if (applyBtn) applyBtn.addEventListener("click", loadActivos);
        const search = document.getElementById("activosSearch");
        if (search) search.addEventListener("keydown", e => { if (e.key === "Enter") loadActivos(); });
    }

    function renderActivoRow(a, includeEdit) {
        const isMaint  = String(a.estado || "").toLowerCase().includes("mantenimiento");
        const dotColor = isMaint ? "#f4cd3d" : "#57d163";
        const editBtn  = includeEdit
            ? `<button class="icon-btn" data-action="edit-activo" data-id="${a.id}" title="Editar"><i class="fa-regular fa-pen-to-square"></i></button>`
            : "";
        return `<tr>
            <td><b>${escapeHtml(a.nombre)}</b><br><small style="color:#9aabb8;">${escapeHtml(a.codigo_interno || "")}</small></td>
            <td><span class="badge-soft">${escapeHtml(a.categoria || "-")}</span></td>
            <td><span style="color:${dotColor};">&#9679;</span> ${escapeHtml(String(a.estado || "-").toUpperCase())}</td>
            <td>${escapeHtml(a.ubicacion || "-")}</td>
            <td class="option-icons">
                <button class="icon-btn" data-action="view-activo" data-id="${a.id}" title="Ver"><i class="fa-regular fa-eye"></i></button>
                <button class="icon-btn" data-action="qr-activo" data-id="${a.id}" title="Generar QR"><i class="fa-solid fa-qrcode"></i></button>
                ${editBtn}
                <button class="icon-btn" data-action="delete-activo" data-id="${a.id}" title="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
            </td>
        </tr>`;
    }

    function attachActivoActions(container, reloadFn) {
        container.addEventListener("click", async (e) => {
            const btn = e.target.closest("[data-action]");
            if (!btn) return;
            const action = btn.dataset.action;
            const id = btn.dataset.id;

            if (action === "view-activo") {
                window.location.href = `/admin/activos/crear?id=${id}&modo=ver`;
            } else if (action === "qr-activo") {
                try {
                    const row = btn.closest("tr");
                    const nameNode = row ? row.querySelector("td b") : null;
                    const assetName = nameNode ? nameNode.textContent.trim() : `Activo ${id}`;
                    let qrData;
                    try {
                        qrData = await fetchJson(`${API_BASE}/assets/${id}/qr/latest`);
                    } catch (_notFound) {
                        qrData = await fetchJson(`${API_BASE}/assets/${id}/qr`, { method: "POST" });
                    }
                    openQrModal(id, assetName, qrData?.qr || "-");
                } catch (err) {
                    showAlert(err.message, "error");
                }
            } else if (action === "edit-activo") {
                window.location.href = `/admin/activos/crear?id=${id}`;
            } else if (action === "delete-activo") {
                if (!confirm("Eliminar este activo? Esta accion no se puede deshacer.")) return;
                try {
                    await fetchJson(`${API_BASE}/assets/${id}`, { method: "DELETE" });
                    showAlert("Activo eliminado.", "success");
                    if (reloadFn) reloadFn(); else btn.closest("tr").remove();
                } catch (err) {
                    showAlert(err.message, "error");
                }
            }
        });
    }

    // ── Page: crear / editar activo ───────────────────────────────────────────
    async function handleCrearActivoPage() {
        const form = document.getElementById("crearActivoForm");
        if (!form) return;

        const msg    = document.getElementById("crearActivoMessage");
        const title  = document.getElementById("crearActivoTitle");
        const editId = getIdFromUrl();
        const modo   = new URLSearchParams(window.location.search).get("modo");
        const photoBox = document.getElementById("activoPhotoBox");
        const photoInput = document.getElementById("activo_foto");
        const photoPreview = document.getElementById("activoPhotoPreview");
        const photoPlaceholder = document.getElementById("activoPhotoPlaceholder");
        let selectedPhotoBase64 = null;

        function setPhotoPreview(dataUrl) {
            if (!photoPreview || !photoPlaceholder) return;
            if (dataUrl) {
                photoPreview.src = dataUrl;
                photoPreview.style.display = "block";
                photoPlaceholder.style.display = "none";
            } else {
                photoPreview.src = "";
                photoPreview.style.display = "none";
                photoPlaceholder.style.display = "block";
            }
        }

        if (photoBox && photoInput) {
            photoBox.addEventListener("click", () => {
                if (!photoInput.disabled) photoInput.click();
            });

            photoInput.addEventListener("change", () => {
                const file = photoInput.files && photoInput.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = () => {
                    const dataUrl = String(reader.result || "");
                    if (!dataUrl.includes(",")) return;
                    selectedPhotoBase64 = dataUrl.split(",")[1] || null;
                    setPhotoPreview(dataUrl);
                };
                reader.onerror = () => {
                    selectedPhotoBase64 = null;
                    showAlert("No se pudo leer la imagen seleccionada.", "error");
                };
                reader.readAsDataURL(file);
            });
        }

        if (editId && title) title.textContent = modo === "ver" ? "Detalle del Activo" : "Editar Activo";

        try {
            const lookups = await fetchJson(`${API_BASE}/lookups`);
            const catSel = document.getElementById("activo_categoria");
            const stSel  = document.getElementById("activo_estado");
            const locSel = document.getElementById("activo_ubicacion");
            (lookups.categories || []).forEach(c =>
                catSel.insertAdjacentHTML("beforeend", `<option value="${c.id}">${escapeHtml(c.nombre)}</option>`)
            );
            (lookups.statuses || []).forEach(s =>
                stSel.insertAdjacentHTML("beforeend", `<option value="${s.id}">${escapeHtml(s.nombre)}</option>`)
            );
            (lookups.locations || []).forEach(l =>
                locSel.insertAdjacentHTML("beforeend", `<option value="${l.id}">${escapeHtml(l.nombre)}</option>`)
            );
        } catch (e) {
            if (msg) msg.textContent = "Error al cargar opciones: " + e.message;
        }

        if (editId) {
            try {
                const data = await fetchJson(`${API_BASE}/assets/${editId}`);
                document.getElementById("activo_nombre").value        = data.nombre || "";
                document.getElementById("activo_serial").value        = data.numero_serie || "";
                document.getElementById("activo_marca").value         = data.marca || "";
                document.getElementById("activo_modelo").value        = data.modelo || "";
                document.getElementById("activo_observaciones").value = data.observaciones || "";
                if (data.foto_base64) {
                    selectedPhotoBase64 = data.foto_base64;
                    setPhotoPreview(`data:image/png;base64,${data.foto_base64}`);
                }
                setTimeout(() => {
                    if (data.id_categoria)     document.getElementById("activo_categoria").value = data.id_categoria;
                    if (data.id_estado_activo || data.id_estado) {
                        document.getElementById("activo_estado").value = data.id_estado_activo || data.id_estado;
                    }
                    if (data.id_ubicacion)     document.getElementById("activo_ubicacion").value = data.id_ubicacion;
                }, 250);
            } catch (e) {
                if (msg) msg.textContent = "Error al cargar activo: " + e.message;
            }

            if (modo === "ver") {
                form.querySelectorAll("input, select, textarea").forEach(el => el.disabled = true);
                const btn = document.getElementById("crearActivoBtn");
                if (btn) btn.style.display = "none";
                if (photoBox) photoBox.style.cursor = "default";
                return;
            }
        }

        form.addEventListener("submit", async (ev) => {
            ev.preventDefault();
            if (msg) msg.textContent = editId ? "Actualizando..." : "Guardando...";

            const payload = {
                nombre:           document.getElementById("activo_nombre").value.trim(),
                numero_serie:     document.getElementById("activo_serial").value.trim() || null,
                marca:            document.getElementById("activo_marca").value || null,
                modelo:           document.getElementById("activo_modelo").value.trim() || null,
                observaciones:    document.getElementById("activo_observaciones").value.trim() || null,
                id_categoria:     Number(document.getElementById("activo_categoria").value) || null,
                id_estado_activo: Number(document.getElementById("activo_estado").value) || null,
                id_ubicacion:     Number(document.getElementById("activo_ubicacion").value) || null,
                codigo_interno:   null,
                descripcion:      null,
                id_usuario:       null,
                foto_base64:      selectedPhotoBase64,
            };

            try {
                const url    = editId ? `${API_BASE}/assets/${editId}` : `${API_BASE}/assets`;
                const method = editId ? "PUT" : "POST";
                const savedAsset = await fetchJson(url, {
                    method,
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                });

                if (!editId && savedAsset?.id) {
                    try {
                        await fetchJson(`${API_BASE}/assets/${savedAsset.id}/qr`, { method: "POST" });
                    } catch (_qrError) {
                        // El activo queda creado aunque falle la etiqueta QR.
                    }
                }
                showAlert(editId ? "Activo actualizado." : "Activo creado.", "success");
                window.location.href = "/admin/activos";
            } catch (e) {
                if (msg) msg.textContent = e.message;
                showAlert(e.message, "error");
            }
        });
    }

    // ── Page: categorias ──────────────────────────────────────────────────────
    async function handleCategoriasPage() {
        const tbody = document.getElementById("categoriasTableBody");
        if (!tbody) return;

        async function loadCategorias() {
            const q = ((document.getElementById("categoriasSearch") || {}).value || "").toLowerCase();
            try {
                const rows = await fetchJson(`${API_BASE}/categories`);
                const filtered = q.trim()
                    ? rows.filter(r =>
                        String(r.nombre || "").toLowerCase().includes(q) ||
                        String(r.descripcion || "").toLowerCase().includes(q))
                    : rows;

                if (!filtered.length) {
                    tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>Sin categorias registradas</td></tr>";
                    return;
                }

                tbody.innerHTML = filtered.map(r => {
                    const icon  = escapeHtml(r.icono || "fa-solid fa-layer-group");
                    const color = escapeHtml(r.color || "#4a90e2");
                    return `<tr>
                        <td><i class="${icon}" style="color:${color};font-size:1.2rem;"></i></td>
                        <td><span class="badge-soft" style="border-left:3px solid ${color};">${escapeHtml(r.nombre)}</span></td>
                        <td>${escapeHtml(r.descripcion || "-")}</td>
                        <td>${r.total_activos ?? 0}</td>
                        <td class="option-icons">
                            <button class="icon-btn" data-action="delete-cat" data-id="${r.id}" title="Eliminar categoria"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    </tr>`;
                }).join("");

            } catch (e) {
                tbody.innerHTML = `<tr><td colspan='5' style='color:#e55;padding:16px;'>${escapeHtml(e.message)}</td></tr>`;
            }
        }

        await loadCategorias();

        tbody.addEventListener("click", async (e) => {
            const btn = e.target.closest("[data-action='delete-cat']");
            if (!btn) return;
            if (!confirm("Eliminar esta categoria?")) return;
            try {
                await fetchJson(`${API_BASE}/categories/${btn.dataset.id}`, { method: "DELETE" });
                showAlert("Categoria eliminada.", "success");
                loadCategorias();
            } catch (err) {
                showAlert(err.message, "error");
            }
        });

        const applyBtn = document.getElementById("categoriasApplyFilter");
        if (applyBtn) applyBtn.addEventListener("click", loadCategorias);
        const search = document.getElementById("categoriasSearch");
        if (search) search.addEventListener("keydown", e => { if (e.key === "Enter") loadCategorias(); });
    }

    // ── Page: auditoria ───────────────────────────────────────────────────────
    async function handleAuditoriaPage() {
        const tbody = document.getElementById("auditoriaTableBody");
        if (!tbody) return;
        const pdfBtn = document.getElementById("auditsPdfBtn");
        let lastAudits = [];

        function downloadAuditsPdf(rows) {
            const JsPdf = getJsPdfCtor();
            if (!JsPdf) {
                showAlert("No se pudo cargar el generador de PDF.", "error");
                return;
            }
            if (!rows || !rows.length) {
                showAlert("No hay auditorias para exportar.", "error");
                return;
            }

            const doc = new JsPdf({ unit: "mm", format: "a4" });
            doc.setFontSize(16);
            doc.text("ActiScan - Reporte de Auditorias", 14, 14);
            doc.setFontSize(10);
            doc.text(`Generado: ${new Date().toLocaleString()}`, 14, 20);

            let y = 28;
            rows.forEach((r, idx) => {
                const activo = r.activo_nombre || r.nombre_activo || r.activo || "-";
                const tipo = r.tipo || "-";
                const fecha = String(r.fecha_auditoria || r.fecha || r.created_at || "-").slice(0, 10);
                const resultado = r.resultado || "-";
                const descripcion = r.descripcion || "-";

                const block = [
                    `${idx + 1}. Activo: ${activo}`,
                    `Tipo: ${tipo}`,
                    `Fecha: ${fecha}`,
                    `Resultado: ${resultado}`,
                    `Descripcion: ${descripcion}`,
                ];

                const wrapped = doc.splitTextToSize(block.join("\n"), 180);
                const height = wrapped.length * 5 + 4;
                if (y + height > 285) {
                    doc.addPage();
                    y = 14;
                }
                doc.setDrawColor(220, 228, 236);
                doc.rect(12, y - 3, 186, height);
                doc.text(wrapped, 14, y);
                y += height + 3;
            });

            doc.save("Auditorias_ActiScan.pdf");
            showAlert("PDF de auditorias descargado.", "success");
        }

        async function loadAuditorias() {
            const q = ((document.getElementById("auditoriaSearch") || {}).value || "").toLowerCase();
            try {
                const rows = await fetchJson(`${API_BASE}/audits`);
                const filtered = q.trim()
                    ? rows.filter(r =>
                        String(r.activo_nombre || r.nombre_activo || r.activo || "").toLowerCase().includes(q) ||
                        String(r.tipo || "").toLowerCase().includes(q))
                    : rows;
                lastAudits = filtered;

                if (!filtered.length) {
                    tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>Sin auditorias registradas</td></tr>";
                    return;
                }

                tbody.innerHTML = filtered.map(r => {
                    const fecha = r.fecha_auditoria || r.fecha || r.created_at || "-";
                    const res   = r.resultado || "-";
                    const resColor = res.toLowerCase().includes("satisfactorio") && !res.toLowerCase().includes("no")
                        ? "#57d163" : res.toLowerCase().includes("observaciones") ? "#f4cd3d" : "#ff6b6b";
                    return `<tr>
                        <td>${escapeHtml(r.activo_nombre || r.nombre_activo || r.activo || "-")}</td>
                        <td>${escapeHtml(r.tipo || "-")}</td>
                        <td>${escapeHtml(String(fecha).slice(0, 10))}</td>
                        <td><span style="color:${resColor};font-weight:700;">${escapeHtml(res)}</span></td>
                        <td class="option-icons">
                            <button class="icon-btn" data-action="delete-audit" data-id="${r.id}" title="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    </tr>`;
                }).join("");

            } catch (e) {
                const notFound = e.message.includes("404") || e.message.toLowerCase().includes("not found");
                const msg = notFound
                    ? "El modulo de auditorias aun no esta disponible en el servidor."
                    : e.message;
                tbody.innerHTML = `<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>${escapeHtml(msg)}</td></tr>`;
            }
        }

        await loadAuditorias();

        tbody.addEventListener("click", async (e) => {
            const btn = e.target.closest("[data-action='delete-audit']");
            if (!btn) return;
            if (!confirm("Eliminar esta auditoria?")) return;
            try {
                await fetchJson(`${API_BASE}/audits/${btn.dataset.id}`, { method: "DELETE" });
                showAlert("Auditoria eliminada.", "success");
                loadAuditorias();
            } catch (err) {
                showAlert(err.message, "error");
            }
        });

        const applyBtn = document.getElementById("auditoriaApplyFilter");
        if (applyBtn) applyBtn.addEventListener("click", loadAuditorias);
        const search = document.getElementById("auditoriaSearch");
        if (search) search.addEventListener("keydown", e => { if (e.key === "Enter") loadAuditorias(); });
        if (pdfBtn) pdfBtn.addEventListener("click", () => downloadAuditsPdf(lastAudits));
    }

    // ── Page: crear auditoria ─────────────────────────────────────────────────
    async function handleCrearAuditoriaPage() {
        const form = document.getElementById("crearAuditoriaForm");
        if (!form) return;

        const msg = document.getElementById("crearAuditoriaMessage");

        try {
            const assets = await fetchJson(`${API_BASE}/assets`);
            const sel = document.getElementById("audit_activo");
            (assets || []).forEach(a =>
                sel.insertAdjacentHTML("beforeend", `<option value="${a.id}">${escapeHtml(a.nombre)}</option>`)
            );
        } catch (e) {
            if (msg) msg.textContent = "Error al cargar activos: " + e.message;
        }

        form.addEventListener("submit", async (ev) => {
            ev.preventDefault();
            if (msg) msg.textContent = "Guardando auditoria...";

            const payload = {
                id_activo:   Number(document.getElementById("audit_activo").value) || null,
                tipo:        document.getElementById("audit_tipo").value,
                descripcion: document.getElementById("audit_descripcion").value.trim() || null,
                resultado:   document.getElementById("audit_resultado").value,
            };

            try {
                await fetchJson(`${API_BASE}/audits`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                });
                showAlert("Auditoria registrada.", "success");
                window.location.href = "/admin/auditoria";
            } catch (e) {
                if (msg) msg.textContent = e.message;
                showAlert(e.message, "error");
            }
        });
    }

    // ── Page: usuarios ────────────────────────────────────────────────────────
    async function handleUsuariosPage() {
        const tbody = document.getElementById("usuariosTableBody");
        if (!tbody) return;

        async function loadUsuarios() {
            const q = ((document.getElementById("usuariosSearch") || {}).value || "").toLowerCase();
            try {
                const rows = await fetchJson(`${API_BASE}/admin/users`);
                const filtered = q.trim()
                    ? rows.filter(r =>
                        String(r.nombre || r.name || "").toLowerCase().includes(q) ||
                        String(r.email || "").toLowerCase().includes(q))
                    : rows;

                if (!filtered.length) {
                    tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;color:#aaa;padding:24px;'>Sin usuarios registrados</td></tr>";
                    return;
                }

                tbody.innerHTML = filtered.map(u => `<tr>
                    <td>${escapeHtml(u.nombre || u.name || "-")}</td>
                    <td>${escapeHtml(u.email || "-")}</td>
                    <td><span class="badge-soft">${escapeHtml(u.rol || u.role || "-")}</span></td>
                    <td>${u.estado !== undefined ? (u.estado ? "Activo" : "Inactivo") : "-"}</td>
                    <td class="option-icons">
                        <button class="icon-btn" data-action="view-user" data-id="${u.id}" title="Ver"><i class="fa-regular fa-eye"></i></button>
                        <button class="icon-btn" data-action="edit-user" data-id="${u.id}" title="Editar"><i class="fa-regular fa-pen-to-square"></i></button>
                        <button class="icon-btn" data-action="delete-user" data-id="${u.id}" title="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
                    </td>
                </tr>`).join("");

            } catch (e) {
                tbody.innerHTML = `<tr><td colspan='5' style='color:#e55;padding:16px;'>${escapeHtml(e.message)}</td></tr>`;
            }
        }

        await loadUsuarios();

        tbody.addEventListener("click", async (e) => {
            const btn = e.target.closest("[data-action]");
            if (!btn) return;
            const action = btn.dataset.action;
            const id     = btn.dataset.id;

            if (action === "view-user") {
                window.location.href = `/admin/registrar-usuario?id=${id}&modo=ver`;
            } else if (action === "edit-user") {
                window.location.href = `/admin/registrar-usuario?id=${id}`;
            } else if (action === "delete-user") {
                if (!confirm("Eliminar este usuario?")) return;
                try {
                    await fetchJson(`${API_BASE}/admin/users/${id}`, { method: "DELETE" });
                    showAlert("Usuario eliminado.", "success");
                    loadUsuarios();
                } catch (err) {
                    showAlert(err.message, "error");
                }
            }
        });

        const applyBtn = document.getElementById("usuariosApplyFilter");
        if (applyBtn) applyBtn.addEventListener("click", loadUsuarios);
        const search = document.getElementById("usuariosSearch");
        if (search) search.addEventListener("keydown", e => { if (e.key === "Enter") loadUsuarios(); });
    }

    // ── Page: registrar / editar usuario ──────────────────────────────────────
    async function handleRegistrarUsuarioPage() {
        const form  = document.getElementById("registrarForm");
        const msg   = document.getElementById("registrarMessage");
        const title = document.getElementById("regUserTitle");
        if (!form) return;

        const editId = getIdFromUrl();
        const modo   = new URLSearchParams(window.location.search).get("modo");
        const rolInput = document.getElementById("reg_rol");

        // Role selection toggle
        document.querySelectorAll("[data-role]").forEach(opt => {
            opt.addEventListener("click", () => {
                document.querySelectorAll("[data-role]").forEach(o => o.classList.remove("active"));
                opt.classList.add("active");
                if (rolInput) rolInput.value = opt.dataset.role;
            });
        });

        if (editId) {
            if (title) title.textContent = modo === "ver" ? "Detalle de Usuario" : "Editar Usuario";
            try {
                const u = await fetchJson(`${API_BASE}/admin/users/${editId}`);
                const nEl = document.getElementById("reg_nombre");
                const eEl = document.getElementById("reg_email");
                const pEl = document.getElementById("reg_password");
                if (nEl) nEl.value = u.nombre || u.name || "";
                if (eEl) eEl.value = u.email || "";
                if (pEl) pEl.placeholder = "Dejar vacio para no cambiar";
                if (pEl) pEl.required = false;

                const rol = (u.rol || u.role || "capturista").toLowerCase();
                if (rolInput) rolInput.value = rol;
                document.querySelectorAll("[data-role]").forEach(o => {
                    o.classList.toggle("active", o.dataset.role === rol);
                });
            } catch (e) {
                if (msg) msg.textContent = "Error al cargar usuario: " + e.message;
            }

            if (modo === "ver") {
                form.querySelectorAll("input").forEach(el => el.disabled = true);
                document.querySelectorAll("[data-role]").forEach(o => o.style.pointerEvents = "none");
                const btn = document.getElementById("registrarBtn");
                if (btn) btn.style.display = "none";
                return;
            }
        }

        form.addEventListener("submit", async (ev) => {
            ev.preventDefault();
            if (msg) { msg.textContent = ""; msg.style.color = "#e55"; }

            const nombre   = document.getElementById("reg_nombre").value.trim();
            const email    = document.getElementById("reg_email").value.trim();
            const password = document.getElementById("reg_password").value;
            const rol      = rolInput ? rolInput.value : "capturista";

            if (!nombre || !email) {
                if (msg) msg.textContent = "Nombre y correo son obligatorios.";
                return;
            }
            if (!editId && !password) {
                if (msg) msg.textContent = "La contrasena es obligatoria.";
                return;
            }

            const payload = { nombre, email, rol };
            if (password) payload.password = password;

            if (msg) { msg.textContent = editId ? "Actualizando..." : "Registrando..."; msg.style.color = "#888"; }

            try {
                const url    = editId ? `${API_BASE}/admin/users/${editId}` : `${API_BASE}/admin/users`;
                const method = editId ? "PUT" : "POST";
                await fetchJson(url, {
                    method,
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                });
                showAlert(editId ? "Usuario actualizado." : "Usuario registrado.", "success");
                window.location.href = "/admin/usuarios";
            } catch (e) {
                if (msg) { msg.textContent = e.message; msg.style.color = "#e55"; }
                showAlert(e.message, "error");
            }
        });
    }

    // ── Page: cambiar contrasena ───────────────────────────────────────────────
    function handleCambiarPasswordPage() {
        const form = document.getElementById("passwordForm");
        const msg  = document.getElementById("passwordMessage");
        if (!form) return;

        form.addEventListener("submit", async (ev) => {
            ev.preventDefault();
            if (msg) { msg.textContent = ""; msg.style.color = "#e55"; }

            const current = document.getElementById("currentPassword").value;
            const newPass = document.getElementById("newPassword").value;
            const confirm = document.getElementById("confirmPassword").value;

            if (!current || !newPass || !confirm) {
                if (msg) msg.textContent = "Completa todos los campos.";
                return;
            }
            if (newPass !== confirm) {
                if (msg) msg.textContent = "Las contrasenas nuevas no coinciden.";
                return;
            }
            if (newPass.length < 6) {
                if (msg) msg.textContent = "La nueva contrasena debe tener al menos 6 caracteres.";
                return;
            }

            if (msg) { msg.textContent = "Actualizando contrasena..."; msg.style.color = "#888"; }

            try {
                await fetchJson(`${API_BASE}/auth/change-password`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ current_password: current, new_password: newPass }),
                });
                if (msg) { msg.textContent = "Contrasena actualizada correctamente."; msg.style.color = "#57d163"; }
                showAlert("Contrasena actualizada.", "success");
                document.getElementById("currentPassword").value = "";
                document.getElementById("newPassword").value = "";
                document.getElementById("confirmPassword").value = "";
            } catch (e) {
                if (msg) { msg.textContent = e.message; msg.style.color = "#e55"; }
                showAlert(e.message, "error");
            }
        });
    }

    // ── Router ────────────────────────────────────────────────────────────────
    const handlers = {
        "login":             handleLoginPage,
        "dashboard":         handleDashboardPage,
        "activos":           handleActivosPage,
        "crear-activo":      handleCrearActivoPage,
        "categorias":        handleCategoriasPage,
        "auditoria":         handleAuditoriaPage,
        "crear-auditoria":   handleCrearAuditoriaPage,
        "usuarios":          handleUsuariosPage,
        "registrar-usuario": handleRegistrarUsuarioPage,
        "cambiar-password":  handleCambiarPasswordPage,
    };

    if (handlers[page]) {
        handlers[page]();
    }
})();
