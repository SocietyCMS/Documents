
import pooltree from '../components/pooltree.vue';
import breadcrumb from '../components/breadcrumb.vue';

export default {
    data() {
        return {
            pools: [],
            objects: [],
            meta: null,
            selectedPool:null,
            selectedParent: null
        }
    },
    components: {pooltree,breadcrumb},
    watch: {
        '$route.params': function () {
            this.selectPool()
            this.selectParent()
        }
    },
    ready() {
        this.requestPoolIndex();
    },
    methods: {
        requestPoolIndex() {
            var resource = this.$resource(resourceDocumentsPoolIndex);
            resource.get({}).then(function (response) {
                this.pools = response.data.data;
                this.selectPool();
                this.selectParent();
            }.bind(this), function (response) {
                toastr.error(response.data.message, 'Error: ' + response.data.status_code);
            }.bind(this));
        },

        requestObjectIndex() {
            var resource = this.$resource(resourceDocumentsPoolListFolder);
            resource.get({uid: this.selectedPool.uid}, {parent_uid: this.selectedParent}).then(function (response) {
                this.objects = response.data.data;
                this.meta = response.data.meta;
            }.bind(this), function (response) {
                toastr.error(response.data.message, 'Error: ' + response.data.status_code);
            }.bind(this));
        },

        redirectBack: function(){
            return this.$route.router.go(window.history.back());
        },

        redirectForward: function(){
            return this.$route.router.go(window.history.forward())
        },

        redirectUp: function(){
            return this.$route.router.go({
                name: 'path',
                params: { pool: this.selectedPool.uid, parent_uid: this.meta.parent_uid?this.meta.parent_uid:'null' }
            })
        },

        selectPool() {

            if(this.$route.params && this.$route.params.pool) {

                var uid = this.$route.params.pool;
                var result = $.grep(this.pools, function(e){ return e.uid == uid; });
                if (result.length == 0) {
                    this.selectedPool = null;
                } else if (result.length == 1) {
                    this.selectedPool = result[0];
                } else {
                    this.selectedPool = result[0];
                }

            } else {
                this.selectedPool = this.pools[0];
            }
        },

        selectParent() {
            if(this.$route.params && this.$route.params.parent_uid) {
                this.selectedParent = this.$route.params.parent_uid
            }

            this.requestObjectIndex();
        }
    }
};
