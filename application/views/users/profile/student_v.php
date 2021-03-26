<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?= $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_set_activation_key').click(function(){
            set_activation_key();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    function set_activation_key(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'users/set_activation_key/' + user_id,
            success: function(response){
                link_content = url_app + 'accounts/recover/' + response
                link_content += '<br> <span class="text-muted"></span>'
                $('#activation_key').html(link_content)
                toastr['success']('Copie el link y ábralo en otro navegador para establecer una nueva contraseña')
            }
        });
    }
</script>

<div class="container">
    <div class="row">
        <div class="col col-md-4">
            <!-- Page Widget -->
            <div class="card text-center">
                <img src="<?= $row->url_image ?>" alt="Imagen del usuario"  onerror="this.src='<?= URL_IMG ?>users/user.png'" class="w100pc">
                <div class="card-body">
                    <h4 class="profile-user"><?= $this->Item_model->name(58, $row->role) ?></h4>

                    <?php if ($this->session->userdata('rol_id') <= 1) { ?>
                        <a href="<?= base_url("admin/ml/{$row->id}") ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                            <i class="fa fa-sign-in"></i>
                            Acceder
                        </a>
                    <?php } ?>

                </div>
            </div>
            <!-- End Page Widget -->
        </div>
        <div class="col col-md-8">
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td class="td-title">Nombre</td>
                        <td><?= $row->display_name ?></td>
                    </tr>

                    <tr>
                        <td class="td-title">Username</td>
                        <td><?= $row->username ?></td>
                    </tr>

                    <tr>
                        <td class="td-title">Correo electrónico</td>
                        <td><?= $row->email ?></td>
                    </tr>

                    <tr>
                        <td class="td-title">Rol de usuario</td>
                        <td><?= $this->Item_model->name(58, $row->role) ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Notas privadas</td>
                        <td><?= $row->admin_notes ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Acerca de mí</td>
                        <td><?= $row->about ?></td>
                    </tr>

                    <tr>
                        <td class="td-title">Actualizado</td>
                        <td>
                            <?= $this->pml->date_format($row->updated_at, 'Y-m-d h:i') ?> por <?= $this->App_model->name_user($row->updater_id, 'du') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Creado</td>
                        <td>
                            <?= $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?= $this->App_model->name_user($row->creator_id, 'du') ?>
                        </td>
                    </tr>
                    <?php if ( $this->session->userdata('role') <= 2  ) { ?>
                        <tr>
                            <td class="td-title">
                                <button class="btn btn-primary btn-sm" id="btn_set_activation_key">
                                    <i class="fa fa-redo-alt"></i>
                                </button>
                                <span class="text-muted"></span>
                            </td>
                            <td>
                                <span id="activation_key">Restaurar contraseña</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
