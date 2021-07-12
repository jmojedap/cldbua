<div id="gallery_class_app">
    <div class="card mb-2">
        <img
            v-bind:src="currentImage.url"
            class="w100pc card-img-top"
            v-bind:alt="currentImage.title"
            onerror="this.src='<?= URL_IMG ?>app/nd.png'"
        >
        <div class="card-body">
            <hr>
            <img
                v-for="(image, imageKey) in images"
                v-bind:src="image.url_thumbnail"
                v-on:click="setCurrent(imageKey)"
                v-bind:class="{'border-primary': image.id == currentImage.id }"
                class="rounded border mr-1 w40p pointer"
                v-bind:alt="image.title"
                onerror="this.src='<?= URL_IMG ?>app/sm_nd.png'"
            >
        </div>
    </div>
</div>

<script>
var gallery_class_app = new Vue({
    el: '#gallery_class_app',
    created: function(){
        this.getList()
    },
    data: {
        clase: <?= json_encode($row) ?>,
        loading: false,
        images: [],
        currentImage: {}
    },
    methods: {
        getList: function(){
            axios.get(url_api + 'posts/get_images/' + this.clase.id)
            .then(response => {
                this.images = response.data.images
                if ( this.images.length > 0 ) this.currentImage = this.images[0]
            })
            .catch(function(error) { console.log(error) })
        },
        setCurrent: function(imageKey){
            this.currentImage = this.images[imageKey]
        },
    }
})
</script>