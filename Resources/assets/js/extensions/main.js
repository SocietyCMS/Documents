
import pooltree from '../components/pooltree.vue';
import breadcrumb from '../components/breadcrumb.vue';

export default {
    data() {
        return {
            pools: [],
            objects: [],
            meta: null,
            selectedPool:null,
            selectedParent: null,

            newPool: {
                title: null,
                quota: 200,
                readRoles: [],
                writeRoles: []
            }
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

            if(!this.$route.params || !this.$route.params.pool) {
                return this.$route.router.go({
                    name: 'path',
                    params: { pool: this.pools[0].uid, parent_uid: 'null' }
                });
            }

            var uid = this.$route.params.pool;
            var result = $.grep(this.pools, function(e){ return e.uid == uid; });

            if (result.length == 0) {
                this.selectedPool = null;
            } else if (result.length == 1) {
                this.selectedPool = result[0];
            } else {
                this.selectedPool = result[0];
            }

            this.selectParent();
        },

        selectParent() {
            if(this.$route.params && this.$route.params.parent_uid) {
                this.selectedParent = this.$route.params.parent_uid
            }

            this.requestObjectIndex();
        },


        createPoolModal() {
            $('#newPool')
                .modal('setting', 'transition', 'fade up')
                .modal('show');
        },

        createPool: function() {
            event.preventDefault();

            var resource = this.$resource(resourceDocumentsPoolStore);
            resource.save(this.newPool, function (data, status, request) {
                var response = data.data;
                this.pools.push(response);

                $('#newPool').modal('hide');

                return this.$route.router.go({
                    name: 'path',
                    params: { pool: response.uid, parent_uid: 'null' }
                });
            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.errors[0], data.message);
                this.editMode = null;
                this.editObject = null;
            }.bind(this));
        },


        fileUploadStart: function () {
            this.editMode = 'uploadFiles';
        },
        fileUploadComplete: function (id, name, responseJSON) {
            if (responseJSON.data.uid) {
                this.objects.push(responseJSON.data);
                this.meta.objects.files++;
                this.selectedPool.objects.files++;
                this.selectedPool.quotaUsed += responseJSON.data.objectSize;
            }
        },
        fileUploadAllComplete: function (responseJSON) {
            this.editMode = null;
        },
    }
};
