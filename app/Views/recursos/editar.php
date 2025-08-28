<?= $header ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Recurso</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('recursos/actualizar/'.$recurso['idrecurso']) ?>" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" value="<?= esc($recurso['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="anio" class="form-label">Año:</label>
                    <input type="number" id="anio" name="anio" class="form-control" value="<?= esc($recurso['anio']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="numpaginas" class="form-label">Número de Páginas:</label>
                    <input type="number" id="numpaginas" name="numpaginas" class="form-control" value="<?= esc($recurso['numpaginas']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="encuadernacion" class="form-label">Encuadernación:</label>
                    <input type="text" id="encuadernacion" name="encuadernacion" class="form-control" value="<?= esc($recurso['encuadernacion']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" value="<?= esc($recurso['isbn']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="numedicion" class="form-label">Número de Edición:</label>
                    <input type="number" id="numedicion" name="numedicion" class="form-control" value="<?= esc($recurso['numedicion']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value=""><?= esc($recurso['estado']) ?></option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= esc($estado)  ?>"><?= esc($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="<?= esc($recurso['stock']) ?>" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Actualizar</button>
                <button type="button" class="btn btn-danger w-100" onclick="window.history.back();">Cancelar</button>
            </form>
        </div>
    </div>
</div>

<?= $footer ?>