<div id="edit_question_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="question_form" @submit.prevent="send_form">
                    <input type="hidden" name="id" value="<?= $row->id ?>">

                    <div class="form-group row">
                        <label for="exam_id" class="col-md-4 col-form-label text-right">ID Examen</label>
                        <div class="col-md-8">
                            <input
                                name="exam_id" type="text" class="form-control" min="1"
                                required
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
                        <label for="question_text" class="col-md-4 col-form-label text-right">Descripción</label>
                        <div class="col-md-8">
                            <textarea
                                name="question_text" type="text" class="form-control" rows="5" required
                                v-model="form_values.question_text"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="option_1" class="col-md-4 col-form-label text-right">Opción A</label>
                        <div class="col-md-8">
                            <textarea
                                name="option_1" type="text" class="form-control" rows="2" 
                                v-model="form_values.option_1"
                            ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_2" class="col-md-4 col-form-label text-right">Opción B</label>
                        <div class="col-md-8">
                            <textarea
                                name="option_2" type="text" class="form-control" rows="2" 
                                v-model="form_values.option_2"
                            ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_3" class="col-md-4 col-form-label text-right">Opción C</label>
                        <div class="col-md-8">
                            <textarea
                                name="option_3" type="text" class="form-control" rows="2" 
                                v-model="form_values.option_3"
                            ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="option_4" class="col-md-4 col-form-label text-right">Opción D</label>
                        <div class="col-md-8">
                            <textarea
                                name="option_4" type="text" class="form-control" rows="2" 
                                v-model="form_values.option_4"
                            ></textarea>
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

var edit_question_app = new Vue({
    el: '#edit_question_app',
    data: {
        form_values: {
            exam_id: <?= $row->exam_id ?>,
            position: <?= $row->position ?>,
            question_text: '<?= $row->question_text ?>',
            option_1: '<?= $row->option_1 ?>',
            option_2: '<?= $row->option_2 ?>',
            option_3: '<?= $row->option_3 ?>',
            option_4: '<?= $row->option_4 ?>',
            correct_option: '0<?= $row->correct_option ?>',
        },
        options_correct_option: {'01': 'Opción A', '02': 'Opción B', '03': 'Opción C', '04': 'Opción D'},
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'questions/save/', $('#question_form').serialize())
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