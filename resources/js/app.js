
require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
// booststrap vue
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
// axios
import axios from 'axios';

Vue.use(BootstrapVue)


// om te testen
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// product page
Vue.component('product-component', require('./components/ProductComponent.vue').default);
Vue.component('product-images-component', require('./components/ProductImagesComponent.vue').default);

// shoppingcard
Vue.component('shopping-card-component', require('./components/ShoppingCardComponent.vue').default);


// admin page
Vue.component('admin-component', require('./components/AdminComponent.vue').default);
Vue.component('admin-customers-component', require('./components/AdminCustomersComponent.vue').default);
Vue.component('admin-products-component', require('./components/AdminProductsComponent.vue').default);
Vue.component('admin-product-versions-component', require('./components/AdminProductVersionsComponent.vue').default);

Vue.component('admin-images-component', require('./components/AdminImagesComponent.vue').default);


const app = new Vue({
    el: '#app',

});
