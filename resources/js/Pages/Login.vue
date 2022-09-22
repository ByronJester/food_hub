<template>
	<div class="w-screen h-screen flex flex-row">
		<div style="background: #E6EE9C; width: 20%; height: 100%"
			class="flex justify-center items-center"
		>
			<div v-if="!isRegister"
				class="mx-2" style="border: 1px solid black; border-radius: 5px;"
			>	
				<div class="px-2 py-2">
					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Email" v-model="formloginData.email"
						@keyup.enter="login()"
					>

					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						placeholder="Password" v-model="formloginData.password"
						@keyup.enter="login()"
					>

					<span class="text-red-500 text-xs ml-2" v-if="message">
						{{ message }}
					</span>

					<button class="w-full  my-2 --login__register--button text-center"
						@click="login()"
						:disabled="disableButton()"
						:class="{ 'cursor-not-allowed' : disableButton() }"
					>
						Login
					</button>

					<p class="text-black text-sm ml-14 cursor-pointer hover:underline"
						@click="isRegister = true"
					>
						Doesn't have accout ? Sign Up Here!
					</p>
				</div>
			</div>

			<div class="mx-2" style="border: 1px solid black; border-radius: 5px;" v-else>
				<div class="px-2 py-2">
					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Name" v-model="formRegisterData.name"
					>
					<span class="text-xs text-red-500">{{validationError('name', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Email" v-model="formRegisterData.email"
					>
					<span class="text-xs text-red-500">{{validationError('email', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Contact" v-model="formRegisterData.phone"
					>
					<span class="text-xs text-red-500">{{validationError('phone', saveError)}} </span>

					<select v-model="formRegisterData.role" class="w-full text-center my-2" style="height: 40px; border-radius: 10px">
						<option value="3">Customer</option>
						<option value="2">Restaurant</option>
					</select>

					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						placeholder="Password" v-model="formRegisterData.password"
					>
					<span class="text-xs text-red-500">{{validationError('password', saveError)}} </span>

					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						placeholder="Confirm Password" v-model="formRegisterData.confirm_password"
					>
					<span class="text-xs text-red-500">{{validationError('confirm_password', saveError)}} </span><br>

					<input type="text" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						placeholder="Restaurant Name" v-model="formRegisterData.restaurant_name"
						v-if="formRegisterData.role == 2"
					>
					<span class="text-xs text-red-500">{{validationError('restaurant_name', saveError)}} </span>

					<div>
						<label class="mt-1 font-bold">ID Picture</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('picture_id', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('picture_id', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2">
						<label class="mt-1 font-bold">Restaurant Display Picture</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('image', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('image', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2">
						<label class="mt-1 font-bold">Restaurant Banner</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('banner', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('banner', saveError)}} </span><br>
					</div>

					<button class="w-full  my-2 --login__register--button text-center"
						@click="register()"
					>
						Register
					</button>

					<p class="text-black text-sm ml-14 cursor-pointer hover:underline"
						@click="isRegister = false"
					>
						Already have account ? Login Here!
					</p>
				</div>
			</div>
		</div>


		<div style="width: 80%; height: 100%">
			<div class="grid grid-cols-5 gap-4 p-5 w-full" v-if="!selected">
				<div class="w-full --restaurant__list cursor-pointer" v-for="(arg, i) in restaurants" :key="i"
					@click="selectShop(arg)"
				>
					<div>
						<img :src="'/images/uploads/' + arg.image" 
							style="height: 220px; width: 100%"
							class="p-5"
						>
					</div>

					<div class="text-center font-bold text-xl">
						{{ arg.restaurant_name }}
					</div>
				</div>
			</div>

			<div class="w-full" v-else>
				<div class="w-full pt-8">
					<span class="ml-5 text-2xl font-semibold cursor-pointer" @click="selected = null">
						<i class="fa-solid fa-arrow-left mr-2"></i> Back
					</span>
				</div>

				<div class="w-full">
					<img :src="'/images/uploads/' + selected.banner" 
						style="height: 400px; width: 100%;"
						class="p-5"
					>

					<img :src="'/images/uploads/' + selected.image" class="relative"
						style="height: 180px; width: 20%; top: -10rem; left: 4rem; border: 1px solid #E6EE9C"
					>
				</div>
			</div>
			
		</div>

		

	</div>

</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import axios from "axios";

export default {
	props:['message', 'options'],
	data(){
		return {
			formloginData : {
				email: null,
				password: null 
			},
			formRegisterData: {
				name: '',
				phone: '',
				email: '',
				password: '',
				confirm_password: '',
				role: 3,
				user_type: 'customer',
				restaurant_name: '',
			},
			isRegister: false,
			saveError: null,
			formData: new FormData(),
			restaurants: [],
			selected: null
		}
	},

	watch: {
		isRegister: function(arg) {
			this.saveError = null
			this.message = null
		},

		'formRegisterData.role': function(arg) {
			if(arg == 2) {
				this.formRegisterData.user_type = 'owner'
			} else {
				this.formRegisterData.user_type = 'customer'
			}
		}
		
	},

	mounted(){
		this.restaurants = this.options.restaurants
	},

	methods: {
		login() {
			Inertia.post(this.$root.route + "/users/login", this.formloginData,
			{
				onSuccess: (res) => {
				},
				orError: (err) => {
				}
			});
		},

		disableButton(){
			if(!this.formloginData.email){
				return true;
			}

			if(!this.formloginData.password){
				return true;
			}

			return false;
		},

		imageChange(arg, e) {
	      	const image = e.target.files[0];

	      	// this.formData = new FormData()

	      	this.formData.append(arg, image);
		},

		register() {
			this.formData.append('name', this.formRegisterData.name);
			this.formData.append('phone', this.formRegisterData.phone);
			this.formData.append('email', this.formRegisterData.email);
			this.formData.append('password', this.formRegisterData.password);
			this.formData.append('confirm_password', this.formRegisterData.confirm_password);
			this.formData.append('role', this.formRegisterData.role);
			this.formData.append('user_type', this.formRegisterData.user_type);
			this.formData.append('restaurant_name', this.formRegisterData.restaurant_name);
			
			axios.post(this.$root.route + "/users/create-account", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						this.formRegisterData = {
							name: '',
							phone: '',
							email: '',
							password: '',
							confirm_password: '',
							role: 3,
							user_type: 'customer',
							restaurant_name: ''
						}

						alert("Account successfully created.");

						this.saveError = null

						this.isRegister = false

						this.formData = new FormData()
						
						location.reload()
					}
				})
		},
		selectShop(arg){
			this.selected = arg
		}
	}
}

</script>

<style scoped>
.--banner {
	background-image: url('/images/bg.jpg');
	background-repeat: no-repeat;
 	background-size: cover;
}

.--login__register {
	width: 380px;
	height: auto;
	background: #607EAA;
	border-radius: 10px;
	position: relative;
}

.--login__register--input {
	height: 40px;
	border-radius: 10px;
}

.--login__register--button {
	height: 40px;
	border-radius: 30px;
	background: #2C3333;
	color: white
}

.--restaurant__list{
	border: 1px solid #FFD600;
	border-radius: 5px;
	height: 250px;
}


</style>