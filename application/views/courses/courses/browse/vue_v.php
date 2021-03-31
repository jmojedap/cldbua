
<script>
// App
//-----------------------------------------------------------------------------

var app_explore = new Vue({
    el: '#app_explore',
    created: function(){
        this.calculate_active_filters()
    },
    data: {
        cf: '<?= $cf ?>',
        controller: '<?= $controller ?>',
        search_num_rows: <?= $search_num_rows ?>,
        num_page: <?= $num_page ?>,
        max_page: <?= $max_page ?>,
        list: <?= json_encode($list) ?>,
        element: [],
        filters: <?= json_encode($filters) ?>,
        display_filters: false,
        loading: false,
        active_filters: false,
        options_type: <?= json_encode($options_type) ?>,
    },
    methods: {
        get_list: function(e, num_page = 1){
            this.loading = true
            axios.post(url_app + this.controller + '/get/' + num_page, $('#search_form').serialize())
            .then(response => {
                this.num_page = num_page
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                $('#head_subtitle').html(response.data.search_num_rows)
                history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters)
                this.all_selected = false
                this.selected = []
                this.loading = false

                this.calculate_active_filters()
            })
            .catch(function (error) { console.log(error) })
        },
        sum_page: function(sum){
            var new_num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page)
            this.get_list(null, new_num_page)
        },
        set_current: function(key){
            this.element = this.list[key]
        },
        toggle_filters: function(){
            this.display_filters = !this.display_filters
            $('#adv_filters').toggle('fast')
        },
        remove_filters: function(){
            this.filters.q = null
            this.filters.type = null

            this.display_filters = false

            setTimeout(() => { this.get_list() }, 100)
        },
        calculate_active_filters: function(){
            var calculated_active_filters = false
            if ( this.filters.q ) calculated_active_filters = true
            if ( this.filters.type ) calculated_active_filters = true

            this.active_filters = calculated_active_filters
        },
    }
})
</script>