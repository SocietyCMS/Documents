<div class="ui modal" id="newPool">
    <div class="header"> {{trans('core::elements.action.create resource', ['name'=>trans('documents::documents.title.pool')])}}</div>
    <div class="content">
        <form class="ui form">
            <div class="ui field">
                <label>{{trans('documents::documents.modal.title')}}</label>
                <input type="text" placeholder="Pool Title..." v-model="newPool.title">
            </div>
            <div class="ui three wide field">
                <label>{{trans('documents::documents.modal.quota')}}</label>
                <div class="ui right labeled input">
                    <input id="quota" type="number" v-model="newPool.quota | quotaToMB" style="text-align:right;"
                           number>
                    <div class="ui label">
                        MB
                    </div>
                </div>
            </div>

            <h3 class="ui dividing header">
                {{trans('documents::documents.modal.permissions')}}
            </h3>

            @include('user::backend.fields.roles',[
            'label' => trans('documents::messages.these roles can view files'),
            'v_model'=> 'newPool.readRoles'
            ])

            @include('user::backend.fields.roles',[
            'label' => trans('documents::messages.these roles can upload and edit files'),
            'v_model'=> 'newPool.writeRoles'
            ])

            <button class="ui green inverted fluid button" v-on:click="createPool"
                    v-bind:class="{'disabled':!newPool.title}">
                <i class="checkmark icon"></i>
                {{ trans('core::elements.button.create') }}
            </button>

        </form>
    </div>
</div>

