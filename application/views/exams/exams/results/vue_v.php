<script>
var results_app = new Vue({
    el: '#results_app',
    created: function(){
        
    },
    data: {
        exam: {
            id: <?= $row->id ?>,
            title: '<?= $row->title ?>',
        },
        row_eu: <?= json_encode($row_eu) ?>,
        questions: <?= json_encode($questions->result()) ?>,
        correct_answers: [<?= $row->answers ?>],
        answers: [<?= $row_eu->answers ?>],
    },
    methods: {
        
    },
})
</script>