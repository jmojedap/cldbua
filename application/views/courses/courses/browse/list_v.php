<div class="courses_list center_box_920">
    <div class="card course" v-for="(element, key) in list">
        <a v-bind:href="`<?= base_url("courses/info/") ?>` + element.id">
            <img
                v-bind:src="element.url_image"
                class="card-img-top"
                alt="Imagen portada del curso"
                onerror="this.src='<?= URL_IMG ?>app/nd.png'"
            >
        </a>
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
        </div>
    </div>
</div>
