<div id="comments_section">
    <form accept-charset="utf-8" method="POST" id="comment_form" @submit.prevent="save_comment">
        <fieldset v-bind:disabled="loading">
            <input type="hidden" name="parent_id" v-model="form_values.parent_id">
            <div class="form-group">
                <textarea
                    name="comment_text" class="form-control" rows="2"
                    required
                    title="Escribe tu aporte" placeholder="Escribe un comentario..."
                    v-model="form_values.comment_text"
                ></textarea>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-primary w120p" type="submit">Comentar</button>
            </div>
        <fieldset>
    </form>

    <hr>

    <!-- Listado de comentarios -->
    <div class="media mb-1" v-for="(comment, kc) in comments" v-bind:id="`comment_` + comment.id">
        <img v-bind:src="comment.user_thumbnail" class="w40p rounded rounded-circle mr-3" alt="Imagen de usuario">
        <div class="media-body">
            <p>
                <strong>{{ comment.display_name }}</strong>
                <small class="ml-2 text-muted" v-bind:title="comment.created_at">{{ comment.created_at | ago }}</small>
            </p>
            <p>{{ comment.comment_text }}</p>
            <p>
                <button class="btn btn-sm btn-light" type="button" data-toggle="modal" data-target="#delete_comment_modal" v-on:click="set_current(kc)" v-if="app_uid == comment.creator_id">
                    Eliminar
                </button>
            </p>
        </div>
    </div>

    <button class="btn btn-light btn-block" v-on:click="get_more_comments" type="button" v-show="num_page < max_page">Más</button>

    <?php $this->load->view('comments/section/modal_delete_comment_v') ?>
</div>
<?php $this->load->view('comments/section/vue_v') ?>