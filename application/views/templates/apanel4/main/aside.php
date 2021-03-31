<script>
// Variables
//-----------------------------------------------------------------------------
    var gossip_pre = '';

//Activación inicial de elementos actuales
//-----------------------------------------------------------------------------
    nav_1_elements.forEach(element => {
        //Activar elemento actual, si está en las secciones
        if ( element.sections.includes(app_cf) ) { element.active = true; }
        //Activar subelemento actual, si está en las secciones
        if ( element.subelements )
        {
            element.subelements.forEach(subelement => {
                if ( subelement.sections.includes(app_cf) )
                {
                    gossip_pre = subelement.text;
                    element.active = true;
                    subelement.active = true;
                }
            });
        }
    });
</script>

<aside class="main_nav_col" id="nav_1">
    <?php $this->load->view('templates/apanel4/main/header') ?>
    <ul class="main_nav">
        <li v-for="(element, i) in elements" v-bind:class="{ 'treeview': element.subelements.length }">
            <a href="#" v-on:click="nav_1_click(i)" v-bind:class="{ 'active': element.active }">
                <i class="bi-2x" v-bind:class="element.icon"></i>
                <span>{{ element.text }}</span>
            </a>
            <span v-if="element.subelements.length > 0 && element.active" class="gossip">{{ gossip }}</span>
            <ul class="treeview-menu" v-if="element.subelements.length > 0">
                <li v-for="(subelement, j) in element.subelements">
                    <a href="#" v-bind:class="{ 'active': subelement.active }" v-on:click="nav_1_click_sub(i,j)">
                        <i v-bind:class="subelement.icon"></i> <span>{{ subelement.text }}</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<script>
var nav_1 = new Vue({
    el: '#nav_1',
    created: function(){
        //this.get_list()
    },
    data: {
        elements: nav_1_elements,
        gossip: gossip_pre,
    },
    methods: {
        nav_1_click: function(i){
            if ( this.elements[i].subelements.length == 0 )
            {
                if ( this.elements[i].anchor ) {
                    window.location = url_app + this.elements[i].cf;
                } else {
                    this.elements.forEach(element => { element.active = false; });
                    this.elements[i].active = true;
                    //console.log('activando: ', this.elements[i].cf)
                    //$('.treeview-menu').slideUp();
                    app_cf = this.elements[i].cf;
                    load_sections('nav_1');
                }
            }
        },
        nav_1_click_sub: function(i,j){
            if ( this.elements[i].subelements[j].anchor )
            {
                window.location = url_app + this.elements[i].subelements[j].cf;
            } else {
                //Activando elemento
                this.elements.forEach(element => { element.active = false; });
                this.elements[i].active = true;

                //Activando subelemento
                this.elements[i].subelements.forEach(subelement => { subelement.active = false; });
                this.elements[i].subelements[j].active = true;
                this.gossip = this.elements[i].subelements[j].text;

                //Cargando secciones
                app_cf = this.elements[i].subelements[j].cf;
                load_sections('nav_1');
            }
        }
    }
})
</script>
