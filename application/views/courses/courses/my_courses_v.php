<div id="user_courses">
    <div class="center_box_750">
        <div class="card" v-for="(element, ke) in list">
            <div class="card-body">
                <div class="media">
                    <img v-bind:src="element.url_thumbnail" class="w75p mr-3 rounded" alt="...">
                    <div class="media-body">
                        <a v-bind:href="`<?= base_url("courses/open_element/") ?>` + element.id + `/` + element.current_element_index">
                            <h5 class="mt-0">{{ element.post_name }}</h5>
                        </a>
                        <p>{{ element.excerpt }}</p>
                        <div class="progress my-3" style="height: 3px;">
                            <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + element.user_progress + `%;`" v-bind:aria-valuenow="element.user_progress" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p>
                            <a v-bind:href="`<?= base_url("courses/open_element/") ?>` + element.id + `/` + element.current_element_index"
                                class="btn btn-main w120p">
                                Continuar
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var user_courses = new Vue({
    el: '#user_courses',
    created: function(){
        //this.get_list()
    },
    data: {
        list: <?= json_encode($courses->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>