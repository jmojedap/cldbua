<div id="app_edit">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-right">Rol <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <select name="role" v-model="form_values.role" class="form-control" required>
                            <option v-for="(option_role, key_role) in options_role" v-bind:value="key_role">{{ option_role }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Nombre y Apellidos <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input
                            name="display_name" class="form-control"
                            placeholder="Nombres" title="Nombres del usuario"
                            required autofocus
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
                            v-bind:class="{ 'is-invalid': validation.email_unique == 0, 'is-valid': validation.email_unique == 1 }"
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
                                v-bind:class="{ 'is-invalid': validation.username_unique == 0, 'is-valid': validation.username_unique == 1 }"
                                placeholder="username"
                                title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                                required
                                pattern="^[A-Za-z0-9_]{6,25}$"
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
                    <label for="city_id" class="col-md-4 col-form-label text-right">País residencia</label>
                    <div class="col-md-8">
                        <select name="city_id" v-model="form_values.city_id" class="form-control form-control-chosen">
                            <option v-for="(option_city, key_city) in options_city" v-bind:value="key_city">{{ option_city }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_notes" class="col-md-4 col-form-label text-right">Notas privadas</label>
                    <div class="col-md-8">
                        <textarea
                            name="admin_notes" class="form-control"
                            title="Notas internas"
                            v-model="form_values.admin_notes"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">Guardar</button>
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
        username: '<?= $row->username ?>',
        email: '<?= $row->email ?>',
        role: '0<?= $row->role ?>',
        document_number: '<?= $row->document_number ?>',
        document_type: '0<?= $row->document_type ?>',
        city_id: '0<?= $row->city_id ?>',
        birth_date: '<?= $row->birth_date ?>',
        gender: '0<?= $row->gender ?>',
        phone_number: '<?= $row->phone_number ?>',
        notes: '<?= $row->notes ?>',
        admin_notes: '<?= $row->admin_notes ?>',
        about: '<?= $row->about ?>',
    };

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                document_number_unique: -1,
                username_unique: -1,
                email_unique: -1
            },
            options_role: <?= json_encode($options_role) ?>,
            options_city: <?= json_encode($options_city) ?>,
        },
        methods: {
            validate_form: function() {
                axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    //this.formulario_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        this.send_form();
                    } else {
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function() {
                axios.post(url_app + 'users/save/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.mensaje);
                        if (response.data.status == 1)
                        {
                        toastr['success']('Datos actualizados');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
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