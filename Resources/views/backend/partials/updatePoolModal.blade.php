<div class="ui modal" id="permissionPool">

    <i class="close icon"></i>
    <div class="header">Pool Permissions</div>


    <div class="content">
        <form class="ui form">

            <div class="ui secondary segment" v-for="pool in pools">

                <h3 class="ui dividing header">
                    @{{ pool.title }}
                </h3>

                <div class="ui three wide field">
                    <label>Quota</label>
                    <div class="ui right labeled input">
                        <input id="quota" type="number" v-model="pool.quota | quotaToMB" style="text-align:right;" number v-on:change="updatePool(pool)">
                        <div class="ui label">
                            MB
                        </div>
                    </div>
                </div>


                @include('user::backend.fields.roles',[
                'label' => 'These Roles can view files:',
                'v_model'=> 'pool.permissions.read',
                'v_change' => 'updatePool(pool)'
                ])

                @include('user::backend.fields.roles',[
                'label' => 'These Roles can upload and edit files:',
                'v_model'=> 'pool.permissions.write',
                'v_change' => 'updatePool(pool)'
                ])

            </div>

        </form>
    </div>
</div>

