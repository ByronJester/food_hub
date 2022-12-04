<template>
    <div>
        <Navigation :auth="auth"></Navigation>
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap lg:w-3/5 sm:mx-auto sm:mb-2 -mx-2">
                <div class="container px-5  mx-auto flex flex-wrap items-center">
                    <div class="bg-white rounded-lg p-8 flex flex-col  w-full mt-10 md:mt-12 bg-white shadow-lg">
                        <div class="text-center mb-8 ">
                            <h1 class="sm:text-2xl text-xl font-medium text-center title-font text-gray-900 mb-2">Audit Trails</h1>
                            <p class="text-sm leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto"></p>
                        </div>
                        <div class="relative p-2">
                            <Table :columns="columns" :rows="options.trails" :keys="keys" :selected.sync="selected"/>
                        </div>
                        <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </div>
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