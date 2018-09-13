import alias_members from './components/AliasMembersItemsComponent.vue';

var vm = new Vue({
    el: '#alias_members_component',
    components:{
        alias_members,
    },
    data() {
        return {
            teste: false
        }
    }
});

global.vm = vm;
