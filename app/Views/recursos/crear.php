<?= $header ?>
<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Recurso</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('recursos/guardar') ?>" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="anio" class="form-label">Año:</label>
                    <input type="number" id="anio" name="anio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="numpaginas" class="form-label">Número de Páginas:</label>
                    <input type="number" id="numpaginas" name="numpaginas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="encuadernacion" class="form-label">Encuadernación:</label>
                    <input type="text" id="encuadernacion" name="encuadernacion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="numedicion" class="form-label">Número de Edición:</label>
                    <input type="number" id="numedicion" name="numedicion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="">Seleccione una opción</option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= esc($estado) ?>"><?= esc($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="number" id="stock" name="stock" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Guardar</button>
            </form>
        </div>
    </div>
</div>
<?= $footer ?>