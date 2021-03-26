<div id="avatar_app">
    <div class="center_box_750 text-center mb-2">
        <h3>Elige tu avatar</h3>
        <img
            v-for="avatar in avatars"
            v-bind:src="`<?= URL_IMG ?>avatars/` + avatar"
            class="rounded border w50p pointer mr-1 mb-1 bg-white"
            v-bind:class="{'border-primary': url_avatar == '<?= URL_IMG ?>avatars/' + avatar }"
            alt="Avatar opción"
            v-on:click="set_avatar(avatar)"
            onerror="this.src='<?= URL_IMG ?>users/user.png'"
        >
    </div>
    <div class="center_box_450">
        <img
            v-bind:src="url_avatar"
            class="card-img-top bg-white border"
            alt="Imagen avatar usuario"
            onerror="this.src='<?= URL_IMG ?>users/user.png'"
        >
    </div>
</div>

<script>
var avatar_app = new Vue({
    el: '#avatar_app',
    data: {
        url_avatar: '<?= $row->url_image ?>',
        avatars: <?= json_encode($avatars) ?>,
    },
    methods: {
        set_avatar: function(file_name){
            var form_data = new FormData
            form_data.append('file_name', file_name)

            axios.post(url_api + 'app/set_avatar/', form_data)
            .then(response => {
                this.url_avatar = response.data.url_avatar
            }).catch(function(error) {console.log(error)})  
        },
    }
})
</script>