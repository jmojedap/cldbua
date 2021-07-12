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
        row_answer: <?= json_encode($row_answer) ?>,
        course: <?= json_encode($course) ?>,
        enrolling: <?= json_encode($row_enrolling) ?>,
        questions: <?= json_encode($questions->result()) ?>,
        correct_answers: [<?= $row->answers ?>],
        answers: [<?= $row_answer->answers ?>],
    },
    methods: {
        
    },
})
</script>