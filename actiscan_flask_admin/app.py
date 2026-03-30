import os
import requests as req_lib
from flask import Flask, render_template, request, jsonify

app = Flask(__name__)

ACTISCAN_API_CANDIDATES = [
    os.environ.get("ACTISCAN_API_BASE"),
    os.environ.get("ACTISCAN_API_V1_BASE"),
    "http://actiscan_fastapi:8001/api",
    "http://actiscan_fastapi:8001/api/v1",
    "http://fastapi:8001/api",
    "http://fastapi:8001/api/v1",
    "http://127.0.0.1:8001/api",
    "http://127.0.0.1:8001/api/v1",
    "http://localhost:8001/api",
    "http://localhost:8001/api/v1",
    "http://host.docker.internal:8001/api",
    "http://host.docker.internal:8001/api/v1",
    "http://127.0.0.1:8000/api",
    "http://localhost:8000/api",
]

_resolved_api_base = None


def get_api_base():
    global _resolved_api_base
    if _resolved_api_base:
        return _resolved_api_base

    for base in ACTISCAN_API_CANDIDATES:
        if not base:
            continue
        base = base.rstrip("/")
        root = base.replace("/api/v1", "").replace("/api", "")
        try:
            r = req_lib.get(f"{root}/health", timeout=2)
            if r.ok:
                _resolved_api_base = base
                return base
        except Exception:
            pass

    fallback = next((b for b in ACTISCAN_API_CANDIDATES if b), "http://127.0.0.1:8001/api")
    return fallback.rstrip("/")


# ─── Auth routes ───────────────────────────────────────────────────────────────

@app.route("/")
def login():
    return render_template("auth/login.html")


@app.route("/recuperar-password")
def recuperar_password():
    return render_template("auth/recuperar_password.html")


@app.route("/verificar-codigo")
def verificar_codigo():
    return render_template("auth/verificar_codigo.html")


@app.route("/cambiar-password")
def cambiar_password():
    return render_template("auth/cambiar_password.html")


# ─── Admin routes ──────────────────────────────────────────────────────────────

@app.route("/admin/dashboard")
def dashboard():
    return render_template("admin/dashboard.html")


@app.route("/admin/perfil")
def perfil():
    return render_template("admin/perfil.html")


@app.route("/admin/activos")
def activos():
    return render_template("admin/activos.html")


@app.route("/admin/activos/crear")
def crear_activo():
    return render_template("admin/crear_activo.html")


@app.route("/admin/categorias")
def categorias():
    return render_template("admin/categorias.html")


@app.route("/admin/auditoria")
def auditoria():
    return render_template("admin/auditoria.html")


@app.route("/admin/auditoria/crear")
def crear_auditoria():
    return render_template("admin/crear_auditoria.html")


@app.route("/admin/usuarios")
def usuarios():
    return render_template("admin/usuarios.html")


@app.route("/admin/registrar-usuario")
def registrar_usuario():
    return render_template("admin/registrar_usuario.html")


# ─── API bridge ────────────────────────────────────────────────────────────────

@app.route("/api-bridge/<path:path>", methods=["GET", "POST", "PUT", "PATCH", "DELETE"])
def api_bridge(path):
    api_base = get_api_base()
    target_url = f"{api_base}/{path.lstrip('/')}"
    method = request.method
    params = request.args.to_dict()

    kwargs = {"params": params, "timeout": 12}

    if method in ("POST", "PUT", "PATCH"):
        payload_json = request.get_json(silent=True)
        if payload_json is not None:
            kwargs["json"] = payload_json
        else:
            kwargs["data"] = request.form.to_dict()

    auth_header = request.headers.get("Authorization")
    if auth_header:
        kwargs["headers"] = {"Authorization": auth_header}

    try:
        resp = req_lib.request(method, target_url, **kwargs)
        ct = resp.headers.get("content-type", "")
        if "application/json" in ct:
            return jsonify(resp.json()), resp.status_code
        return resp.content, resp.status_code, {"Content-Type": ct or "text/plain"}
    except Exception as exc:
        return jsonify({"detail": f"No se pudo conectar con FastAPI: {exc}"}), 503


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False)
