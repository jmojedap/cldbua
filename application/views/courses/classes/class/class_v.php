<div id="clase_app">
    <div class="row">
        <div class="col-md-8">
            <iframe
                width="100%"
                style="min-height: 500px;"
                v-bind:src="`https://www.youtube.com/embed/` + clase.youtube_id + `?rel=0&showinfo=0&controls=1`"
                v-bind:title="clase.title"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                
                allowfullscreen>
            </iframe>
            <h3 class="text-muted">{{ course.title }}</h3>
            <h2>{{ clase.title }} <small class="text-muted">Módulo {{ clase.module }} / Clase {{ clase.position }}</small></h2>
            <div v-html="clase.content"></div>
        </div>
        <div class="col-md-4">
            Aquí van los comentarios
        </div>
    </div>
</div>

<script>
var clase_app = new Vue({
    el: '#clase_app',
    created: function(){
        //this.get_list()
    },
    data: {
        clase: {
            title: '<?= $clase->post_name ?>',
            content: '<?= $clase->content ?>',
            youtube_id: '<?= $clase->text_1 ?>',
            module: '<?= $clase->integer_1 ?>',
            position: '<?= $clase->position ?>',
        },
        course: {
            title: '<?= $course->post_name ?>',
        }
    },
    methods: {
        
    }
})
</script>