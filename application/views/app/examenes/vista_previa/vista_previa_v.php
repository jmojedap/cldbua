<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/admin_pml/css/skins/skin-blue-exams.css">

<div id="preview_app" class="exam center_box_750">
    <div class="row">
        <div class="col-md-8">
            <p>{{ exam.title }}</p>
        </div>
        <div class="col-md-4 text-right">
            Pregunta {{ num_question }} de {{ questions.length + 1 }}
        </div>
    </div>
    
    <div class="questions-explorer">
        <span class="item"
            v-for="(item, item_key) in questions"
            v-bind:class="{'answered': answers[item_key] > 0, 'active': key_question == item_key }"
            v-on:click="set_question(parseInt(item.position) + 1)"
            >
        </span>
    </div>

    <p class="question_text">
        {{ curr_question.question_text }}
    </p>

    <table class="table d-none">
        <tbody>
            <tr><td>curr_answer</td><td>{{ curr_answer }}</td></tr>
            <tr><td>answers</td><td>{{ answers }}</td></tr>
        </tbody>
    </table>

    <div class="answer_option"
            v-for="(option_answer, option_key) in options_answer"
            v-bind:class="{'active': option_answer.value == curr_answer }"
            v-on:click="set_answer(option_answer.value)"
            v-show="option_answer.text"
        >
        <p>{{ option_answer.text }}</p>
    </div>


    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary btn-lg w120p" v-on:click="change_question(-1)"><i class="fa fa-chevron-left"></i></button>
        <button class="btn btn-secondary btn-lg w120p" v-on:click="change_question(1)"><i class="fa fa-chevron-right"></i></button>
    </div>

</div>
<?php $this->load->view('admin/exams/exams/preview/vue_v') ?>