<style>
    .diploma {
        border: 1px solid #CCC;
        background-color: white;
        text-align: center;
        height: 580px;
        padding: 50px 10px 10px 10px;
    }
</style>

<div id="enrolling_status_app">
    <div class="center_box_750">
        <div class="row">
            <div class="col-md-4">
                <img
                    v-bind:src="user.url_image"
                    class="rounded rounded-circle border w100pc"
                    v-bind:alt="`Imagen de usuario` + user.display_name"
                    onerror="this.src='<?= URL_IMG ?>users/user.png'"
                >
            </div>
            <div class="col-md-8">
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
        <hr>
        <div>
            <div class="diploma">
                <h3>Universidad de los Andes</h3>
                <p>Certifica a</p>
                <h4>{{ user.display_name }}</h4>
                <p>Por participar y aprobar el</p>
                <h4>Curso</h4>
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