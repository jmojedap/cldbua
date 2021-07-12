<div id="login_app" class="text-center center_box mw360p">
    <p>
        Escribe tu correo electrónico
        <br/>
        y tu contraseña para ingresar.
    </p>

    <form accept-charset="utf-8" method="POST" id="login_form" @submit.prevent="validate_login">
        <div class="form-group">
            <input
                class="form-control form-control-lg" name="username"
                placeholder="Correo electrónico" required
                title="Correo electrónico">
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Contraseña" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-main btn-lg btn-block" v-bind:disabled="loading"><i v-show="loading" class="fa fa-spin fa-spinner"></i> Ingresar</button>
        </div>
        
        <div class="form-group">
            <a href="<?= URL_APP . 'accounts/recovery' ?>">¿Olvidaste los datos de tu cuenta?</a>
        </div>
        
        <!-- <div class="form-group">
            <a href="<?php //echo $g_client->createAuthUrl(); ?>" class="btn btn_google btn-block">
                <img src="<?php //echo URL_IMG . 'app/google.png'?>" style="width: 20px">
                Ingresar con Google
            </a>
        </div> -->
    </form>

    <br/>
    
    <div id="messages" v-if="!status">
        <div class="alert alert-warning" v-for="message in messages">
            {{ message }}
        </div>
    </div>

    <p>¿No tienes una cuenta? <a href="<?= URL_APP . 'accounts/signup' ?>">Regístrate</a></p>
</div>

<script>
var login_app = new Vue({
    el: '#login_app',
    data: {
        messages: [],
        status: 1,
        loading: false
    },
    methods: {
        validate_login: function(){                
            this.loading = true
            axios.post(url_front + 'accounts/validate_login', $('#login_form').serialize())
            .then(response => {
                if ( response.data.status == 1 )
                {
                    window.location = url_front + 'accounts/logged';
                } else {
                    this.messages = response.data.messages;
                    this.status = response.data.status;
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        }
    }
});
</script>
