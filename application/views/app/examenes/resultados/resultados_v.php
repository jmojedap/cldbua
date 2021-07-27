<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/admin_pml/css/skins/skin-blue-exams.css">

<div id="results_app" class="exam center_box_750">
    <div class="row">
        <div class="col-md-8">
            <p>{{ exam.title }}</p>
        </div>
        <div class="col-md-4 text-right">
            Resumen
        </div>
    </div>
    
    <h3>
        <span v-show="row_answer.approved == 0"><i class="fa fa-exclamation-triangle text-warning"></i> Examen No aprobado</span>
        <span v-show="row_answer.approved == 1"><i class="fa fa-check text-success"></i> Examen Aprobado</span>
    </h3>
    <div class="progress">
        <div class="progress-bar"
            role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
            v-bind:style="`width: ` + row_answer.pct_correct + `%;`"
            v-bind:class="{'bg-success': row_answer.approved == 1, 'bg-danger': row_answer.approved == 0 }"
            >
            {{ row_answer.pct_correct }}%
        </div>
    </div>
    

    <?php if ( $row_enrolling->status == 1 ) : ?>
        <div class="mt-2 card text-center">
            <div class="card-body">
                <h3 class="text-success">¡Muchas felicidades!</h3>
                <p style="font-size: 1.2em;">Has finalizado y aprobado: <strong><?= $course->post_name ?></strong></p>
                <p>
                    <a href="<?= URL_FRONT . "cursos/estado_inscripcion/{$course->id}/{$row_enrolling->user_id}/{$row_enrolling->id}" ?>" class="btn btn-success btn-sm">
                        VER CERTIFICADO
                    </a>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <hr>

    <h3>Resultados por pregunta</h3>

    <div v-for="(question, kq) in questions">
        <p class="mb-2">
            {{ parseInt(question.position) + 1 }}.
            {{ question.question_text }}
        </p>
        <div class="answer" v-bind:class="{'correct': answers[kq] == correct_answers[kq], 'incorrect': answers[kq] != correct_answers[kq]}">
            <div class="float-right">
                <i class="fa fa-check text-success" v-show="answers[kq] == correct_answers[kq]"></i>
                <i class="fa fa-times text-danger" v-show="answers[kq] != correct_answers[kq]"></i>
            </div>
            <div v-show="answers[kq] == 1">{{ question.option_1 }}</div>
            <div v-show="answers[kq] == 2">{{ question.option_2 }}</div>
            <div v-show="answers[kq] == 3">{{ question.option_3 }}</div>
            <div v-show="answers[kq] == 4">{{ question.option_4 }}</div>
            <div v-show="answers[kq] == 0"> <span class="text-muted">(NR)</span> </div>
        </div>
        <hr>
    </div>

    <div class="text-center" v-show="row_answer.approved == 0">
        <a href="<?= URL_FRONT . "examenes/preparacion/{$row->id}/{$course->id}" ?>" class="btn btn-secondary">
            INTENTARLO DE NUEVO
        </a>
    </div>
</div>
<?php $this->load->view($this->views_folder . 'resultados/vue_v') ?>