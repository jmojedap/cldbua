<div class="row">
    <div class="col-md-3">
        <div v-for="(element, key) in list" class="card mb-2">
            <div class="card-body">
                <div>
                    <a v-bind:href="`<?= base_url("courses/info/") ?>` + element.id">
                        <h3 class="card-title">
                            {{ element.post_name }}
                        </h3>
                    </a>
                </div>
                <div>
                    <p>{{ element.excerpt }}</p>
                </div>
                
                <div>
                    <a class="btn btn-light btn-lg w120p" v-bind:href="`<?= base_url("courses/info/") ?>` + element.id + `/` + element.slug">
                        Ver más
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
