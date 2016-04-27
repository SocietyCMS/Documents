import main from './extensions/main.js';
import view from './extensions/view.js';

Vue.filter('advancedSort', function (arr, sortKey, reverse) {
    var orderBy = Vue.filter('orderBy');
    var orderdArrayKey = orderBy(arr, sortKey, reverse);
    return orderBy(orderdArrayKey, 'tag', -1);
});

Vue.filter('quotaToMB', function (value) {
    return value / 1024 / 1024;
});

Vue.filter('quotaToMB', {
    read: function(val) {
        return val / 1024 / 1024;
    },
    write: function(val, oldVal) {
        return val * 1024 * 1024;
    }
})



var App = Vue.extend(main);

var View = Vue.extend(view);

var router = new VueRouter();

router.map({
    '/:pool/*parent_uid': {
        name: 'path',
        component: View
    }
});

router.start(App, '#societyAdmin');
