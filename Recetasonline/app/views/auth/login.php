<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h3>Iniciar sesión</h3>
      <form action="<?= $base ?>/login_post" method="POST">
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-primary">Entrar</button>
        <a class="btn btn-link" href="<?= $base ?>/register">Crear cuenta</a>
      </form>
    </div>
  </div>
</div>
