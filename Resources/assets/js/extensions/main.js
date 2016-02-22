
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
                quota: 209715200,
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
            if(this.selectedPool == null) {
                return;
            }

            var resource = this.$resource(resourceDocumentsPoolListFolder);
            resource.get({uid: this.selectedPool.uid}, {parent_uid: this.selectedParent}).then(function (response) {
                this.objects = response.data.data;
                this.meta = response.data.meta;

                this.$nextTick(function () {
                    $('.ui.dropdown')
                        .dropdown();
                })

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
            $('.ui.dropdown')
                .dropdown();

            $('#newPool')
                .modal('setting', 'transition', 'fade up')
                .modal('show');
        },

        permissionPoolModal() {
            $('.ui.dropdown')
                .dropdown();

            $('#permissionPool')
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

                this.newPool = {
                    title: null,
                        quota: 209715200,
                        readRoles: [],
                        writeRoles: []
                };

                return this.$route.router.go({
                    name: 'path',
                    params: { pool: response.uid, parent_uid: 'null' }
                });
            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.errors[0], data.message);
            }.bind(this));
        },

        updatePool: function(pool) {
            event.preventDefault();

            var resource = this.$resource(resourceDocumentsPoolUpdate);
            resource.update(pool,{uid:pool.uid}, function (data, status, request) {
                var response = data.data;

            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.errors[0], data.message);
            }.bind(this));
        },


        fileUploadStart: function () {
        },
        fileUploadComplete: function (id, name, responseJSON) {
            if(!responseJSON.success){
                return toastr.error(responseJSON.message);
            }
            if (responseJSON.data.uid) {
                this.objects.push(responseJSON.data);
                this.meta.objects.files++;
                this.selectedPool.objects.files++;
                this.selectedPool.quotaUsed += responseJSON.data.objectSize;
            }
        },
        fileUploadAllComplete: function (responseJSON) {
        },

        createFolder: function (object, event) {
            event.preventDefault();
            var newFolder = {
                mimeType: "application/x-directory",
                parent_uid: this.selectedParent,
                pool_uid: this.selectedPool.uid,
                title: "New Folder"
            };

            var resource = this.$resource(resourceDocumentsFolderStore);
            resource.save({pool: this.selectedPool.uid}, newFolder, function (data, status, request) {

                this.objects.push(data.data);
                this.meta.objects.folder++;
                this.selectedPool.objects.folder++;

            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.message);
            }.bind(this));
        }

    }
};
