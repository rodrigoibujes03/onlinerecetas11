// public/js/main.js
function postComment(recipeId) {
  const txt = document.getElementById('commentText').value.trim();
  if (!txt) return alert('Escribe algo...');
  fetch(window.location.origin + '<?= BASE_URL ?>/comment_post'.replace('/Recetasonline/public',''), {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({recipe_id: recipeId, content: txt})
  }).then(r => r.json()).then(data => {
    if (data.ok) location.reload();
    else alert('Error al publicar');
  }).catch(e => alert('Error: ' + e));
}

// filter by category
function filterByCategory(){
  const val = document.getElementById('categoryFilter').value;
  const base = '<?= rtrim(BASE_URL,"/") ?>';
  if (val) window.location.href = base + '/recipes?category=' + val;
  else window.location.href = base + '/recipes';
}
