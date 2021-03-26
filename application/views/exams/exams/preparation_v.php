<div id="preparation_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <h3><?= $row->title ?></h3>
                <p><?= $row->description ?></p>
                <hr>

                Iniciará a responder el cuestionario. Tiene <strong class="text-primary"><?= $row->minutes ?></strong> para completarlo.

                <?php if ( ! is_null($row_eu) ) : ?>
                    <hr>
                    <h4><i class="fa fa-info-circle text-info"></i> Respuesta previa</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="td-title">Fecha respuesta</td>
                                <td><?= $row_eu->updated_at ?></td>
                            </tr>
                            <tr>
                                <td class="td-title">Hace</td>
                                <td><?= $this->pml->ago($row_eu->updated_at) ?></td>
                            </tr>
                            <tr>
                                <td class="td-title">% correctas</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar"
                                            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                                            v-bind:style="`width: ` + row_eu.pct_correct + `%;`"
                                            v-bind:class="{'bg-success': row_eu.approved == 1, 'bg-danger': row_eu.approved == 0 }"
                                            >
                                            {{ row_eu.pct_correct }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title">Resultado</td>
                                <td>
                                    <span v-show="row_eu.approved == 0"><i class="fa fa-times text-danger"></i> No aprobado</span>
                                    <span v-show="row_eu.approved == 1"><i class="fa fa-check text-success"></i> Aprobado</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title"></td>
                                <td>
                                    <a href="<?= base_url("exams/results/{$row->id}/{$row_eu->id}") ?>" class="btn btn-light">Ver detalle</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <p><strong>Información</strong></p>
                    <p>
                        Usted ya respondió este cuestionario. Si continúa sus respuestas previas serán reemplazadas.
                    </p>
                    
                <?php endif; ?>

                <p class="text-center">
                    <button class="btn btn-success btn-lg" v-on:click="start">
                        <span v-show="qty_attempts > 1">REINICIAR Y</span>
                        RESPONDER
                    </button>
                </p>

            </div>
        </div>
    </div>
</div>

<script>
var preparation_app = new Vue({
    el: '#preparation_app',
    created: function(){
        //this.get_list()
    },
    data: {
        exam_id: <?= $row->id ?>,
        qty_attempts: <?= $qty_attempts ?>,
        row_eu: <?= json_encode($row_eu) ?>
    },
    methods: {
        start: function(){
            var form_data = new FormData
            form_data.append('exam_id', this.exam_id)
            form_data.append('qty_attempts', this.qty_attempts)
            axios.post(url_api + 'exams/start/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    window.location = url_app + 'exams/resolve/' + this.exam_id + '/' + response.data.saved_id
                }
            })
            .catch(function(error) {console.log(error)})  
        },
    }
})
</script>
