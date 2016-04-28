<div class="ui modal" id="permissionPool">

    <i class="close icon"></i>
    <div class="header">{{trans('documents::documents.menu.manage pools')}}</div>


    <div class="content">
        <form class="ui form">

            <div class="ui secondary segment" v-for="pool in pools">

                <h3 class="ui dividing header">
                    @{{ pool.title }}
                </h3>

                <div class="ui three wide field">
                    <label>{{trans('documents::documents.modal.quota')}}</label>
                    <div class="ui right labeled input">
                        <input id="quota" type="number" v-model="pool.quota | quotaToMB" style="text-align:right;"
                               number v-on:change="updatePool(pool)">
                        <div class="ui label">
                            MB
                        </div>
                    </div>
                </div>

                @include('user::backend.fields.roles',[
                'label' => trans('documents::messages.these roles can view files'),
                'v_model'=> 'pool.permissions.read',
                'v_change' => 'updatePool(pool)'
                ])

                @include('user::backend.fields.roles',[
                'label' => trans('documents::messages.these roles can upload and edit files'),
                'v_model'=> 'pool.permissions.write',
                'v_change' => 'updatePool(pool)'
                ])

            </div>

        </form>
    </div>
</div>

