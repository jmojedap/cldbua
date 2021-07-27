<div id="summary_app">
    <div class="center_box_750">
        <div class="row">
            <div class="col-md-6">
                <a href="<?= URL_ADMIN . "users/explore" ?>">
                    <div class="card mb-2">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body text-left w-100">
                                        <h3 class="text-color-2">{{ summary.students.num_rows }}</h3>
                                        <span>Estudiantes registrados</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <i class="fa fa-users fa-3x float-right text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <div class="card mb-2">
                    <a href="<?= URL_ADMIN . "posts/explore/1/?type=04110" ?>">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body text-left w-100">
                                        <h3 class="text-color-2">{{ summary.courses.num_rows }}</h3>
                                        <span>Cursos activos</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <i class="fa fa-book fa-3x float-right text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <h3>Enlaces</h3>
        <ul>
            <li>
                <a href="<?= URL_APP . "cursos/catalogo" ?>">Catálogo de cursos</a>
            </li>
        </ul>
    </div>
</div>

<script>
var summary_app = new Vue({
    el: '#summary_app',
    created: function(){
        //this.get_list()
    },
    data: {
        summary: <?= json_encode($summary) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>