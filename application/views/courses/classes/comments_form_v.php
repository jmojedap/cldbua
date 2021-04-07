<div id="comments_app">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="comment_form" @submit.prevent="save_comment">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="parent_id" v-model="form_values.parent_id">
                    <input
                        name="comment_text" type="text" class="form-control"
                        required
                        title="Escribe tu aporte" placeholder="Escribe tu aporte"
                        v-model="form_values.comment_text"
                    >
                    
                    <div class="form-group">
                        <button class="btn btn-primary w120p" type="submit">Enviar</button>
                    </div>
                <fieldset>
            </form>

            <hr>

            <div class="media" v-for="comment in comments" :key="comment.id">
            <img v-bind:src="comment.user_thumbnail" class="w30p rounded rounded-circle mr-3" alt="Imagen de usuario">
            <div class="media-body">
                {{ comment.comment_text }}
            </div>
            </div>
            
        </div>
    </div>
    
</div>

<script>
var comments_app = new Vue({
    el: '#comments_app',
    created: function(){
        this.get_comments()
    },
    data: {
        post_id: <?= $clase->id ?>,
        loading: false,
        comments: [],
        form_values: {
            comment_text: 'Texto random de comentario <?= date('YmdHis') ?>',
            parent_id: 0
        }
    },
    methods: {
        get_comments: function(){
            axios.get(url_api + 'comments/element_comments/2000/' + this.post_id)
            .then(response => {
                this.comments = response.data.comments
            }).catch(function(error) {console.log(error)})
        },
        save_comment: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('comment_form'))
            axios.post(url_api + 'comments/save/2000/' + this.post_id, form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>

