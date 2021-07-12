<?php
    $num_modulo = 0;
?>
<div class="list-group">
    <?php foreach ( $classes->result() as $index_class => $class ) : ?>
        <?php
            $num_class_list = $index_class + 1;
            $cl_class = $this->pml->active_class($index_class, $index, 'active');
        ?>
        <?php if ( $class->related_1 != $num_modulo ) : ?>
            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Módulo <?= $class->related_1 ?></a>
        <?php endif; ?>
        <a href="<?= URL_ADMIN . "courses/open_element/{$course->id}/{$index_class}" ?>" class="list-group-item list-group-item-action <?= $cl_class ?>">
            <span class="badge badge-secondary mr-2"><?= $num_class_list ?></span>
            <?= $class->post_name ?>
        </a>
        <?php
            //Siguiente ciclo
            $num_modulo = $class->related_1;
        ?>
    <?php endforeach ?>
  
</div>