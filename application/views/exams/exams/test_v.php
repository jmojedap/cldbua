<div id="test_app">
    <h1>{{ answers[answer_key] }}</h1>
    <p>
        {{ answers }}
    </p>
    <button class="btn btn-success" v-on:click="set_answer(444)">
        Cambiar
    </button>
</div>

<script>
var test_app = new Vue({
    el: '#test_app',
    created: function(){
        //this.get_list()
    },
    data: {
        answers: '0,0,0,0',
        answer_key: 2,
        questions: <?= json_encode($questions->result()) ?>,
    },
    methods: {
        set_question: function(num_question){
            
        },
        set_answer: function(answer){
            var arr_answers = this.answers.split(',');
            arr_answers[this.answer_key] = answer
            console.log(arr_answers)
            this.answers = arr_answers.join(',')
            
            /*this.answers[0] = 777;
            console.log('cambiando')
            console.log(this.answers[0])*/
        },
        
    }
})
</script>