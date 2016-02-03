import list from '../components/list.vue';

export default {
    data() {
        return {
            sortKey: 'title',
            sortReverse: 1
        }
    },
    template: list.template,
    props: ['pool', 'objects'],
    methods: {
        'sortBy': function (sortKey) {
            this.sortReverse = (this.sortKey == sortKey) ? this.sortReverse * -1 : 1;
            this.sortKey = sortKey;
        },
        objectOpen: function (object, event) {
            event.preventDefault()

            if (object.tag == 'folder') {
                return this.$route.router.go({
                    name: 'path',
                    params: { pool: this.pool.uid, parent_uid: object.uid}
                })
            }

            return window.open(object.downloadUrl + '?token='+jwtoken,"_blank")
        },
    }
};
