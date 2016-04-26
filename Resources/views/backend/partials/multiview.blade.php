<div class="ui top attached blue progress" v-show="uploadInProgress" id="uploadProgressBarTop">
    <div class="bar"></div>
</div>

<div class="ui active inverted dimmer" v-show="showLoader">
    <div class="ui medium text loader">Loading</div>
</div>

<router-view  :pool="selectedPool" :objects="objects"></router-view>

<div class="ui bottom attached blue progress" v-show="uploadInProgress"  id="uploadProgressBarBottom">
    <div class="bar"></div>
</div>