<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="text-white">Recetas</h2>
  <div style="width:260px;">
    <select id="categoryFilter" class="form-select" onchange="filterByCategory()">
      <option value="">Todas</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="row">
  <?php if(empty($recipes)): ?>
    <div class="col-12"><div class="alert alert-light">No hay recetas aún.</div></div>
  <?php endif; ?>

  <?php foreach($recipes as $r): ?>
    <div class="col-md-4 mb-4">
      <div class="card recipe-card">
        <?php $img = $r['image'] ?? null; $imgUrl = $img ? ($uploads_url . '/' . $img) : ($base . '/css/placeholder.png'); ?>
        <img src="<?= $imgUrl ?>" class="card-img-top" alt="<?= htmlspecialchars($r['title']) ?>">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($r['title']) ?></h5>
          <p class="card-text text-truncate"><?= htmlspecialchars(substr($r['description'],0,120)) ?></p>
          <a href="<?= $base ?>/recipe?id=<?= $r['id'] ?>" class="btn btn-main w-100">Ver receta</a>
        </div>
        <div class="card-footer d-flex justify-content-between small">
          <span>Por <?= htmlspecialchars($r['author'] ?? 'Anon') ?></span>
          <span><?= date('Y-m-d', strtotime($r['created_at'] ?? 'now')) ?></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
