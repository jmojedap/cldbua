<div id="edit_exam_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="exam_form" @submit.prevent="send_form">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-right">Título</label>
                        <div class="col-md-8">
                            <input name="title" type="text" class="form-control" required v-model="form_values.title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label text-right">Descripción</label>
                        <div class="col-md-8">
                            <textarea
                                name="description" type="text" class="form-control" maxlength=280 rows="5" required
                                v-model="form_values.description"
                            ></textarea>
                            <small class="form-text text-muted">Disponibles: {{ 280 - form_values.description.length }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="minutes" class="col-md-4 col-form-label text-right">Minutos</label>
                        <div class="col-md-8">
                            <input
                                name="minutes" type="number" class="form-control"
                                required min="1" max="240"
                                v-model="form_values.minutes"
                            >
                            <small class="form-text text-muted">Tiempo disponible para responder el cuestionario</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Vue app
//-----------------------------------------------------------------------------
var edit_exam_app = new Vue({
    el: '#edit_exam_app',
    data: {
        form_values: {
            title: '<?= $row->title ?>',
            description: '<?= $row->description ?>',
            minutes: <?= $row->minutes ?>
        }
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'exams/save/', $('#exam_form').serialize())
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    toastr['success']('Cambios guardados')
                }
            })
            .catch(function(error) {console.log(error)})  
        }
    }
})
</script>