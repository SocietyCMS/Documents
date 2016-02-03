<template>

    <div class="ui breadcrumb item">

        <a class="section" v-link="{ name: 'path', params: { pool: pool.uid, parent_uid: 'null'}}"  v-if="pool">
            <i class="home icon"></i>
            {{ pool.title }}
        </a>

        <template v-for="item in containing_ns_path">
            <div class="divider"> / </div>
            <a class="ui section text" v-link="{ name: 'path', params: { pool: pool.uid, parent_uid: item.uid}}"
               v-bind:class="{ 'black': meta.patent_uid == item.uid }">{{ item.title }}</a>
        </template>

        <div class="divider"> / </div>

    </div>
</template>

<script>
    export default {
        props: ['pool', 'meta'],
        computed: {
            containing_ns_path: function () {
                if (
                        this.meta &&
                        this.meta.containing_ns_path &&
                        this.meta.containing_fq_uid

                ) {

                    var currentPath = this.meta.containing_ns_path.split('/');
                    var currentPathUid = this.meta.containing_fq_uid.split(':');

                     var returnObject = [];

                    currentPath.forEach(function (element, index, array) {
                        returnObject.push({
                            'uid': currentPathUid[index],
                            'title': element
                        });
                    });

                    return returnObject;
                }
                return [];
            }
        },
    };
</script>
