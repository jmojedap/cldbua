var nav_1_elements = [
    {
        text: 'Inicio',
        active: false,
        icon: 'bi-house',
        cf: 'courses/browse',
        sections: [],
        subelements: [],
        anchor: true
    },
    {
        text: 'Usuarios', active: false, icon: 'bi-person', cf: 'users/explore', anchor: false,
        sections: ['users/explore', 'users/add', 'users/import', 'users/profile', 'users/edit', 'users/assigned_contents'],
        subelements: []
    },
    {
        text: 'Posts',
        active: false,
        icon: 'bi-newspaper',
        cf: 'posts/explore',
        sections: ['posts/explore', 'posts/add', 'posts/import', 'posts/info', 'posts/edit', 'posts/image', 'posts/details'],
        subelements: []
    },
    {
        text: 'Archivos',
        active: false,
        icon: 'bi-image',
        cf: 'files/explore',
        sections: ['files/explore', 'files/add', 'files/import', 'files/info', 'files/edit', 'files/image', 'files/details'],
        subelements: []
    },
    {
        text: 'Comentarios',
        active: false,
        icon: 'bi-chat-quote',
        cf: 'comments/explore',
        sections: ['comments/explore', 'comments/add', 'comments/info'],
        subelements: []
    },
    {
        text: 'Cursos',
        active: false,
        style: '',
        icon: 'bi-book',
        cf: '',
        sections: ['courses/explore'],
        subelements: [
            {
                text: 'Cursos', active: false, icon: 'bi-book', cf: 'courses/explore',
                sections: ['courses/explore', 'courses/add', 'courses/edit', 'courses/info', 'courses/classes']
            },
            {
                text: 'Clases', active: false, icon: 'bi-play-btn', cf: 'posts/explore',
                sections: ['classes/explore', 'classes/add', 'classes/edit', 'classes/info']
            },
        ]
    },
    {
        text: 'Exámenes',
        active: false,
        style: '',
        icon: 'bi-question',
        cf: '',
        sections: ['exams/explore'],
        subelements: [
            {
                text: 'Exámenes', active: false, icon: 'bi-book', cf: 'exams/explore', anchor: false,
                sections: ['exams/explore', 'exams/add', 'exams/edit', 'exams/info', 'exams/questions']
            },
            {
                text: 'Preguntas', active: false, icon: 'bi-question', cf: 'questions/explore', anchor: false,
                sections: ['questions/explore', 'questions/add', 'question/edit', 'questions/info']
            },
        ]
    },
    {
        text: 'Ajustes',
        active: false,
        style: '',
        icon: 'bi-sliders',
        cf: '',
        sections: ['admin/options'],
        subelements: [
            {
                text: 'General', active: false, icon: 'bi-gear', cf: 'admin/options',
                sections: ['admin/options']
            },
            {
                text: 'Ítems', active: false, icon: 'bi-list', cf: 'items/manage',
                sections: ['items/manage', 'items/import']
            },
            {
                text: 'Base de datos', active: false, icon: 'bi-chevron-right', cf: 'sync/panel',
                sections: ['sync/panel']
            },
            {
                text: 'Eventos', active: false, icon: 'bi-clock', cf: 'events/summary', anchor: false,
                sections: ['events/explore', 'events/summary']
            },
            {
                text: 'Lugares', active: false, icon: 'bi-geo-alt', cf: 'places/explore', anchor: false,
                sections: ['places/explore', 'places/add', 'places/edit'],
            }
        ]
    }
];