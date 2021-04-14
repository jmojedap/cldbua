<div class="center_box_920">
    <div id="clase_app">
        <div class="youtube-cointainer">
            <iframe width="100%"
                src="https://www.youtube.com/embed/<?= $clase->text_1 ?>?rel=0&showinfo=0&controls=1&autoplay=0"
                title="<?= $clase->post_name ?>" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <a class="btn btn-secondary w120p" title="Clase anterior" href="<?= base_url("courses/open_element/{$course->id}/") . ($index - 1) ?>"><i class="fa fa-chevron-left"></i></a>
            <a class="btn btn-secondary w120p" title="Clase siguiente"  href="<?= base_url("courses/open_element/{$course->id}/") . ($index + 1) ?>"><i class="fa fa-chevron-right"></i></a>
        </div>
        <h2><?= $clase->post_name ?></h2>
        <p>Módulo <?= $clase->related_1 ?> &middot; Clase <?= $num_class ?></p>
    </div>
    
    <!-- Menú Tab Contenido -->
    <ul class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="nav-content-tab" data-toggle="tab" href="#nav-content" role="tab" aria-controls="nav-content" aria-selected="true">Contenido</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-comments-tab" data-toggle="tab" href="#nav-comments" role="tab" aria-controls="nav-comments" aria-selected="false">Comentarios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-classes-list-tab" data-toggle="tab" href="#nav-classes-list" role="tab" aria-controls="nav-comments" aria-selected="false">Clases</a>
        </li>
    </ul>

    <!-- Contenidos asociados a tabs -->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-content" role="tabpanel" aria-labelledby="nav-content-tab">
            <div class="card">
                <div class="card-body">
                    <div><?= $clase->content ?></div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-comments" role="tabpanel" aria-labelledby="nav-comments-tab">
            <?php $this->load->view('comments/section/section_v') ?>
        </div>
        <div class="tab-pane fade" id="nav-classes-list" role="tabpanel" aria-labelledby="nav-classes-list">
            <?php $this->load->view('courses/classes/read/classes_list_v') ?>
        </div>
    </div>
</div>