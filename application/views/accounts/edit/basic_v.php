<?php
    $options_gender = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
    $options_city = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
    $options_document_type = $this->Item_model->options('category_id = 53', 'Tipo documento');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Nombre y Apellidos <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input
                            name="display_name" class="form-control"
                            placeholder="Nombres y apellidos" title="Nombres y apellidos"
                            required
                            v-model="form_values.display_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico <span class="text-danger">*</span></label>
                        
                    <div class="col-md-8">
                        <input
                            name="email" class="form-control"
                            placeholder="Correo electrónico" title="Correo electrónico"
                            v-bind:class="{ 'is-invalid': validation.email_unique == 0 }"
                            v-model="form_values.email"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El correo electrónico ya fue registrado, por favor escriba otro
                        </span>
                    </div>
                </div>

                <div class="form-group row" id="form-group_username">
                    <label for="username" class="col-md-4 col-form-label text-right">Username <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <!-- /btn-group -->
                            <input
                                id="field-username"
                                name="username"
                                class="form-control"
                                v-bind:class="{ 'is-invalid': validation.username_unique == 0 }"
                                placeholder="username"
                                title="Puede contener letras y números, entre 5 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                                required
                                pattern="^[A-Za-z0-9_]{5,25}$"
                                v-model="form_values.username"
                                v-on:change="validate_form"
                                >
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" title="Generar username" v-on:click="generate_username">
                                    <i class="fa fa-magic"></i>
                                </button>
                            </div>
                            
                            <span class="invalid-feedback">
                                El username escrito no está disponible, por favor elija otro
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="about" class="col-md-4 col-form-label text-right">Acerca de mí</label>
                    <div class="col-md-8">
                        <textarea
                            name="about" class="form-control"
                            title="Notas internas"
                            v-model="form_values.about"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

// Variables
//-----------------------------------------------------------------------------
var form_values = {
    display_name: '<?= $row->display_name ?>',
    email: '<?= $row->email ?>',
    username: '<?= $row->username ?>',
    about: '<?= $row->about ?>',
};

// App VueJS
//-----------------------------------------------------------------------------
var app_edit = new Vue({
el: '#app_edit',
    data: {
        form_values: form_values,
        validation: {
            username_unique: -1,
            email_unique: -1
        }
    },
    methods: {
        validate_form: function() {
            axios.post(url_api + 'accounts/validate_form/', $('#edit_form').serialize())
            .then(response => {
                //this.formulario_valido = response.data.status;
                this.validation = response.data.validation;
            })
            .catch(function (error) { console.log(error) })
        },
        validate_send: function () {
            axios.post(url_api + 'accounts/validate_form/', $('#edit_form').serialize())
            .then(response => {
                if (response.data.status == 1) {
                    this.send_form();
                } else {
                    toastr['error']('Revise las casillas en rojo');
                }
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function() {
            axios.post(url_api + 'accounts/update/', $('#edit_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data.message);
                    if (response.data.status == 1)
                    {
                    toastr['success']('Datos actualizados');
                    }
                })
                .catch(function (error) { console.log(error) }) 
        },
        generate_username: function() {
            var form_data = new FormData
            form_data.append('display_name', this.form_values.display_name)
            axios.post(url_app + 'users/username/', form_data)
            .then(response => {
                this.form_values.username = response.data
            })
            .catch(function (error) { console.log(error) })
        }
    }
});
</script>