<template>
     <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col --main--div">
            <div style="width: 100%">
                <Table :columns="columns" :rows="options.trails" :keys="keys" :selected.sync="selected"/>
            </div>
        </div>
    </Navigation>
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import Table from "../Components/Table";
import Toggle from '../Components/Toggle.vue';
import axios from "axios";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table,
        Toggle
    },
    data(){
        return {
            selected: null,
            columns: ['Logs'],
            keys: [
                {
                    label: 'description'
                }
            ]
        }
    },

    created(){

    },

    methods: {

    }
}
</script>

<style scoped>

</style>