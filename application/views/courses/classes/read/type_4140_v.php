<div id="exam_class_app">
    <div class="center_box_750">
        <div class="card mb-2">
            <div class="card-body">
                <h3>{{ exam.title }}</h3>
                <p>{{ exam.description }}</p>
                <hr>

                Iniciará a responder el cuestionario. Tiene <strong class="text-primary">{{ exam.minutes }}</strong> minutos para responderlo.
                
                <div v-show="answer != null">
                    <hr>
                    <h4><i class="fa fa-info-circle text-info"></i> Respuesta previa</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="td-title">Fecha respuesta</td>
                                <td>
                                    {{ answer.updated_at }}
                                    &middot;
                                    {{ answer.updated_at | ago }}
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title">% correctas</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                                            v-bind:style="`width: ` + answer.pct_correct + `%;`"
                                            v-bind:class="{'bg-success': answer.approved == 1, 'bg-danger': answer.approved == 0 }"
                                            >
                                            {{ answer.pct_correct }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title">Resultado</td>
                                <td>
                                    <span v-show="answer.approved == 0"><i class="fa fa-times text-danger"></i> No aprobado</span>
                                    <span v-show="answer.approved == 1"><i class="fa fa-check text-success"></i> Aprobado</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title"></td>
                                <td>
                                    <a href="<?= base_url("exams/results/{$row->id}/{$answer->id}") ?>" class="btn btn-light">Ver detalle</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <p><strong>Información</strong></p>
                    <p>
                        Usted ya respondió este cuestionario. Si continúa sus respuestas previas serán reemplazadas.
                    </p>
                </div>

                <p class="text-center">
                    <button class="btn btn-success btn-lg" v-on:click="start">
                        <span v-show="qty_attempts > 1">REINICIAR Y</span>
                        RESPONDER
                    </button>
                </p>

            </div>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <a class="btn btn-secondary w120p" title="Clase anterior" href="<?= base_url("courses/open_element/{$course->id}/") . ($index - 1) ?>"><i class="fa fa-chevron-left"></i></a>
            <a class="btn btn-secondary w120p" title="Clase siguiente"  href="<?= base_url("courses/open_element/{$course->id}/") . ($index + 1) ?>"><i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</div>

<script>
// Filtros
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow()
})

// VueApp
//-----------------------------------------------------------------------------
var exam_class_app = new Vue({
    el: '#exam_class_app',
    created: function(){
        this.get_exam_info()
    },
    data: {
        enrolling_id: <?= $enrolling_id ?>,
        exam_id: <?= $row->related_2 ?>,
        exam: {},
        answer: {},
        qty_attempts: 0,
        loading: false,
    },
    methods: {
        get_exam_info: function(){
            axios.get(url_api + 'exams/get_preparation_info/' + this.exam_id)
            .then(response => {
                this.exam = response.data.row
                this.answer = response.data.row_eu
                this.qty_attempts = response.data.qty_attempts
            })
            .catch(function(error) { console.log(error) })
        },
        start: function(){
            var form_data = new FormData
            form_data.append('exam_id', this.exam_id)
            form_data.append('qty_attempts', this.qty_attempts)
            axios.post(url_api + 'exams/start/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    window.location = url_app + 'exams/resolve/' + this.exam_id + '/' + response.data.saved_id + '/' + this.enrolling_id
                }
            })
            .catch(function(error) {console.log(error)})  
        },
    }
})
</script>

<div class="center_box_750">
    <!-- Menú Tab Contenido -->
    <ul class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="nav-classes-list-tab" data-toggle="tab" href="#nav-classes-list" role="tab" aria-controls="nav-comments" aria-selected="false">Clases</a>
        </li>
    </ul>
    <?php $this->load->view('courses/classes/read/classes_list_v') ?>
</div>