<div id="add_exam_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="exam_form" @submit.prevent="send_form">
                    
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
                            <button class="btn btn-success w120p" type="submit">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->load->view('common/modal_created_v') ?>
</div>

<script>
var add_exam_app = new Vue({
    el: '#add_exam_app',
    data: {
        form_values: { title: '', description: '', minutes: 30}
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'exams/save/', $('#exam_form').serialize())
            .then(response => {
                console.log(response.data)
                if ( response.data.saved_id > 0 )
                {
                    this.row_id = response.data.saved_id
                    this.clean_form()
                    $('#modal_created').modal()
                }
            })
            .catch(function(error) {console.log(error)})  
        },
        clean_form: function() {
            this.form_values.title = 0
            this.form_values.description = ''
        },
        go_created: function() {
            window.location = url_app + 'exams/edit/' + this.row_id;
        }
    }
})
</script>