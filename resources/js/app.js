require('./bootstrap');

import Vue from 'vue'
// booststrap vue
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
// axios
import axios from 'axios';

window.Vue = require('vue');
Vue.use(BootstrapVue)

// product page
Vue.component('product-component', require('./components/ProductComponent.vue').default);
Vue.component('product-images-component', require('./components/ProductImagesComponent.vue').default);

// shoppingcart
Vue.component('shopping-cart-component', require('./components/ShoppingCartComponent.vue').default);

// orders customer
Vue.component('customer-orders-component', require('./components/OrdersComponent.vue').default);


// admin page Components
Vue.component('admin-component', require('./components/admin/AdminComponent.vue').default);

// admin customers Components
Vue.component('admin-customers-component', require('./components/admin/customers/CustomersComponent.vue').default);

// admin products Components
Vue.component('admin-products-component', require('./components/admin/products/ProductsComponent.vue').default);
Vue.component('admin-products-productversions-component', require('./components/admin/products/ProductVersionsComponent.vue').default);
Vue.component('admin-products-images-component', require('./components/admin/products/ImagesComponent.vue').default);
Vue.component('admin-products-sizes-component', require('./components/admin/products/SizesComponent.vue').default);

// admin data Componentes
Vue.component('admin-data-color-component', require('./components/admin/data/ColorComponent.vue').default);
Vue.component('admin-data-length-component', require('./components/admin/data/LengthComponent.vue').default);
Vue.component('admin-data-status-component', require('./components/admin/data/StatusComponent.vue').default);
Vue.component('admin-data-waist-component', require('./components/admin/data/WaistComponent.vue').default);



// Currency filter
Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        value = +value;
    }
    var formatter = new Intl.NumberFormat('en-eur', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2
    });

    return formatter.format(value);
});

const app = new Vue({
    el: '#app',
});
