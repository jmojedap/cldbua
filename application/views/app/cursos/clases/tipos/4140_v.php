<div id="exam_class_app">
    <div class="center_box_920">
        <div class="card mb-2">
            <div class="card-body">
                <h3>{{ exam.title }}</h3>
                <p>{{ exam.description }}</p>
                
                <div v-show="answer.id > 0">
                    <hr>
                    <h4><i class="fa fa-info-circle text-warning"></i> Respuesta previa</h4>
                    <table class="table table-borderless">
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
                                    <a v-bind:href="`<?= URL_ADMIN . "exams/results/" ?>` + exam_id + '/' + answer.id + `/` + enrolling_id" class="btn btn-light">Ver detalle</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <p class="text-center">
                        <strong>Advertencia:</strong>  Ya respondiste este cuestionario. Si continúas tus respuestas previas serán reemplazadas.
                    </p>
                </div>

                <div class="text-center">
                    <p>
                        Iniciará a responder el cuestionario. Tienes <strong class="text-primary">{{ exam.minutes }}</strong> minutos para completarlo.
                    </p>

                    <p class="text-center">
                        <button class="btn btn-main btn-lg" v-on:click="start">
                            <span v-show="qty_attempts > 1">REINICIAR Y</span>
                            RESPONDER
                        </button>
                    </p>
                </div>

            </div>
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
        answer: {
            id: 0, updated_at: '', pct_correct: 0
        },
        qty_attempts: 0,
        loading: false,
    },
    methods: {
        get_exam_info: function(){
            axios.get(url_api + 'exams/get_preparation_info/' + this.exam_id)
            .then(response => {
                this.exam = response.data.row
                if ( response.data.row_eu != null ) this.answer = response.data.row_eu
                this.qty_attempts = response.data.qty_attempts
            })
            .catch(function(error) { console.log(error) })
        },
        start: function(){
            var form_data = new FormData
            form_data.append('exam_id', this.exam_id)
            form_data.append('enrolling_id', this.enrolling_id)
            form_data.append('qty_attempts', this.qty_attempts)
            
            axios.post(url_api + 'exams/start/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    window.location = url_front + 'examenes/resolver/' + this.exam_id + '/' + response.data.saved_id + '/' + this.enrolling_id
                }
            })
            .catch(function(error) { console.log(error)} )
        },
    }
})
</script>