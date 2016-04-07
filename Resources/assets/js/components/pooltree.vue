<template>
    <a v-link="{ name: 'path', params: { pool: pool.uid, parent_uid: 'null'}}" v-bind:class="{'active': selected == pool}" class="item">
        {{pool.title}}
    </a>
</template>

<script>
    export default {
        props: ['pool', 'selected']
    };
</script>
