<div id="clase_app">
    <div class="center_box_920">
        <div class="youtube-cointainer">
            <iframe width="100%"
                v-bind:src="`https://www.youtube.com/embed/` + clase.youtube_id + `?rel=0&showinfo=0&controls=1`"
                v-bind:title="clase.title" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <button class="btn btn-secondary w120p" v-on:click="go_to(index - 1)"><i
                    class="fa fa-chevron-left"></i></button>
            <button class="btn btn-secondary w120p" v-on:click="go_to(index + 1)" href=""><i
                    class="fa fa-chevron-right"></i></button>
        </div>
        <h2>{{ clase.title }}</h2>
        <p>Módulo {{ clase.module }} &middot; Clase {{ clase.position }}</p>

        <ul class="nav nav-tabs mb-2">
            <li class="nav-item">
                <a class="nav-link pointer" v-bind:class="{'active': curr_section == 'content' }" v-on:click="set_section('content')">Contenido</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-bind:class="{'active': curr_section == 'comments' }" v-on:click="set_section('comments')">Comentarios</a>
            </li>
        </ul>

        <div class="card" v-show="curr_section == 'content'">
            <div class="card-body">
                <div v-html="clase.content"></div>
            </div>
        </div>
        <div v-show="curr_section == 'comments'">
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
            aquí van los comentarios
            <br>
        </div>
    </div>
</div>

<script>
var clase_app = new Vue({
    el: '#clase_app',
    created: function() {
        //this.get_list()
    },
    data: {
        enrolling_id: <?= $enrolling_id ?>,
        index: <?= $index ?>,
        clase: {
            title: '<?= $clase->post_name ?>',
            content: '<?= $clase->content ?>',
            youtube_id: '<?= $clase->text_1 ?>',
            module: '<?= $clase->integer_1 ?>',
            position: '<?= $clase->position ?>',
        },
        course: {
            id: <?= $course->id ?>,
            title: '<?= $course->post_name ?>',
        },
        curr_section: 'content'
    },
    methods: {
        go_to: function(new_index) {
            var new_location = url_app + 'courses/open_element/' + this.course.id + '/' + new_index
            window.location = new_location
            //console.log(new_location)
        },
        set_section: function(new_section){
            this.curr_section = new_section
        },
    }
})
</script>