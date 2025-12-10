<div class="row">
  <div class="col-lg-8">
    <div class="card mb-3">
      <?php $mainImg = $images[0]['filename'] ?? null; $imgUrl = $mainImg ? $uploads_url . '/' . $mainImg : $base . '/css/placeholder.png'; ?>
      <img src="<?= $imgUrl ?>" class="card-img-top" style="max-height:420px;object-fit:cover;">
      <div class="card-body">
        <h2><?= htmlspecialchars($recipe['title']) ?></h2>
        <p class="text-muted">Por <?= htmlspecialchars($recipe['author']) ?> • <?= date('Y-m-d', strtotime($recipe['created_at'])) ?></p>
        <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>

        <h5>Ingredientes</h5>
        <ul><?php foreach(explode("\n",$recipe['ingredients']) as $ing): if(trim($ing)=='') continue; ?><li><?= htmlspecialchars($ing) ?></li><?php endforeach; ?></ul>

        <h5>Preparación</h5>
        <div><?= nl2br(htmlspecialchars($recipe['steps'])) ?></div>

        <div class="mt-3">
          <?php if(!empty($_SESSION['user'])): ?>
            <form action="<?= $base ?>/favorite_toggle" method="POST" style="display:inline">
              <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
              <button class="btn <?= $isFav ? 'btn-outline-light' : 'btn-main' ?>"><?= $isFav ? 'Quitar favorito' : 'Agregar a favoritos' ?></button>
            </form>
            <?php if($_SESSION['user']['id']==$recipe['user_id'] || $_SESSION['user']['role_id']==2): ?>
              <a href="<?= $base ?>/recipe_edit?id=<?= $recipe['id'] ?>" class="btn btn-secondary">Editar</a>
            <?php endif; ?>
          <?php else: ?>
            <a href="<?= $base ?>/login" class="btn btn-light">Inicia sesión para interactuar</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if(count($images)>1): ?>
      <div class="mb-4"><h5>Galería</h5><div class="d-flex gap-2 flex-wrap"><?php foreach($images as $im): ?><img src="<?= $uploads_url . '/' . $im['filename'] ?>" style="height:80px;object-fit:cover;border-radius:8px;"><?php endforeach; ?></div></div>
    <?php endif; ?>

    <div class="card p-3">
      <h5>Comentarios</h5>
      <?php if(!empty($_SESSION['user'])): ?>
        <form id="commentForm" onsubmit="return false;">
          <div class="mb-2">
            <textarea id="commentText" class="form-control" rows="3" placeholder="Escribe un comentario..."></textarea>
          </div>
          <button class="btn btn-sm btn-light" onclick="postComment(<?= $recipe['id'] ?>)">Publicar</button>
        </form>
      <?php else: ?>
        <p><a href="<?= $base ?>/login">Inicia sesión</a> para comentar.</p>
      <?php endif; ?>

      <hr>

      <?php foreach($comments as $c): ?>
        <div class="mb-2"><strong><?= htmlspecialchars($c['author']) ?></strong> <small class="text-muted"><?= $c['created_at'] ?></small><div><?= nl2br(htmlspecialchars($c['content'])) ?></div></div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card p-3">
      <h5>Acerca del autor</h5>
      <p><?= htmlspecialchars($recipe['author']) ?></p>
      <hr>
      <h6>Otras recetas</h6>
      <p>Próximamente...</p>
    </div>
  </div>
</div>
