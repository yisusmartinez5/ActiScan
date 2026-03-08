from flask import Flask, render_template

app = Flask(__name__)

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

@app.route("/admin/dashboard")
def dashboard():
    return render_template("admin/dashboard.html")

@app.route("/admin/perfil")
def perfil():
    return render_template("admin/perfil.html")

@app.route("/admin/activos")
def activos():
    return render_template("admin/activos.html")

@app.route("/admin/categorias")
def categorias():
    return render_template("admin/categorias.html")

@app.route("/admin/auditoria")
def auditoria():
    return render_template("admin/auditoria.html")

@app.route("/admin/usuarios")
def usuarios():
    return render_template("admin/usuarios.html")

@app.route("/admin/registrar-usuario")
def registrar_usuario():
    return render_template("admin/registrar_usuario.html")

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)