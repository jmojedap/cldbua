<script>
var preview_app = new Vue({
    el: '#preview_app',
    created: function(){
        this.set_question(<?= $num_question ?>)
    },
    data: {
        exam: {
            title: '<?= $row->title ?>',
        },
        questions: <?= json_encode($questions->result()) ?>,
        num_question: 1,
        key_question: 0,
        curr_question: 0,
        answers: [0,0,0],
        curr_answer: 0,
        options_answer: [],
    },
    methods: {
        set_question: function(num_question){
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
            //console.log('respondiendo', option_value)
            //this.answers[this.key_question] = option_value
            //this.answers = [option_value,3,3,3]
            console.log('antes: ', this.answers[0])
            this.curr_answer = option_value
            this.answers[this.key_question] = option_value
            console.log('después: ', this.answers[0])
        },
    },
    computed: {
        nombre_metodo: function(){
            
        },
    }
})
</script>