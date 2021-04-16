<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="td-title">Abir</td>
                    <td><a href="<?= base_url("courses/class/{$row->id}/{$row->slug}/1") ?>" class="btn btn-sm btn-light w120p" target="_blank">Abrir</a></td>
                </tr>
                <tr>
                    <td class="td-title">ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td class="td-title">Tipo</td>
                    <td><?= $row->type_id ?> &middot; <?= $this->Item_model->name(33, $row->type_id) ?> </td>
                </tr>
                <tr>
                    <td class="td-title">Nombre del curso</td>
                    <td><?= $row->post_name ?></td>
                </tr>
                <tr>
                    <td class="td-title">Estado</td>
                    <td><?= $row->status ?></td>
                </tr>
                <tr>
                    <td class="td-title">slug</td>
                    <td><?= $row->slug ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="td-title">Actualizado por</td>
                    <td><?= $row->updater_id ?> &middot; <?= $this->App_model->name_user($row->updater_id, 'd') ?></td>
                </tr>
                <tr>
                    <td class="td-title">Actualizado</td>
                    <td><?= $row->updated_at ?> &middot; <?= $this->pml->ago($row->updated_at) ?></td>
                </tr>
                <tr>
                    <td class="td-title">Creador</td>
                    <td><?= $row->creator_id ?> &middot; <?= $this->App_model->name_user($row->creator_id, 'd') ?></td>
                </tr>
                <tr>
                    <td class="td-title">Creado</td>
                    <td><?= $row->created_at ?> &middot; <?= $this->pml->ago($row->created_at) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?= $row->post_name ?></h2>
                <div>
                    <h4 class="text-muted">Descripción</h4>
                    <?= $row->excerpt ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">Palabras clave</h4>
                    <?= $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>