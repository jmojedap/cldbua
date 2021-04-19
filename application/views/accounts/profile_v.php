<div class="center_box_920">

        <div class="row">
            <div class="col-md-3">
                <div class="text-center">
                    <img src="<?= $row->url_image ?>" alt="Imagen del usuario" class="w120p border rounded rounded-circle mb-2" onerror="this.src='<?= URL_IMG ?>users/user.png'">
                    <h4><?= $row->display_name ?></h4>
                </div>
            </div>
            <div class="col-md-9">
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
</div>

