<div class="ui dropdown icon item">
    <i class="wrench icon"></i>

    <div class="menu">
        <div class="item">
            <i class="dropdown icon"></i>
            <span class="text">New</span>

            <div class="menu">
                <div class="item">Document</div>
                <div class="item">Image</div>
            </div>
        </div>
        <div class="item">
            Open...
        </div>
        <div class="item">
            Save...
        </div>
        <div class="item">Edit Permissions</div>
        <div class="divider"></div>
        <div class="header">
            Export
        </div>
        <div class="item">
            Share...
        </div>
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

<div class="ui breadcrumb item">
    <a class="section">Home</a>
    <div class="divider"> / </div>
    <a class="section">Store</a>
    <div class="divider"> / </div>
    <div class="active section">T-Shirt</div>
</div>


<div class="right menu">
    <div class="item">
        <button class="circular ui icon basic button active">
            <i class="list layout icon button"></i>
        </button>

        <button class="circular ui icon basic button">
            <i class="grid layout icon button"></i>
        </button>
    </div>

    <div class="ui right aligned category search item">
        <div class="ui transparent icon input">
            <input class="prompt" type="text" placeholder="Search...">
            <i class="search link icon"></i>
        </div>
    </div>
</div>
