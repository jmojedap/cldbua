<div id="questions_app">
    <div class="row">
        <div class="col-md-8">
            <table class="table bg-white">
                <thead>
                    <th>No.</th>
                    <th>Pregunta</th>
                    <th>Position</th>
                    <th>ID</th>
                </thead>
                <tbody>
                    <tr v-for="(element, key) in list">
                        <td>{{ parseInt(element.position) + 1 }}</td>
                        <td>{{ element.question_text }}</td>
                        <td>{{ element.position }}</td>
                        <td>{{ element.id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            Aquí formulario
        </div>
    </div>
</div>

<?php $this->load->view('exams/exams/questions/vue_v') ?>