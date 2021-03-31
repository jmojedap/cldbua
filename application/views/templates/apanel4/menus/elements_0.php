<script>
var nav_1_elements = [
    {
        text: 'Usuarios',
        active: false,
        icon: 'bi bi-person',
        cf: 'users/explore',
        sections: ['users/explore', 'users/add', 'users/import', 'users/profile', 'users/edit', 'users/assigned_contents'],
        subelements: [],
        anchor: true
    },
    {
        text: 'Posts',
        active: false,
        icon: 'bi-newspaper',
        cf: 'posts/explore',
        sections: ['posts/explore', 'posts/add', 'posts/import', 'posts/info', 'posts/edit', 'posts/image', 'posts/details'],
        subelements: [],
        anchor: false
    },
    {
        text: 'Archivos',
        active: false,
        icon: 'bi-file',
        cf: 'files/explore',
        sections: ['files/explore', 'files/add', 'files/import', 'files/info', 'files/edit', 'files/image', 'files/details'],
        subelements: [],
        anchor: true
    },
    {
        text: 'Ajustes',
        active: false,
        style: '',
        icon: 'bi-gear',
        cf: '',
        sections: ['admin/options', 'items/manage'],
        subelements: [
            {
                text: 'General',
                active: false,
                icon: 'bi-gear',
                cf: 'site/welcome',
                sections: ['admin/options', 'site/welcome'],
                anchor: true
            },
            {
                text: 'Ítems',
                active: false,
                icon: 'bi-list',
                cf: 'items/manage',
                sections: ['items/manage', 'items/import'],
                anchor: true
            },
            {
                text: 'Base de datos',
                active: false,
                icon: 'bi-stack',
                cf: 'sync/panel',
                sections: ['sync/panel'],
                anchor: false
            },
            {
                text: 'Eventos',
                active: false,
                icon: 'bi-calendar',
                cf: 'events/summary',
                sections: ['events/explore', 'events/summary'],
                anchor: false
            }
        ]
    }
];
</script>