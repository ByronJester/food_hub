<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
            <div class="w-full mt-3" v-if="auth.role == 2">
                <span class="text-2xl ml-2 font-bold">
                    <i class="fa-solid fa-users mr-3"> </i>Staffs 
                </span>
            </div>

            <div class="w-full flex flex-row pt-10 pl-3" v-else style="height: 100px">
                <div class="w-auto --text mr-10 cursor-pointer" :class="{'--active' : activeTab == 'owner'}" @click="activeTab = 'owner'">
                    <span class="px-10"><i class="fa-solid fa-shop mr-3"></i> Local Food Joints </span>
                </div>

                <div class="w-auto --text text-center cursor-pointer" :class="{'--active' : activeTab == 'customer'}" @click="activeTab = 'customer'">
                    <span class="px-10"> <i class="fa-solid fa-users mr-3"> </i> Customers </span>
                </div>
            </div>

            <div class="w-full pt-10 pr-2">
                <span class="float-right cursor-pointer" @click="openUserModal()">
                    <i class="fa-solid fa-plus fa-2xl"></i>
                </span>
            </div>

            <div class="w-full h-full pt-5 flex flex-row pt-3" v-if="!isNew">
                <div :style="{width: user & auth.role != 2 ? '80%' : '100%'}" class="mx-2">
                    <Table :columns="columns" :rows="users" :keys="keys" :selected.sync="user"/>
                </div>

                <div style="width: 20%;" 
                    class="mx-2 flex flex-col" v-if="user && auth.role != 2"
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

            <div id="userModal" class="userModal">
                <!-- Modal content -->
                <div class="user-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                            Create Staff
                        </span>

                        <span class="float-right cursor-pointer"
                            @click="closeUserModal()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full flex flex-col mt-4">
                        <div class="px-5 py-3">
                        <label for="name">Name:</label><br>
                        <input type="text" class="--input py-1" v-model="form.name">
                        <span class="text-xs text-red-500">{{validationError('name', saveError)}} </span>
                    </div>

                    <div class="px-5 py-3">
                        <label for="name">Contact:</label><br>
                        <input type="text" class="--input py-1" v-model="form.phone">
                        <span class="text-xs text-red-500">{{validationError('phone', saveError)}} </span>
                    </div>

                    <div class="px-5 py-3">
                        <label for="name">Email:</label><br>
                        <input type="text" class="--input py-1" v-model="form.email">
                        <span class="text-xs text-red-500">{{validationError('email', saveError)}} </span>
                    </div >

                    <div class="px-5 py-3">
                        <label for="name">Address:</label><br>
                        <input type="text" class="--input py-1" v-model="form.address">
                        <span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
                    </div >

                    <div class="px-5 pt-3 pb-5">
                        <button class="--btn py-2" @click="submit()">
                            Create
                        </button>
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
import axios from "axios";

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
                'Name', 'Email', 'Contact', 'Address'
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
                {
                    label: 'address',
                }
            ],
            users: [],
            user: null,
            activeTab: 'owner',
            saveError: null,
            form: {
                name: null,
                phone: null,
                email: null,
                address: null
            },
            isNew: false
        }
    },

    watch: {
        activeTab(arg) {
            this.users = this.options.users.filter( x => { return x.user_type == arg})
        }
    },

    mounted() {
        if(this.auth.role == 2) {
            this.activeTab = 'staff'
        }

        this.users = this.options.users.filter( x => { return x.user_type == this.activeTab})

        if(this.auth.role == 1) {
            this.columns.push('Status')

            this.keys.push({ label : 'verified' })
        }
    },

    methods: {
        openUserModal(){
            var modal = document.getElementById("userModal");

            modal.style.display = "block";

            this.isNew = true

            this.form = {
                name: null,
                phone: null,
                email: null,
                address: null
            }
        },

        closeUserModal(){
            var modal = document.getElementById("userModal");

            modal.style.display = "none";

            this.isNew = false

            this.form = {
                name: null,
                phone: null,
                email: null,
                address: null
            }
        },

        submit() {
            axios.post(this.$root.route + "/users/create-staff", this.form)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Staff successfully created.");

						this.saveError = null

						location.reload()
					}
				})
        }
    }
}

</script>

<style scoped>
.--active {
    border-bottom: 2px solid #E4B934;
}

.--text {
	font-size: calc(.1em + 1vw);
}

.userModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 20%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.user-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.--input {
    width: 100%;
    height: 40px;
    border: 1px solid black;
    border-radius: 5px;
    text-align: center;
}

.--btn {
    background: #E4B934;
    border-radius: 10px;
    width: 100%;
    text-align: center;
    color: black;
}
</style>
