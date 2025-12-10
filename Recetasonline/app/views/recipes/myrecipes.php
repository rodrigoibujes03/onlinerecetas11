<h3>Mis recetas</h3>
<div class="row">
  <?php if(empty($recipes)): ?><div class="col-12 alert alert-light">No tienes recetas.</div><?php endif; ?>
  <?php foreach($recipes as $r): ?>
    <div class="col-md-4 mb-3">
      <div class="card recipe-card">
        <?php $img = null; $imgUrl = $img ? ($uploads_url . '/' . $img) : ($base . '/css/placeholder.png'); ?>
        <img src="<?= $imgUrl ?>" class="card-img-top" style="height:180px;object-fit:cover;">
        <div class="card-body">
          <h5><?= htmlspecialchars($r['title']) ?></h5>
          <a href="<?= $base ?>/recipe?id=<?= $r['id'] ?>" class="btn btn-main">Ver</a>
          <a href="<?= $base ?>/recipe_edit?id=<?= $r['id'] ?>" class="btn btn-secondary">Editar</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
