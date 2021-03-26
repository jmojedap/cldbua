var nav_1_elements = [
    {
        text: 'Inicio',
        active: false,
        icon: 'fa fa-home',
        cf: 'info/home',
        sections: [],
        subelements: [],
        anchor: true
    },
    {
        text: 'Usuarios', active: false, icon: 'fa fa-user', cf: 'users/explore', anchor: false,
        sections: ['users/explore', 'users/add', 'users/import', 'users/profile', 'users/edit', 'users/assigned_contents'],
        subelements: []
    },
    {
        text: 'Posts',
        active: false,
        icon: 'far fa-file-alt',
        cf: 'posts/explore',
        sections: ['posts/explore', 'posts/add', 'posts/import', 'posts/info', 'posts/edit', 'posts/image', 'posts/details'],
        subelements: []
    },
    {
        text: 'Archivos',
        active: false,
        icon: 'far fa-image',
        cf: 'files/explore',
        sections: ['files/explore', 'files/add', 'files/import', 'files/info', 'files/edit', 'files/image', 'files/details'],
        subelements: []
    },
    {
        text: 'Comentarios',
        active: false,
        icon: 'far fa-comment',
        cf: 'comments/explore',
        sections: ['comments/explore', 'comments/add', 'comments/info'],
        subelements: []
    },
    {
        text: 'Cuestionarios',
        active: false,
        style: '',
        icon: 'fa fa-question',
        cf: '',
        sections: ['exams/explore'],
        subelements: [
            {
                text: 'Cuestionarios', active: false, icon: 'far fa-file-alt', cf: 'exams/explore',
                sections: ['exams/explore', 'exams/add', 'exams/edit', 'exams/info', 'exams/questions']
            },
            {
                text: 'Preguntas', active: false, icon: 'fa fa-question', cf: 'questions/explore',
                sections: ['questions/explore', 'questions/add', 'question/edit', 'questions/info']
            },
        ]
    },
    {
        text: 'Ajustes',
        active: false,
        style: '',
        icon: 'fa fa-sliders-h',
        cf: '',
        sections: ['admin/options'],
        subelements: [
            {
                text: 'General', active: false, icon: 'fa fa-cogs', cf: 'admin/options',
                sections: ['admin/options']
            },
            {
                text: 'Ítems', active: false, icon: 'fa fa-bars', cf: 'items/manage',
                sections: ['items/manage', 'items/import']
            },
            {
                text: 'Base de datos', active: false, icon: 'fa fa-database', cf: 'sync/panel',
                sections: ['sync/panel']
            },
            {
                text: 'Eventos', active: false, icon: 'far fa-clock', cf: 'events/summary', anchor: false,
                sections: ['events/explore', 'events/summary']
            },
            {
                text: 'Lugares', active: false, icon: 'fa fa-map-marker-alt', cf: 'places/explore', anchor: false,
                sections: ['places/explore', 'places/add', 'places/edit'],
            }
        ]
    }
];