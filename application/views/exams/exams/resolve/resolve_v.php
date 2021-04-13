<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/admin_pml/css/skins/skin-blue-exams.css">

<div id="resolve_app" class="exam center_box_750">
    <div class="row">
        <div class="col-md-8">
            <p>{{ exam.title }}</p>
        </div>
        <div class="col-md-4 text-right">
            Pregunta {{ num_question }} de {{ questions.length + 1 }}
        </div>
    </div>

    <!-- Sección respuesta de pregunta -->
    <div v-show="step == 'resolve'">
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
            {{ option_answer.text }}
        </div>


        <div class="d-flex justify-content-between mb-2">
            <button class="btn btn-secondary w120p" v-on:click="change_question(-1)" v-bind:disabled="num_question == 1">
                <i class="fa fa-chevron-left"></i>
            </button>
            <button class="btn btn-secondary w120p" v-on:click="change_question(1)" v-bind:disabled="num_question == questions.length">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>

        <div>
            <button class="btn btn-warning w120p" v-on:click="set_step('check')">
                Finalizar...
            </button>
        </div>

    </div>

    <!-- Sección resumen de respuestas -->
    <div v-show="step == 'check'">
        <div class="text-center mb-3">
            <button class="btn btn-secondary" v-on:click="set_step('resolve')">
                <i class="fa fa-arrow-left"></i> REGRESAR
            </button>
        </div>
        <h3>Estas son sus respuestas</h3>

        <div v-for="(question, kq) in questions">
            <p class="mb-2">
                {{ parseInt(question.position) + 1 }}.
                {{ question.question_text }}
            </p>
            <div class="answer" v-bind:class="{'active': answers[kq] > 0 }">
                <div class="float-right">
                    <small class="pointer text-primary" v-on:click="set_question(parseInt(question.position) + 1)">Cambiar</small>
                </div>
                <div v-show="answers[kq] == 0"><small class="text-danger">[ SIN RESPONDER ]</small></div>
                <div v-show="answers[kq] == 1">{{ question.option_1 }}</div>
                <div v-show="answers[kq] == 2">{{ question.option_2 }}</div>
                <div v-show="answers[kq] == 3">{{ question.option_3 }}</div>
                <div v-show="answers[kq] == 4">{{ question.option_4 }}</div>
            </div>
            <hr>
        </div>

        <div class="text-center">
            <button class="btn btn-primary btn-lg" v-on:click="finalize">
                CALIFICAR RESPUESTAS
            </button>
        </div>
    </div>

    <div>
        
    </div>
</div>
<?php $this->load->view('exams/exams/resolve/vue_v') ?>