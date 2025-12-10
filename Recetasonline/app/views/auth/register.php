<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h3>Crear cuenta</h3>
      <form action="<?= $base ?>/register_post" method="POST">
        <div class="mb-3"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Contraseña (min 6)</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-success">Registrar</button>
      </form>
    </div>
  </div>
</div>
