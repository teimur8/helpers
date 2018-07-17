import UserProfile from './UserProfile';

import VueWait from 'vue-wait';
import * as uiv from 'uiv';
Vue.use(uiv);

new Vue({
    el: '#user-profile',
    render: h => h(UserProfile),
    wait: new VueWait()
});
