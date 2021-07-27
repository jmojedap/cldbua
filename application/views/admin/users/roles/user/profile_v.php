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

<?php
    $qty_login = $this->Db_model->num_rows('events', "user_id = {$row->id} AND type_id = 101");
?>

<div class="center_box_750">
    <div class="row">
        <div class="col col-md-4">
            <!-- Page Widget -->
            <div class="card text-center mb-2">
                <img src="<?= $row->url_image ?>" alt="Imagen del usuario"  onerror="this.src='<?= URL_IMG ?>users/user.png'" class="w100pc">
                <div class="card-body">
                    <h4 class="profile-user"><?= $this->Item_model->name(58, $row->role) ?></h4>

                    <?php if ($this->session->userdata('rol_id') <= 1) { ?>
                        <a href="<?= URL_APP . "accounts/ml/{$row->id}" ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                            <i class="fa fa-sign-in"></i>
                            Acceder
                        </a>
                    <?php } ?>

                </div>
                <div class="card-footer">
                    <div class="row no-space">
                        <div class="col-6">
                            <?php if ( strlen($row->birth_date) > 0 ) { ?>
                                <strong class="profile-stat-count"><?= $this->pml->age($row->birth_date); ?></strong>
                                <span>Años</span>
                            <?php } ?>
                        </div>
                        <div class="col-6">
                            <strong class="profile-stat-count"><?= $qty_login ?></strong>
                            <span>Sesiones</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Widget -->
        </div>
        <div class="col col-md-8">
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td class="text-right" width="25%"><span class="text-muted">No. Documento</span></td>
                        <td>
                            <?= $row->document_number ?>
                            <?= $this->Item_model->name(53, $row->document_type); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Nombre</span></td>
                        <td><?= $row->display_name ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Nombre de usuario</span></td>
                        <td><?= $row->username ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Correo electrónico</span></td>
                        <td><?= $row->email ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Sexo</span></td>
                        <td><?= $this->Item_model->name(59, $row->gender) ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Rol de usuario</span></td>
                        <td><?= $this->Item_model->name(58, $row->role) ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="text-muted">Fecha de nacimiento</span></td>
                        <td><?= $this->pml->date_format($row->birth_date, 'Y-M-d') ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Actualizado</span></td>
                        <td>
                            <?= $this->pml->date_format($row->updated_at, 'Y-m-d h:i') ?> por <?= $this->App_model->name_user($row->updater_id, 'du') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Creado</span></td>
                        <td>
                            <?= $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?= $this->App_model->name_user($row->creator_id, 'du') ?>
                        </td>
                    </tr>
                    <?php if ( $this->session->userdata('role') <= 2  ) { ?>
                        <tr>
                            <td class="text-right">
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
