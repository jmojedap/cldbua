<?php
    $cl_sections['content'] = 'show active';
    $cl_sections['comments'] = '';
    $cl_sections['classes'] = '';

    //Si es examen
    if ( $clase->type_id == 4140) {
        $cl_sections['content'] = 'd-none';
        $cl_sections['comments'] = 'd-none';
        $cl_sections['classes'] = 'show active';
    }
?>

<div class="center_box_920">
    <?php $this->load->view("app/cursos/clases/tipos/{$clase->type_id}_v") ?>

    <div class="d-flex justify-content-between mb-2">
        <a class="btn btn-secondary w120p" title="Clase anterior" href="<?= URL_APP . "cursos/abrir_elemento/{$course->id}/" . ($index - 1) ?>"><i class="fa fa-chevron-left"></i></a>
        <a class="btn btn-secondary w120p" title="Clase siguiente"  href="<?= URL_APP . "cursos/abrir_elemento/{$course->id}/" . ($index + 1) ?>"><i class="fa fa-chevron-right"></i></a>
    </div>

    <h2><?= $clase->post_name ?></h2>
    <p>Módulo <?= $clase->related_1 ?> &middot; Clase <?= $num_class ?></p>
    
    <!-- Menú Tab Contenido -->
    <ul class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= $cl_sections['content'] ?>" id="nav-content-tab" data-toggle="tab" href="#nav-content" role="tab" aria-controls="nav-content" aria-selected="true">Contenido</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $cl_sections['comments'] ?>" id="nav-comments-tab" data-toggle="tab" href="#nav-comments" role="tab" aria-controls="nav-comments" aria-selected="false">Comentarios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $cl_sections['classes'] ?>" id="nav-classes-list-tab" data-toggle="tab" href="#nav-classes-list" role="tab" aria-controls="nav-comments" aria-selected="false">Clases</a>
        </li>
    </ul>

    <!-- Contenidos asociados a tabs -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade <?= $cl_sections['content'] ?>" id="nav-content" role="tabpanel" aria-labelledby="nav-content-tab">
            <div class="card">
                <div class="card-body">
                    <div><?= $clase->content ?></div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade <?= $cl_sections['comments'] ?>" id="nav-comments" role="tabpanel" aria-labelledby="nav-comments-tab">
            <?php $this->load->view('admin/comments/section/section_v') ?>
        </div>
        <div class="tab-pane fade <?= $cl_sections['classes'] ?>" id="nav-classes-list" role="tabpanel" aria-labelledby="nav-classes-list">
            <?php $this->load->view('app/cursos/clases/clases_lista_v') ?>
        </div>
    </div>
</div>