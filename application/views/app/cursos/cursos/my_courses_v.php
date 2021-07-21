<div id="user_courses">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <div class="row" v-for="(element, ke) in list">
                    <div class="col-md-9">
                        <div class="media">
                            <a v-bind:href="`<?= URL_ADMIN . "courses/open_element/" ?>` + element.id + `/` + element.current_element_index">
                                <img v-bind:src="element.url_thumbnail" class="w75p mr-3 rounded" alt="...">
                            </a>
                            <div class="media-body">
                                <h5 class="mt-0">{{ element.post_name }}</h5>
                                <div class="progress my-3" style="height: 3px;">
                                    <div class="progress-bar" role="progressbar"
                                        v-bind:style="`width: ` + element.user_progress + `%;`"
                                        v-bind:class="{'bg-success': element.user_progress == 100 }"
                                        v-bind:aria-valuenow="element.user_progress" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p>
                                    <span v-if="element.enrolling_status == 1">
                                        <strong class="text-success">
                                            <i class="fa fa-check"></i>
                                            {{ element.enrolling_status | enrolling_status_name }}
                                        </strong>
                                    </span>
                                    <span v-else>
                                        <strong class="text-info">
                                            {{ element.enrolling_status | enrolling_status_name }}
                                        </strong>
                                        <span class="text-muted" v-if="element.user_progress == 100">&middot; Examen final pendiente</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a class="btn btn-main btn-block"
                            v-bind:href="`<?= URL_ADMIN . "courses/open_element/" ?>` + element.id + `/` + element.current_element_index"
                            v-show="element.enrolling_status > 1"
                            >
                            Continuar
                        </a>
                        <a class="btn btn-success btn-block"
                            v-bind:href="`<?= URL_ADMIN . "courses/enrolling_status/" ?>` + element.id + `/` + user_id + `/` + element.enrolling_id"
                            v-show="element.enrolling_status == 1"
                            >
                            Certificado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var enrolling_status = <?= json_encode($arr_enrolling_status) ?>;

// Filtros
//-----------------------------------------------------------------------------
Vue.filter('enrolling_status_name', function (value) {
    if (!value) return ''
    value = enrolling_status[value]
    return value
})

// VueApp
//-----------------------------------------------------------------------------
var user_courses = new Vue({
    el: '#user_courses',
    created: function(){
        //this.get_list()
    },
    data: {
        user_id: <?= $this->session->userdata('user_id') ?>,
        list: <?= json_encode($courses->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>