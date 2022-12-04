<template>
    <div class="w-full text-center text-4xl">
        Success
    </div>
</template>


<script>
    import Navigation from '../Layouts/Navigation.vue'
    import axios from "axios";
    import _ from 'lodash';

    export default {
        props: ['auth', 'options'],
        components: {
            Navigation,
        },
        mounted() {
            console.log(this.options)
        }
    }
</script>