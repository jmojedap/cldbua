<?php $this->load->view('assets/bs4_chosen'); ?>

<div id="add_user">
    <div class="card center_box_750">
        <div class="card-header">
            Crear estudiante
        </div>
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <input type="hidden" name="role" value="21">

                <div class="form-group row">
                    <label for="first_name" class="col-md-4 col-form-label text-right">Nombres y Apellidos *</label>
                    <div class="col-md-4">
                        <input
                            name="first_name" class="form-control"
                            placeholder="Nombres" title="Nombres del usuario"
                            required autofocus
                            v-model="form_values.first_name"
                            >
                    </div>
                    <div class="col-md-4">
                        <input
                            name="last_name" class="form-control"
                            placeholder="Apellidos" title="Apellidos del usuario"
                            required
                            v-model="form_values.last_name"
                            >
                    </div>
                </div>
                
                <div class="form-group row" id="form-group_email">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico *</label>
                    <div class="col-md-8">
                        <input
                            name="email" type="email" class="form-control" title="Dirección de correo electrónico"
                            required
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
                    <label for="username" class="col-md-4 col-form-label text-right">Username *</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            
                            <input
                                name="username" class="form-control"
                                title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                                required pattern="^[A-Za-z0-9_]{6,25}$"
                                v-bind:class="{ 'is-invalid': validation.username_unique == 0, 'is-valid': validation.username_unique == 1 }"
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
                    <label for="password" class="col-md-4 col-form-label text-right">Contraseña *</label>
                    <div class="col-md-8">
                        <input
                            name="password" class="form-control"
                            title="Debe tener al menos un número y una letra minúscula, y al menos 8 caractéres"
                            required pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            v-model="form_values.password"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">Crear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view($this->views_folder . 'add/modal_created_v') ?>
</div>

<?php
$this->load->view($this->views_folder . 'add/student/vue_v');