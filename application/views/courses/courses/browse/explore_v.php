<div id="app_explore">
    <div class="row">
        <div class="col-md-5">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>

        <div class="col-md-3">
            <button type="button" class="btn btn-info" title="Quitar los filtros de búsqueda"
                v-if="active_filters" v-on:click="remove_filters">
                <i class="fa fa-filter"></i>
            </button>
        </div>
        
        <div class="col-md-4 mb-2 text-right">
            <a class="btn text-muted">
                {{ search_num_rows }} resultados &middot; Pág {{ num_page }} / {{ max_page }}
            </a>
            <?php $this->load->view('common/vue_pagination_v'); ?>
        </div>
    </div>

    <?php $this->load->view($views_folder . 'list_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>