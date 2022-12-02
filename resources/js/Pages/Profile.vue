<template>
    <div>
        <Navigation :auth="auth"></Navigation>
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap lg:w-3/5 sm:mx-auto sm:mb-2 -mx-2">
                <div class="container px-5  mx-auto flex flex-wrap items-center">
                    <div class="bg-white rounded-lg p-8 flex flex-col  w-full mt-10 md:mt-12 bg-white shadow-lg">
                        <div class="text-center mb-8 ">
                            <h1 class="sm:text-2xl text-xl font-medium text-center title-font text-gray-900 mb-2">Profile Information</h1>
                            <p class="text-sm leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto">Update your account's profile information.</p>
                        </div>
                        <div class="relative p-2">
                            <label for="name" class="leading-7 text-sm text-gray-600">Name</label>
                            <input type="text" id="name" name="name" v-model="form.name" required
                                placeholder="Name"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('name', saveError)}}
                                </span>
                        </div>
                        <div class="relative p-2">
                            <label for="contact-number" class="leading-7 text-sm text-gray-600">Contact Number</label>
                            <input type="text" id="contact-number" name="contact-number" v-model="form.phone" required
                                placeholder="Contact Number"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('phone', saveError)}}
                                </span>
                        </div>
                        <div class="relative p-2">
                            <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                            <input type="text" id="email" name="email" v-model="form.email" required
                                placeholder="Email"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('email', saveError)}}
                                </span>
                        </div>
                        <div class="relative p-2">
                            <label for="address" class="leading-7 text-sm text-gray-600">Address</label>
                            <input type="text" id="address" name="address" v-model="form.address" required
                                placeholder="Address"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('address', saveError)}}
                                </span>
                        </div>
                        <div class="relative p-2">
                            <label for="password" class="leading-7 text-sm text-gray-600">Password</label>
                            <input type="password" id="password" name="password" v-model="form.password" placeholder="******"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('password', saveError)}}
                                </span>
                        </div>
                        <div class="relative p-2">
                            <label for="confirm_password" class="leading-7 text-sm text-gray-600">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" v-model="form.confirm_password" placeholder="******"
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2" >
                                    {{validationError('confirm_password', saveError)}}
                                </span>
                        </div>
                        <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button class="w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
                                @click="submit()"
                            >
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

		<!-- Modal verification code form -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="isOTP" @keydown.escape.prevent.stop="isOTP = false" x-transition>
			<div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								Verification
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
							<div class="relative mb-2">
								<label for="verification-code" class="leading-7 text-sm text-gray-600"></label>
                            	<input type="text" id="verification-code" name="food-joint-name" placeholder="Enter Code" v-model="form.code" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('code', saveError)}}
								</span>
							</div>

						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="submit()"
						>
							Proceed
						</button>
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
    data(){
        return {
            form: {
                id: null,
                name: null,
                phone: null,
                email: null,
                address: null,
                password: null,
                confirm_password: null,
                code: null,
            },
            saveError: null,
            isMobile: window.screen.width <= 700,
            isOTP: false
        }
    },

    created(){
        this.form.id = this.auth.id
        this.form.name = this.auth.name
        this.form.phone = this.auth.phone
        this.form.email = this.auth.email
        this.form.address = this.auth.address
    },

    methods: {
        submit(){
            var req = this.form

            if(!req.password && !req.confirm_password) {
                delete req["password"];
                delete req["confirm_password"];
            }

            axios.post(this.$root.route + "/users/edit-profile", req)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						// alert("Profile successfully updated.");

						// this.saveError = null

						// location.reload()
                        if(this.form.code == null) {
                            this.isOTP = true;
                        } else {
                            location.reload()
                        }
                        
					}
				})
        },

        openOtpModal(){
            var modal = document.getElementById("otpModal");

            modal.style.display = "block";

            this.isOTP = true
        },

        closeOtpModal(){
            var modal = document.getElementById("otpModal");

            modal.style.display = "none";

            this.isOTP = false
        },
    }
}
</script>