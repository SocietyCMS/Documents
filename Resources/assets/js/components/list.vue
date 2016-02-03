<template>


    <table class="ui selectable table" id="file-list-table">
        <thead>
        <tr>
            <th class="therteen wide filename"
                v-on:click="sortBy('title')"
                v-bind:class="{ 'sorted': sortKey == 'tag', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                Title
            </th>
            <th class="">
            </th>
            <th class="one wide right aligned"
                v-on:click="sortBy('objectSize')"
                v-bind:class="{ 'sorted': sortKey == 'objectSize', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                Size
            </th>
            <th class="two wide right aligned"
                v-on:click="sortBy('created_at.timestamp')"
                v-bind:class="{ 'sorted': sortKey == 'created_at.timestamp', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                Modified
            </th>
        </tr>
        </thead>
        <tbody>

        <tr class="object" v-bind:class="{'negative':object.deleted}" v-for="object in objects | filterBy filterKey | advancedSort sortKey sortReverse">
            <td class="selectable">
                <a href="" v-on:click="objectOpen(object, $event)">
                    <i v-bind:class="object.mimeType | semanticFileTypeClass" class="icon"></i>

                    <div class="ui text" v-if="editObject != object">{{ object.title }} <span
                            class="ui gray text"
                            v-if="object.fileExtension">.{{ object.fileExtension }}</span>
                    </div>
                    <div class="ui input" v-else>
                        <input type="text" v-model="object.title" v-on:blur="objectBlurEdit(object, $event)"
                               v-on:keydown="objectKeydownEdit(object, $event)" id="objectEditInput-{{object.uid}}">
                    </div>

                </a>
            </td>
            <td class="collapsing">

                <button class="circular ui icon positive button" v-if="object.deleted" v-on:click="objectRestore(object, $event)"><i class="life ring icon"></i></button>
                <button class="circular ui icon negative button" v-if="object.deleted" v-on:click="objectForceDelete(object, $event)"><i class="trash icon"></i></button>

                <button class="circular ui icon button" v-if="!object.deleted"><i class="share alternate icon"></i></button>

                <div class="ui top left pointing dropdown" v-if="!object.deleted">
                    <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>

                    <div class="menu">
                        <div class="item" v-on:click="objectOpen(object, $event)">
                            Open...
                        </div>
                        <div class="item" v-on:click="objectEdit(object, $event)">
                            Rename
                        </div>
                        <div class="item">
                            <i class="folder icon"></i>
                            Move to folder
                        </div>
                        <div class="item" v-on:click="objectDelete(object, $event)">
                            <i class="trash icon"></i>
                            Move to trash
                        </div>
                    </div>
                </div>

            </td>
            <td class="right aligned collapsing" v-if="object.tag == 'file'">{{ object.objectSize | humanReadableFilesize }}</td>
            <td class="right aligned collapsing" v-if="object.tag == 'folder'">-</td>
            <td class="right aligned collapsing">{{ object.created_at.diffForHumans }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th><span v-if="folder_meta">{{ folder_meta.objects.folders }}
                            folders and {{ folder_meta.objects.files }} files</span></th>
            <th></th>
            <th class="right aligned collapsing"></th>
            <th></th>
        </tr>
        </tfoot>
    </table>

</template>
