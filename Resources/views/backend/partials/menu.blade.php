<div class="ui dropdown icon item" v-if="!selectedPool || selectedPool.userPermissions.write">
    <i class="wrench icon"></i>

    <div class="menu">
        <div class="item" id="uploadFileButton" v-show="selectedPool && selectedPool.userPermissions.write">
            {{trans('documents::documents.action.upload')}}
        </div>
        <div class="item" v-on:click="createFolder(object, $event)" v-if="selectedPool && selectedPool.userPermissions.write">
            {{trans('documents::documents.action.new folder')}}
        </div>
        @permission('documents::manage-pools')
            <div class="divider"></div>
            <div class="item" v-on:click="createPoolModal">{{trans('core::elements.action.create resource', ['name'=>trans('documents::documents.title.pool')])}}</div>
            <div class="item" v-on:click="permissionPoolModal">{{trans('documents::documents.menu.manage pools')}}</div>
        @endpermission
    </div>
</div>

<div class="item">
    <div class="circular ui icon basic button" v-on:click="redirectBack">
        <i class="chevron left icon button"></i>
    </div>

    <div class="circular ui icon basic button" v-on:click="redirectForward">
        <i class="chevron right icon button"></i>
    </div>

    <div class="circular ui icon basic button" v-on:click="redirectUp">
        <i class="level up icon button"></i>
    </div>
</div>

<breadcrumb :pool="selectedPool" :meta="meta"></breadcrumb>

<div class="right menu">
    <div class="item">
        <button class="circular ui icon basic button active">
            <i class="list layout icon button"></i>
        </button>

        <button class="circular ui icon basic disabled button">
            <i class="grid layout icon button"></i>
        </button>
    </div>

    <div class="ui right aligned category search item">
        <div class="ui transparent icon disabled input">
            <input class="prompt" type="text" placeholder="Search...">
            <i class="search link icon"></i>
        </div>
    </div>
</div>
