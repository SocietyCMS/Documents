<template>

    <table class="ui selectable table" id="file-list-table">
        <thead>
        <tr>
            <th class="therteen wide filename"
                v-on:click="sortBy('title')"
                v-bind:class="{ 'sorted': sortKey == 'tag', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                {{{ 'documents::documents.list.title' | trans }}}
            </th>
            <th class="">
            </th>
            <th class="one wide right aligned"
                v-on:click="sortBy('objectSize')"
                v-bind:class="{ 'sorted': sortKey == 'objectSize', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                {{{ 'documents::documents.list.size' | trans }}}
            </th>
            <th class="two wide right aligned"
                v-on:click="sortBy('created_at.timestamp')"
                v-bind:class="{ 'sorted': sortKey == 'created_at.timestamp', 'ascending':sortReverse>0, 'descending':sortReverse<0}">
                {{{ 'documents::documents.list.modified' | trans }}}
            </th>
        </tr>
        </thead>
        <tbody>

        <tr class="object" v-bind:class="{'negative':object.deleted}" v-for="object in objects | filterBy filterKey | advancedSort sortKey sortReverse">
            <td class="selectable">
                <a v-if="editObject != object" href="" v-on:click="objectOpen(object, $event)">
                    <i v-bind:class="object.mimeType | semanticFileTypeClass" class="icon"></i>

                    <div class="ui text">{{ object.title }}
                        <span class="ui gray text"
                            v-if="object.fileExtension">.{{ object.fileExtension }}
                        </span>
                    </div>

                </a>

                <div class="ui right action left icon input" v-else>
                    <i v-bind:class="object.mimeType | semanticFileTypeClass" class="icon"></i>
                    <input type="text" id="objectEditInput-{{object.uid}}"
                           v-model="object.title"
                           v-on:blur="objectBlurEdit(object, $event)"
                           v-on:keydown="objectKeydownEdit(object, $event)" >
                    <div class="ui icon primary button" v-on:click="objectBlurEdit(object, $event)">
                        <i class="checkmark icon"></i>
                    </div>
                </div>
            </td>
            <td class="collapsing">

                <button class="circular ui icon positive button" v-if="object.deleted && pool.userPermissions.write" v-on:click="objectRestore(object, $event)"><i class="life ring icon"></i></button>
                <button class="circular ui icon negative button" v-if="object.deleted && pool.userPermissions.write" v-on:click="objectForceDelete(object, $event)"><i class="trash icon"></i></button>

                <button class="circular ui icon disabled button" v-if="!object.deleted && pool.userPermissions.write"><i class="share alternate icon "></i></button>

                <div class="ui top left pointing dropdown" v-if="!object.deleted">
                    <button class="circular ui icon button"><i class="ellipsis horizontal icon"></i></button>

                    <div class="menu">
                        <div class="item" v-on:click="objectOpen(object, $event)">
                            {{{ 'documents::documents.contextmenu.open' | trans }}}
                        </div>
                        <div class="item" v-on:click="objectEdit(object, $event)"  v-if="pool.userPermissions.write">
                            {{{ 'documents::documents.contextmenu.rename' | trans }}}
                        </div>
                        <div class="item" v-on:click="objectDelete(object, $event)"  v-if="pool.userPermissions.write">
                            <i class="trash icon"></i>
                            {{{ 'documents::documents.contextmenu.move to trash' | trans }}}
                        </div>
                    </div>
                </div>

            </td>
            <td class="right aligned collapsing" v-if="object.tag == 'file'">{{ object.objectSize | humanReadableFilesize }}</td>
            <td class="right aligned collapsing" v-if="object.tag == 'folder'">-</td>
            <td class="right aligned collapsing">{{ object.created_at.diffForHumans }}</td>
        </tr>
        </tbody>
    </table>

    <div v-if="objects.length == 0">
        <h1 class="ui center aligned icon header" id="noPhotosPlaceholder">
            <i class="grey cloud upload icon"></i>
            {{{ 'documents::documents.info.this pool is empty' | trans }}}
            <div class="sub header" v-if="pool.userPermissions.write">{{{ 'documents::documents.info.drag-drop upload' | trans }}}</div>
        </h1>
    </div>

</template>
