<template>
    <div class="ui pool list">
        <div class="item">
            <i class="home icon"></i>

               <i class="minus square outline icon" v-if="selected == pool"></i>
               <i class="plus square outline icon" v-else></i>

           <div class="content">

               <a v-link="{ name: 'path', params: { pool: pool.uid, parent_uid: 'null'}}" class="header">{{pool.title}}</a>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['pool', 'selected']
    };
</script>
