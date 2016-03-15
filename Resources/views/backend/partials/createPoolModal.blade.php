<div class="ui modal"  id="newPool">
    <div class="header"> Create a new Pool</div>
    <div class="content">
        <form class="ui form">
            <div class="ui field">
                <label>Title</label>
                <input type="text" placeholder="Pool Title..." v-model="newPool.title">
            </div>
            <div class="ui three wide field">
                <label>Quota</label>
                <div class="ui right labeled input">
                    <input id="quota" type="number" v-model="newPool.quota | quotaToMB" style="text-align:right;" number>
                    <div class="ui label">
                        MB
                    </div>
                </div>
            </div>

            <h3 class="ui dividing header">
                Permissions
            </h3>

            @include('user::backend.fields.roles',[
            'label' => 'These Roles can view files:',
            'v_model'=> 'newPool.readRoles'
            ])

            @include('user::backend.fields.roles',[
            'label' => 'These Roles can upload and edit files:',
            'v_model'=> 'newPool.writeRoles'
            ])

            <button class="ui green inverted fluid button" v-on:click="createPool" v-bind:class="{'disabled':!newPool.title}">
                <i class="checkmark icon"></i>
                Create
            </button>



        </form>
    </div>
</div>

