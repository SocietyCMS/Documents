import main from './extensions/main.js';
import view from './extensions/view.js';

import { dragAndDropModule, fineUploaderBasicInstanceImages } from './extensions/upload.js';


Vue.filter('advancedSort', function (arr, sortKey, reverse) {
    var orderBy = Vue.filter('orderBy');
    var orderdArrayKey = orderBy(arr, sortKey, reverse);
    return orderBy(orderdArrayKey, 'tag', -1);
});


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
