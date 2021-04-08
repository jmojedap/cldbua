<div class="center_box_920">
    <div id="clase_app">
        <div class="youtube-cointainer">
            <iframe width="100%"
                src="https://www.youtube.com/embed/<?= $clase->text_1 ?>?rel=0&showinfo=0&controls=1&autoplay=1"
                title="<?= $clase->post_name ?>" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <a class="btn btn-secondary w120p" href="<?= base_url("courses/open_element/{$course->id}/") . ($index - 1) ?>"><i class="fa fa-chevron-left"></i></a>
            <a class="btn btn-secondary w120p"  href="<?= base_url("courses/open_element/{$course->id}/") . ($index + 1) ?>"><i class="fa fa-chevron-right"></i></a>
        </div>
        <h2><?= $clase->post_name ?></h2>
        <p>Módulo <?= $clase->integer_1 ?> &middot; Clase <?= $clase->position ?></p>

    </div>
    
    <ul class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Contenido</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Comentarios (<?= $row->qty_comments ?>)</a>
        </li>
    </ul>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body">
                    <div><?= $clase->content ?></div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card">
                <div class="card-body">
                    <?php $this->load->view('comments/section/section_v') ?>
                </div>
            </div>
        </div>
    </div>
</div>