<?php
    $options_parent_id = $this->App_model->options_post('type_id = 4110', 'n', 'Seleccione el curso');
?>

<div id="edit_app">
    <div class="center_box_750">
        <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
            <input type="hidden" name="id" value="<?= $row->id ?>">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="parent_id" class="col-md-4 col-form-label text-right">Curso</label>
                        <div class="col-md-8">
                            <select name="parent_id" v-model="form_values.parent_id" class="form-control" required>
                                <option v-for="(option_parent_id, key_parent_id) in options_parent_id" v-bind:value="key_parent_id">{{ option_parent_id }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="related_1" class="col-md-4 col-form-label text-right">Módulo / Orden</label>
                        <div class="col-md-4">
                            <input
                                name="related_1" type="number" class="form-control"
                                required
                                v-model="form_values.related_1"
                            >
                        </div>
                        <div class="col-md-4">
                            <input
                                name="position" type="number" class="form-control"
                                required
                                v-model="form_values.position"
                            >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="post_name" class="col-md-4 col-form-label text-right">Nombre exámen</label>
                        <div class="col-md-8">
                            <input
                                name="post_name" type="text" class="form-control"
                                required
                                title="Nombre clase" placeholder="Nombre clase"
                                v-model="form_values.post_name"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="related_2" class="col-md-4 col-form-label text-right">ID Exámen</label>
                        <div class="col-md-8">
                            <input
                                name="related_2" type="text" class="form-control"
                                required
                                title="ID Exámen" placeholder="ID Exámen"
                                v-model="form_values.related_2"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="integer_1" class="col-md-4 col-form-label text-right">% peso en el curso</label>
                        <div class="col-md-8">
                            <input
                                name="integer_1" type="number" class="form-control" min="1" max="100"
                                required
                                title="% peso en el curso" placeholder="% peso en el curso"
                                v-model="form_values.integer_1"
                            >
                            <small class="form-text text-muted">
                                Si hay un solo examen en el curso, el peso es 100.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="excerpt" class="col-md-4 col-form-label text-right">Descripción del exámen en el curso</label>
                        <div class="col-md-8">
                            <textarea
                                name="excerpt" class="form-control" rows="3" v-model="form_values.excerpt"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var edit_app = new Vue({
    el: '#edit_app',
    data: {
        form_values: {
            post_name: '<?= $row->post_name ?>',
            excerpt: '<?= $row->excerpt ?>',
            parent_id: '0<?= $row->parent_id ?>',
            related_2: '<?= $row->related_2 ?>',
            position: '<?= $row->position ?>',
            related_1: '<?= $row->related_1 ?>',
            integer_1: '<?= $row->integer_1 ?>',
        },
        options_parent_id: <?= json_encode($options_parent_id) ?>,
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'posts/save/', $('#edit_form').serialize())
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
            }).catch(function(error) {console.log(error)})  
        },
    }
})
</script>