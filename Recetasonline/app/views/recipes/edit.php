<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card p-4">
      <h3>Editar receta</h3>
      <form action="<?= $base ?>/recipe_update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
        <div class="mb-3"><label>Título</label><input name="title" value="<?= htmlspecialchars($recipe['title']) ?>" class="form-control" required></div>
        <div class="mb-3"><label>Categoría</label><select name="category_id" class="form-select"><option value="">Sin categoría</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $recipe['category_id']==$c['id'] ? 'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option><?php endforeach;?></select></div>
        <div class="mb-3"><label>Descripción</label><textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($recipe['description']) ?></textarea></div>
        <div class="mb-3"><label>Ingredientes</label><textarea name="ingredients" class="form-control" rows="4"><?= htmlspecialchars($recipe['ingredients']) ?></textarea></div>
        <div class="mb-3"><label>Pasos</label><textarea name="steps" class="form-control" rows="6"><?= htmlspecialchars($recipe['steps']) ?></textarea></div>
        <div class="mb-3"><label>Agregar imágenes (opcionales)</label><input type="file" name="images[]" class="form-control" accept="image/*" multiple></div>
        <button class="btn btn-main">Actualizar</button>
      </form>

      <form action="<?= $base ?>/recipe_delete" method="POST" class="mt-3" onsubmit="return confirm('Eliminar receta?')">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
        <button class="btn btn-danger">Eliminar receta</button>
      </form>
    </div>
  </div>
</div>
