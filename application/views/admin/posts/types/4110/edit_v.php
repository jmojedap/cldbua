<?php
    $options_status = $this->Item_model->options('category_id = 42');
?>

<div id="edit_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <div class="form-group row">
                        <label for="post_name" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="post_name" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="form_values.post_name"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="excerpt" class="col-md-4 col-form-label text-right">Descripción general</label>
                        <div class="col-md-8">
                            <textarea
                                name="excerpt" class="form-control"
                                required rows="3"
                                v-model="form_values.excerpt"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="keywords" class="col-md-4 col-form-label text-right">Palabras clave</label>
                        <div class="col-md-8">
                            <textarea
                                name="keywords" class="form-control"
                                required rows="3"
                                v-model="form_values.keywords"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-md-4 col-form-label text-right">Estado publicación</label>
                        <div class="col-md-8">
                            <select name="status" v-model="form_values.status" class="form-control" required>
                                <option v-for="(option_status, key_status) in options_status" v-bind:value="key_status">{{ option_status }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="content_json" class="col-md-4 col-form-label text-right">Contenido curso</label>
                        <div class="col-md-8">
                            <textarea
                                name="content_json" class="form-control"
                                required rows="10"
                                v-model="form_values.content_json"
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var edit_app = new Vue({
    el: '#edit_app',
    data: {
        form_values: {
            post_name: '<?= $row->post_name ?>',
            excerpt: '<?= $row->excerpt ?>',
            keywords: '<?= $row->keywords ?>',
            content_json: '<?= $row->content_json ?>',
            status: '0<?= $row->status ?>',
        },
        options_status: <?= json_encode($options_status) ?>
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