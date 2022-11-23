<template>
     <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
            <div class="w-full mt-3">
                <span class="text-2xl ml-2 font-bold">
                    <i class="fa-solid fa-user mr-3"> </i>Profile
                </span>
            </div>

            <div class="w-full h-full mt-5 flex justify-center items-center" v-if="!isOTP">
                <div class="flex flex-col w-full md:w-1/4" style="border: 1px solid black; border-radius: 10px">
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

                    <div class="px-5 py-3">
                        <label for="name">Password:</label><br>
                        <input type="password" class="--input py-1" v-model="form.password" placeholder="********">
                        <span class="text-xs text-red-500">{{validationError('password', saveError)}} </span>
                    </div>

                    <div class="px-5 py-3">
                        <label for="name">Confirm Password:</label><br>
                        <input type="password" class="--input py-1" v-model="form.confirm_password" placeholder="********">
                        <span class="text-xs text-red-500">{{validationError('confirm_password', saveError)}} </span>
                    </div>

                    <div class="px-5 pt-3 pb-5">
                        <button class="--btn py-2" @click="submit()">
                            Submit
                        </button>
                    </div>

                </div>
            </div>

            <div id="otpModal" class="otpModal">
				<!-- Modal content -->
				<div class="otp-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full">
						<span class="text-lg font-bold">
							Verification Code
						</span>
					</div>

					<div class="w-full flex flex-col mt-4">
						<div class="w-full">
							<input type="text" class="w-full  my-2 --input text-center" style="border: 1px solid black"
								placeholder="Verification Code" v-model="form.code"
							>
							<span class="text-xs text-red-500">{{validationError('code', saveError)}} </span><br>
						</div>
					</div>

					<div class="w-full flex flex-col mt-4">
						<button class="w-full --btn py-3" @click="submit()">
							Proceed
						</button>
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
                            this.openOtpModal()
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

<style scoped>
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

.otpModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 40%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.otp-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}
</style>