import main from './extensions/main.js';
import view from './extensions/view.js';

var App = Vue.extend(main);

var View = Vue.extend(view);


var router = new VueRouter();

router.map({
    '/:pool/*parent_uid': {
        name: 'pool',
        component: View
    }
});

router.start(App, '#societyAdmin');
