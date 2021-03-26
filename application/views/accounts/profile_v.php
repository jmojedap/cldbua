<div class="row">
    <div class="col col-md-3">
        <!-- Page Widget -->
        <div class="card text-center mb-2">
            <img src="<?= $row->url_image ?>" alt="Imagen del usuario" class="w100pc" onerror="this.src='<?= URL_IMG ?>users/user.png'">
            <div class="card-body">
                <h4 class="profile-user"><?= $this->Item_model->name(58, $row->role) ?></h4>

                <?php if ($this->session->userdata('role') <= 1) { ?>
                    <a href="<?= base_url("admin/ml/{$row->id}") ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                        <i class="fa fa-sign-in"></i>
                        Accetd-title
                    </a>
                <?php } ?>
            </div>
        </div>
        <!-- End Page Widget -->
    </div>
    <div class="col col-md-9">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="td-title"><span class="text-muted">Nombre</span></td>
                    <td><?= $row->display_name ?></td>
                </tr>

                <tr>
                    <td class="td-title"><span class="text-muted">Nombre de usuario</span></td>
                    <td><?= $row->username ?></td>
                </tr>

                <tr>
                    <td class="td-title"><span class="text-muted">Correo electrónico</span></td>
                    <td><?= $row->email ?></td>
                </tr>

                <tr>
                    <td class="td-title"><span class="text-muted">Rol de usuario</span></td>
                    <td><?= $this->Item_model->name(58, $row->role) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>