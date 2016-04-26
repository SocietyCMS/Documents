import list from '../components/list.vue';

export default {
    data() {
        return {
            sortKey: 'title',
            sortReverse: 1,

            editObject:null
            
        }
    },
    template: list.template,
    props: ['pool', 'objects'],
    watch: {
        'objects': function () {
            if(this.objects[this.objects.length-1] && this.objects[this.objects.length-1].editing == true) {
                this.objects[this.objects.length-1].editing = false;
                this.objectEdit(this.objects[this.objects.length-1]);
            }

            this.$nextTick(function () {
                $('.ui.dropdown')
                    .dropdown();
            })
        }
    },
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

            return window.open(object.downloadUrl + '?token='+societycms.jwtoken,"_blank")
        },

        objectEdit: function (object, event) {
            this.editObject = object;
            this.editMode = 'rename';

            setTimeout(function () {
                    $('#objectEditInput-'+object.uid).focus();
                    $('#objectEditInput-'+object.uid).select();
                }
                , 50);
        },
        objectBlurEdit: function (object, event) {
            event.preventDefault();

            if (this.editObject == null || this.editMode != 'rename') {
                return;
            }

            var resource = this.$resource(resourceDocumentsFileUpdate);
            resource.update({pool: this.pool.uid}, this.editObject, function (data, status, request) {
                this.editMode = null;
                this.editObject = null;
            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.errors[0], 'Error: ' + data.message);
            });

        },
        objectKeydownEdit: function (object, event) {
            if (event.which == 13) {
                this.objectBlurEdit(object, event);
            }
        },

        objectDelete: function (object, event) {
            event.preventDefault();

            var resource = this.$resource(resourceDocumentsFileDestroy);
            resource.delete({pool: this.pool.uid}, object, function (data, status, request) {
                if(status)
                {
                    if(this.showDeleted) {
                        object.deleted = true;
                    } else {
                        this.objects.$remove(object);
                    }


                    if (object.tag == 'file') {
                        this.pool.objects.files--;
                    }
                }
            }.bind(this)).error(function (data, status, request) {
                toastr.error(data.errors[0], 'Error: ' + data.message);
            });
        },
    }
};
