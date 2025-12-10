<div class="card p-3">
  <h3>Usuarios</h3>
  <table class="table table-sm">
    <thead><tr><th>#</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Creado</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['role_name'] ?? 'user') ?></td>
          <td><?= $u['created_at'] ?></td>
          <td>
            <form action="<?= $base ?>/admin_delete_user" method="POST" style="display:inline" onsubmit="return confirm('Eliminar usuario?')">
              <input type="hidden" name="id" value="<?= $u['id'] ?>">
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
