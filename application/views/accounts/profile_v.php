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
                        Acceder
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
                    <td class="td-title">Nombre</td>
                    <td><?= $row->display_name ?></td>
                </tr>

                <tr>
                    <td class="td-title">Nombre de usuario</td>
                    <td><?= $row->username ?></td>
                </tr>

                <tr>
                    <td class="td-title">Correo electrónico</td>
                    <td><?= $row->email ?></td>
                </tr>

                <tr>
                    <td class="td-title">País residencia</td>
                    <td><?= $this->App_model->place_name($row->city_id); ?></td>
                </tr>

                <tr>
                    <td class="td-title">Acerca de mí</td>
                    <td><?= $row->about; ?></td>
                </tr>

                <tr>
                    <td class="td-title">Rol de usuario</td>
                    <td><?= $this->Item_model->name(58, $row->role) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>