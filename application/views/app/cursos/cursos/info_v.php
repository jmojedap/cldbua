<div id="couser_app">
    <div class="center_box_920">
        <div class="mb-2">
            <a href="<?= URL_ADMIN . "courses/browse" ?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Catálogo</a>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ course.post_name }}</h1> 
                        <p>{{ course.excerpt }}</p>
                        <button class="btn btn-main btn-lg" v-on:click="enroll" v-bind:disabled="loading">
                            <span v-show="loading"><i class="fa fa-spin fa-spinner"></i></span>
                            Inscribirme
                        </button>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <img
                    v-bind:src="course.url_image"
                    class="card-img-top"
                    alt="Imagen portada del curso"
                    onerror="this.src='<?= URL_IMG ?>app/nd.png'"
                >
            </div>
        </div>
    </div>
</div>

<script>
var couser_app = new Vue({
    el: '#couser_app',
    created: function(){
        //this.get_list()
    },
    data: {
        course: <?= json_encode($row) ?>,
        user_id: app_uid,
        loading: false,
    },
    methods: {
        enroll: function(){
            this.loading = true
            var form_data = new FormData()
            form_data.append('user_id', this.user_id)
            form_data.append('course_id', this.course.id)
            axios.post(url_api + 'courses/enroll/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Inscripción creada!')
                    setTimeout(() => {
                        window.location = url_app + 'cursos/abrir_elemento/' + this.course.id + '/'
                    }, 2000);
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>