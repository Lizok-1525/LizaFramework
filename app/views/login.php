<div class="container intro">
    <h2>Iniciar Sesión</h2>
    <?php if (!empty($error)): ?>
        <p class="error" style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form class="row" method="POST" action="">
        <div class="row">
            <label for="name" class="form-label m-2">Nombre de usuario</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="John Jayson" required>
        </div>
        <div class="row">
            <label for="email" class="form-label m-2">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
        </div>
        <div class="row">
            <label for="password" class="form-label m-2">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="row">
            <button type="submit" class="btn btn-outline-secondary m-3">Confirm identity</button>
        </div>
    </form>
</div>