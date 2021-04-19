<div id="enrolling_status_app">
    <div class="center_box_750">
        <div class="row mb-2">
            <div class="col-sm-2 text-center">
                <img
                v-bind:src="user.url_image"
                class="rounded rounded-circle border w120p mb-2"
                v-bind:alt="`Imagen de usuario` + user.display_name"
                onerror="this.src='<?= URL_IMG ?>users/user.png'"
                >
            </div>
            <div class="col-sm-10">
                <h4>{{ user.display_name }}</h4>
                <h4 class="text-success">¡Muchas felicidades!</h4>
                <p>
                    Has aprobado el curso <strong class="text-primary">{{ course.post_name }}</strong>
                </p>
                <p>
                    <button class="btn btn-success" v-on:click="download_certificate">
                        <i class="fa fa-download"></i> Descargar diploma
                    </button>
                </p>
            </div>
        </div>
        <div>
            <div class="diploma">
                <img src="<?= URL_RESOURCES ?>brands/pacarina/logo-front.png" alt="Escudo instutición" class="w120p">
                <h3 style="color: #FF0CA6;">Pacarina Media Lab</h3>
                <p>Certifica a</p>
                <h4 style="font-weight: bold;">{{ user.display_name }}</h4>
                <p>Por participar y aprobar el</p>
                <h2>{{ course.post_name }}</h2>
                <div class="row text-center">
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-4">
                        <img
                            v-bind:src="course.url_image"
                            class="rounded w150p"
                            alt="imagen del curso"
                            onerror="this.src='<?= URL_IMG ?>app/nd.png'"
                        >
                    </div>
                    <div class="col-md-4">
                        Firma
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
var enrolling_status_app = new Vue({
    el: '#enrolling_status_app',
    created: function(){
        //this.get_list()
    },
    data: {
        course: <?= json_encode($course) ?>,
        enrrolling: <?= json_encode($enrolling) ?>,
        user: {
            display_name: '<?= $user->display_name ?>',
            url_image: '<?= $user->url_image ?>',
        },
        loading: false,
    },
    methods: {
        download_certificate: function(){
            toastr['info']('Funcionalidad en construcción')
        },
    }
})
</script>