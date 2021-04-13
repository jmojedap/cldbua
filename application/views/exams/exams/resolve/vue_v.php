<script>
var preview_app = new Vue({
    el: '#resolve_app',
    created: function(){
        this.set_question(<?= $num_question ?>)
    },
    data: {
        enrolling_id: <?= $enrolling_id ?>,
        exam: {
            id: <?= $row->id ?>,
            title: '<?= $row->title ?>',
        },
        eu_id: <?= $eu_id ?>,
        questions: <?= json_encode($questions->result()) ?>,
        num_question: 1,
        key_question: 0,
        curr_question: 0,
        answers: [<?= ($row_eu->answers) ?>],
        curr_answer: 0,
        options_answer: [],
        step: 'check',
    },
    methods: {
        set_question: function(num_question){
            this.step = 'resolve'
            this.num_question = num_question
            this.key_question = num_question - 1
            this.curr_question = this.questions[this.key_question]
            this.curr_answer = this.answers[this.key_question]
            this.set_options()
        },
        change_question: function(sum){
            var num_question = Pcrn.cycle_between(this.num_question + sum, 1, this.questions.length)
            this.set_question(num_question)
        },
        set_options: function(){
            this.options_answers = []
            this.options_answer = [
                {'value': 1, 'text': this.curr_question.option_1},
                {'value': 2, 'text': this.curr_question.option_2},
                {'value': 3, 'text': this.curr_question.option_3},
                {'value': 4, 'text': this.curr_question.option_4},
            ]
        },
        set_answer: function(option_value){            
            this.curr_answer = option_value
            this.answers[this.key_question] = option_value
            this.save_answers()
        },
        save_answers: function(){
            var form_data = new FormData
            form_data.append('exam_id', this.exam.id)
            form_data.append('eu_id', this.eu_id)
            form_data.append('answers', this.answers)

            axios.post(url_api + 'exams/save_answers/', form_data)
            .then(response => {
                console.log(response.data)
            }).catch(function(error) {console.log(error)})  
        },
        set_step: function(step){
            this.step = step  
        },
        finalize: function(){
            var form_data = new FormData
            form_data.append('exam_id', this.exam.id)
            form_data.append('eu_id', this.eu_id)
            form_data.append('enrolling_id', this.enrolling_id)
            form_data.append('answers', this.answers)

            axios.post(url_api + 'exams/finalize/', form_data)
            .then(response => {
                console.log(response.data)
                if ( response.data.status == 1 ) {
                    toastr['success'](response.data.message)
                } else {
                    toastr['error'](response.data.message)
                }

                setTimeout(() => {
                    window.location = url_app + 'exams/results/' + this.exam.id + '/' + this.eu_id + '/' + this.enrolling_id
                }, 3000);

            }).catch(function(error) {console.log(error)})  
        },
    },
})
</script>