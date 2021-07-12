<script>
var questions_app = new Vue({
    el: '#questions_app',
    created: function(){
        this.get_list()
    },
    data: {
        exam_id: <?= $row->id ?>,
        list: []
    },
    methods: {
        get_list: function(){
            axios.get(url_api + 'exams/get_questions/' + this.exam_id)
            .then(response => {
                this.list = response.data.list
            }).catch(function(error) {console.log(error)})  
        },
    }
})
</script>