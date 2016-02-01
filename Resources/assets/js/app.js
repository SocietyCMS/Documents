import pooltreeview from './components/poolTreeview.vue';

// Define some components
var Foo = Vue.extend({
    template: '<p>This is foo!</p>'
})

var Bar = Vue.extend({
    template: '<p>This is bar!</p>'
})


var App = Vue.extend({
    data() {
        return {pools: []}
    },
    components: {pooltreeview},

    ready() {
        var resource = this.$resource(resourceDocumentsPoolIndex);
        // save item
        resource.get({}).then(function (response) {
            this.pools = response.data.data;
            this.selectFirstPool()
        }.bind(this), function (response) {
            toastr.error(response.data.message, 'Error: ' + response.data.status_code);
        }.bind(this));
    },
    methods: {
        selectFirstPool() {
            this.selectedPool = this.pools[1];
        }
    }
});

// Create a router instance.
// You can pass in additional options here, but let's
// keep it simple for now.
var router = new VueRouter()

// Define some routes.
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// Vue.extend(), or just a component options object.
// We'll talk about nested routes later.
router.map({
    '/:pool': {
        component: Foo
    },
    '/bar': {
        component: Bar
    }
})

// Now we can start the app!
// The router will create an instance of App and mount to
// the element matching the selector #app.
router.start(App, '#societyAdmin')
