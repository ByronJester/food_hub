<template>
<div>
    <Navigation :auth="auth"></Navigation>

        <section class="text-gray-600 body-font">
            <div class="container py-12 mx-auto">
            </div>
        </section>

        <section class="text-gray-600 body-font">
            <div class="container  mx-auto">
                <div class="flex mx-auto flex-wrap mb-4 mt-10" v-if="auth.role == 2">
                    <a class="bg-gray-100 border-yellow-500 text-yellow-500 sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none tracking-wider rounded-t">
                        <i class="fa-solid fa-users mr-3"></i> Staffs
                    </a>
                </div>
                <div class="flex mx-auto flex-wrap mb-4 mt-10" v-else>
                    <a  :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeTab == 'owner'}" @click="activeTab = 'owner'" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none tracking-wider rounded-t">
                        <i class="fa-solid fa-shop mr-3"></i> Local Food Joints
                    </a>
                    <a :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeTab == 'customer'}" @click="activeTab = 'customer'" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider">
                        <i class="fa-solid fa-users mr-3"></i>Customers
                    </a>
                </div>
                <div class="flex flex-wrap sm:mb-2 -mx-2">
                    <div class="w-full overflow-hidden rounded-lg shadow">
                        <div class="bg-white px-2 py-5 border-b border-gray-200 sm:px-6">
                            <div class="ml-2 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap" v-if="auth.role == 2">
                               <div class="ml-2 mt-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Staffs
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Showing staffs
                                    </p>
                                </div>
                                <div class="mt-4 flex sm:mt-0 sm:ml-2">
                                    <button type="button" class="w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm" @click="openUserModal()">Add Staff</button>
                                </div>
                            </div>
                            <div class="ml-2 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap" v-else>
                                <div class="ml-2 mt-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" v-if="activeTab == 'owner'">
                                        Local Food Joints
                                    </h3>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" v-else>
                                        Customers
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500" v-if="activeTab == 'owner'">
                                        Showing local food joints
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500" v-else>
                                        Showing customers
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full overflow-x-auto">

                            <Table :columns="columns" :rows="users" :keys="keys" :selected.sync="user"/>
                        </div>
                        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9">
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

		<!-- Modal create staff form -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="isNew" @keydown.escape.prevent.stop="closeUserModal()" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="closeUserModal()"
						class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<span class="sr-only">Close</span>
						<!-- Heroicon name: outline/x -->
						<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
						stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								Create staff
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
							<div class="relative mb-2">
								<label for="name" class="leading-7 text-sm text-gray-600">Name</label>
                            	<input type="text" id="name" name="name" placeholder="Name" required v-model="form.name" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('name', saveError)}} 
								</span>
							</div>
							<div class="relative mb-2">
								<label for="registration-email" class="leading-7 text-sm text-gray-600">Email</label>
                            	<input type="email" id="registration-email" name="Email" placeholder="Email" required v-model="form.email" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('email', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="phone" class="leading-7 text-sm text-gray-600">Contact Number</label>
                            	<input type="text" id="phone" name="contact" placeholder="Contact Number" required v-model="form.phone" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('phone', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="address" class="leading-7 text-sm text-gray-600">Address</label>
                            	<input type="text" id="address" name="address" placeholder="Street, Barangay, Town" required v-model="form.address" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('address', saveError)}}
								</span>
							</div>
						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="submit();"
						>
							Submit
						</button>
						<button type="button" @click="closeUserModal()" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>


		<!-- Modal view user -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="user && auth.role != 2"  v-if="user && auth.role != 2" @keydown.escape.prevent.stop="user = null" x-transition>
			<div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="user = null"
						class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<span class="sr-only">Close</span>
						<!-- Heroicon name: outline/x -->
						<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
						stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								{{ user.name }}
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							<div class="relative mb-2">
                                <Toggle :value="user.is_active" :url="'/users/deactivate-reactivate'" :id="user.id" class="ml-5"/>
							</div>
                            <div class="w-full flex flex-col">
                                <div class="w-full pl-5 font-bold text-xl">
                                    ID Pictures
                                </div>

                                <div v-for="p in user.picture_id" :key="p">
                                    <img :src="'/images/uploads/' + p" 
                                        style="height: 220px; width: 100%"
                                        class="p-5"
                                    >
                                </div>


                                <div class="w-full pl-5 font-bold text-xl" v-if="user.permit">
                                    Business Permit
                                </div>

                                <div class="w-full" v-if="user.permit">
                                    <img :src="'/images/uploads/' + user.permit" 
                                        style="height: 220px; width: 100%"
                                        class="p-5"
                                    >
                                </div>
                            </div>
						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button type="button" @click="user = null" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Close</button>
					</div>
									
				</div>
			</div>
		</div>
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
            this.user= null
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

        console.log(this.options)
    },

    methods: {
        openUserModal(){

            this.isNew = true

            this.form = {
                name: null,
                phone: null,
                email: null,
                address: null
            }
        },

        closeUserModal(){

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

