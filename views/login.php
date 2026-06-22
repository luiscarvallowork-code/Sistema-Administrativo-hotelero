<div class="modal-inicio-sesion">
    <h2 class="modal-inicio-sesion__titulo">Acceso de Administrador</h2>

    <?php if (isset($GLOBALS['login_error'])): ?>
        <p class="modal-inicio-sesion__error"><?php echo $GLOBALS['login_error']; ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <p class="modal-inicio-sesion__exito">Ya estás autenticado como Administrador.</p>
        <a href="index.php?action=logout" class="btn btn--danger">Cerrar Sesión de Admin</a>
    <?php else: ?>
        <form action="" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="grupo-campo">
                <label for="password" class="etiqueta">Introduce la contraseña:</label>
                <input type="password" name="password" id="password" class="input" required>
            </div>
            <button type="submit" class="btn btn--primary">Desbloquear Funciones</button>
        </form>
    <?php endif; ?>
</div>
