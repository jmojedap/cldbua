<div class="ml-auto only-lg">
    <div class="btn-group" role="group">
        <a href="<?= URL_APP . 'accounts/profile' ?>" class="btn btn-light" style="min-width: 120px;">
            <i class="bi-person text-main mr-1"></i>
            <?= $this->session->userdata('short_name') ?>
        </a>
        <a href="<?= URL_APP . 'accounts/logout' ?>" class="btn btn-light" title="Cerrar sesión de <?= $this->session->userdata('short_name') ?>">
            Salir
        </a>
    </div>
</div>