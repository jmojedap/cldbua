<div id="add_question_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="question_form" @submit.prevent="send_form">

                    <div class="form-group row">
                        <label for="exam_id" class="col-md-4 col-form-label text-right">ID Examen</label>
                        <div class="col-md-8">
                            <input
                                name="exam_id" type="number" class="form-control"
                                required min="1"
                                title="ID Examen" placeholder="ID Examen"
                                v-model="form_values.exam_id"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="position" class="col-md-4 col-form-label text-right">Orden</label>
                        <div class="col-md-8">
                            <input
                                name="position" type="number" class="form-control"
                                required min="0"
                                title="Orden" placeholder="Orden"
                                v-model="form_values.position"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="question_text" class="col-md-4 col-form-label text-right">Texto pregunta</label>
                        <div class="col-md-8">
                            <textarea
                                name="question_text" type="text" class="form-control" rows="5" required
                                v-model="form_values.question_text"
                            ></textarea>
                        </div>
                    </div>

                    <!-- OPCIONES DE RESPUESTA -->
                    <div class="form-group row">
                        <label for="option_1" class="col-md-4 col-form-label text-right">Opción A</label>
                        <div class="col-md-8">
                            <textarea name="option_1" class="form-control" rows="2" required v-model="form_values.option_1"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_2" class="col-md-4 col-form-label text-right">Opción B</label>
                        <div class="col-md-8">
                            <textarea name="option_2" class="form-control" rows="2" required v-model="form_values.option_2"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_3" class="col-md-4 col-form-label text-right">Opción C</label>
                        <div class="col-md-8">
                            <textarea name="option_3" class="form-control" rows="2" v-model="form_values.option_3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_4" class="col-md-4 col-form-label text-right">Opción D</label>
                        <div class="col-md-8">
                            <textarea name="option_4" class="form-control" rows="2" v-model="form_values.option_4"></textarea>
                        </div>
                    </div>                

                    <div class="form-group row">
                        <label for="correct_option" class="col-md-4 col-form-label text-right">Respuesta correcta</label>
                        <div class="col-md-8">
                            <select name="correct_option" v-model="form_values.correct_option" class="form-control" required>
                                <option v-for="(option_correct_option, key_correct_option) in options_correct_option" v-bind:value="key_correct_option">{{ option_correct_option }}</option>
                            </select>
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
var add_question_app = new Vue({
    el: '#add_question_app',
    data: {
        form_values: {
            exam_id: 0,
            position: 0,
            question_text: '',
            option_1: '',
            option_2: '',
            option_3: '',
            option_4: '',
            correct_option: '',
        },
        options_correct_option: {'01': 'Opción A', '02': 'Opción B', '03': 'Opción C', '04': 'Opción D'},
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'questions/save/', $('#question_form').serialize())
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
            this.form_values.question_text = ''
        },
        go_created: function() {
            window.location = url_app + 'questions/edit/' + this.row_id;
        }
    }
})
</script>