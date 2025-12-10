<h3>Favoritos</h3>
<div class="row">
  <?php if(empty($recipes)): ?><div class="col-12 alert alert-light">No tienes favoritos.</div><?php endif; ?>
  <?php foreach($recipes as $r): ?>
    <div class="col-md-4 mb-3">
      <div class="card recipe-card">
        <?php $img = $r['image'] ?? null; $imgUrl = $img ? $uploads_url . '/' . $img : $base . '/css/placeholder.png'; ?>
        <img src="<?= $imgUrl ?>" class="card-img-top" style="height:180px;object-fit:cover;">
        <div class="card-body">
          <h5><?= htmlspecialchars($r['title']) ?></h5>
          <a href="<?= $base ?>/recipe?id=<?= $r['id'] ?>" class="btn btn-main">Ver</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
