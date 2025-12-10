<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card p-4">
      <h3>Crear receta</h3>
      <form action="<?= $base ?>/recipe_save" method="POST" enctype="multipart/form-data">
        <div class="mb-3"><label>Título</label><input name="title" class="form-control" required></div>
        <div class="mb-3"><label>Categoría</label><select name="category_id" class="form-select"><option value="">Sin categoría</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach;?></select></div>
        <div class="mb-3"><label>Descripción</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="mb-3"><label>Ingredientes (uno por línea)</label><textarea name="ingredients" class="form-control" rows="4"></textarea></div>
        <div class="mb-3"><label>Pasos / Preparación</label><textarea name="steps" class="form-control" rows="6"></textarea></div>
        <div class="mb-3"><label>Imágenes (varias)</label><input type="file" name="images[]" class="form-control" accept="image/*" multiple></div>
        <button class="btn btn-main">Guardar receta</button>
      </form>
    </div>
  </div>
</div>
