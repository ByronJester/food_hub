<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
            <div class="w-full mt-3">
                <span class="text-2xl ml-2 font-bold">
                    <i class="fa-solid fa-users mr-3"> </i>Users 
                </span>
            </div>

            <div class="w-full h-full mt-5 flex flex-row">

                <div :style="{width: user ? '80%' : '100%'}" class="mx-2">
                    <Table :columns="columns" :rows="users" :keys="keys" :selected.sync="user"/>
                </div>

                <div style="width: 20%;" 
                    class="mx-2 flex flex-col" v-if="user"
                >
                    <div class="w-full h-auto"
                        style="border: 1px solid #FFD740; border-radius: 5px"
                    >   
                        <div class="w-full py-2 pr-4">
                            <span class="float-right cursor-pointer"
                                @click="user = null"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full text-center font-bold text-xl mt-2 mb-5 mx-4">
                            {{ user.name }}<Toggle :value="user.is_active" :url="'/users/deactivate-reactivate'" :id="user.id" class="ml-5"/>
                        </div>

                        <div class="w-full">
                            <img :src="'/images/uploads/' + user.picture_id" 
                                style="height: 220px; width: 100%"
                                class="p-5"
                            >
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </Navigation>
    
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import Table from "../Components/Table";
import Toggle from '../Components/Toggle.vue';


export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table,
        Toggle
    },

    data() {
        return {
            columns: [
                'Name', 'Email', 'Contact'
            ],
            keys : [
                {
                    label: 'name',
                },
                {
                    label: 'email',
                },
                {
                    label: 'phone',
                },
            ],
            users: [],
            user: null
        }
    },

    watch: {
        user(arg) {
            console.log(arg)
        }
    },

    mounted() {
        console.log(this.options)
        this.users = this.options.users
    }
}

</script>
