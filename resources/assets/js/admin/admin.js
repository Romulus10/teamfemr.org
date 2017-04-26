require('./bootstrap');

// Register the map component
Vue.component('femr-map', require('./components/FemrMap.vue'));

const app = new Vue({
    el: '#app',

    data(){

        return {

        }
    },
    mounted(){

        // TODO - Do this better if this admin mobile menu is kept
        var burger = document.querySelector('.nav-toggle');
        var menu = document.querySelector('.nav-menu');
        burger.addEventListener('click', function() {

            burger.classList.toggle('is-active');
            menu.classList.toggle('is-active');
        });
    }
});