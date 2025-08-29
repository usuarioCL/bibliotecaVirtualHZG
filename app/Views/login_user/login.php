<?= $header ?>
    <div class="m-5">
        <?= $navbar ?>
        <div class="container mt-5 ">
            <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif; ?>
            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <h1 class="mb-4 text-center">Login</h1>
                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="nomuser" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="passuser" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $footer ?>