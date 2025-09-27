<section class="card">
  <h2>Iniciar Sesión</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="/login" class="form">
    <label>Nombre</label>
    <input type="text" name="name" placeholder="Tu nombre" required>
    <button type="submit" class="btn btn-primary">Entrar</button>
  </form>
</section>
