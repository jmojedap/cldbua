<div id="course_classes">
    <table class="table bg-white">
        <thead>
            <th width="30px">ID</th>
            <th>Clase</th>
            <th>Tipo</th>
            <th>Módulo</th>
            <th>Orden</th>
            <th>Peso</th>
            <th width="90px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list">
                <td>{{ element.id }}</td>
                <td>
                    <a v-bind:href="`<?= base_url("posts/info/") ?>` + element.id" target="_blank">
                        {{ element.post_name }}
                    </a>
                </td>
                <td>{{ element.type_id | type_name }}</td>
                <td>{{ element.related_1 }}</td>
                <td>{{ element.position }}</td>
                <td>
                    <span v-show="element.type_id == 4140">{{ element.integer_1 }}%</span>
                </td>
                <td>
                    <a v-bind:href="`<?= base_url("courses/open_element/{$row->id}/") ?>` + key" class="a4" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <a v-bind:href="`<?= base_url("posts/edit/") ?>` + element.id" class="a4" target="_blank">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var type_names = <?= json_encode($arr_types) ?>;

// Filros
//-----------------------------------------------------------------------------
Vue.filter('type_name', function (value) {
    if (!value) return ''
    value = type_names[value]
    return value
})

// VueJS App
//-----------------------------------------------------------------------------
var course_classes = new Vue({
    el: '#course_classes',
    created: function(){
        //this.get_list()
    },
    data: {
        list: <?= json_encode($classes->result()) ?>,
        //form_values: row,
        loading: false,
    },
    methods: {
        
    }
})
</script>