<template>

    <div class="ui list">
        <div class="item" v-for="object in objects">

            <a v-link="{ name: 'pool', params: { pool: pool.uid, parent_uid: object.uid }}"
               class="header" v-if="object.tag=='folder'">{{ object.title }}
            </a>
            <span v-else>{{ object.title }}</span>
        </div>
    </div>

</template>
