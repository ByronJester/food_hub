<template>
	<div class="w-screen h-screen flex flex-row">
		<div style="background: #000000; width: 20%; height: 100%"
			class="flex flex-col justify-center items-center"
			v-if="isLogin"
		>	
			<div class="w-full mb-5 relative" style="top: -5rem">
				<img class="w-full px-20" style="height: 150px" src="/images/logo2.png" />
			</div>
			
			<div v-if="!isRegister"
				class="mx-2 relative" style="border: 1px solid #FFFFFF; border-radius: 5px; top: -4rem"
			>	
				<div class="w-full p-2 text-white">
					<span class="float-right cursor-pointer" @click="isLogin = false">
						<i class="fa-solid fa-xmark"></i>
					</span>
				</div>

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

					<p class="text-white text-sm w-full text-center cursor-pointer hover:underline"
						@click="isRegister = true"
					>
						Doesn't have accout ? Sign Up Here!
					</p>
				</div>
			</div>

			<div class="mx-2 relative" style="border: 1px solid #FFFFFF; border-radius: 5px; top: -3rem" v-else>
				<div class="w-full p-2 text-white">
					<span class="float-right cursor-pointer" @click="isLogin = false">
						<i class="fa-solid fa-xmark"></i>
					</span>
				</div>

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

					<div class="text-white">
						<label class="mt-1 font-bold">ID Picture</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('picture_id', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('picture_id', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2" class="text-white">
						<label class="mt-1 font-bold">Restaurant Display Picture</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('image', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('image', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2" class="text-white">
						<label class="mt-1 font-bold">Restaurant Banner</label><br>
						<input type="file" accept="image/png, image/jpeg" @change="imageChange('banner', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('banner', saveError)}} </span><br>
					</div>

					<button class="w-full  my-2 --login__register--button text-center"
						@click="register()"
					>
						Register
					</button>

					<p class="text-white text-sm w-full text-center cursor-pointer hover:underline"
						@click="isRegister = false"
					>
						Already have account ? Login Here!
					</p>
				</div>
			</div>
		</div>


		<div style="height: 100%"
			:style="{width: isLogin ? '80%' : '100%'}"
			class=""
		>
			<div class="w-full" style="height: 50px" :style="{'background': !isLogin ? '#000000' : '#FFFFFF'}">
				<p class="float-right cursor-pointer mt-3 mr-3" v-if="!isLogin" @click="isLogin = true">
					<span class="text-lg">
						<b class="text-white">LO</b><b style="background: #E4B934; border-radius: 5px" class="px-1">GIN</b>
					</span>
				</p>
			</div>

			<div class="grid grid-cols-5 gap-4 p-5 w-full" v-if="!restaurant">
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
				<div class="w-full text-2xl font-bold px-5 pt-5">
					<span class="cursor-pointer" @click="restaurant = null">
						<i class="fa-solid fa-arrow-left"></i> Back
					</span>

                    <span class="cursor-pointer float-right" @click="restaurant = null">
						{{ restaurant.restaurant_name }}
					</span>
				</div>

				<div class="w-full relative">
					<div class="w-full">
						<img :src="'/images/uploads/' + restaurant.banner" 
							style="height: 400px; width: 100%;"
							class="p-5 relative"
						>

						<img :src="'/images/uploads/' + restaurant.image" class="absolute"
							style="height: 180px; width: 20%; top: 17rem; left: 4rem; border: 1px solid #E4B934"
						>
					</div>

					<div class="w-full mt-5 px-5">
						<div class="flex flex-row float-right" style="width: 30%">
							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-gray-500': activeCategory == 'Food'}"
								@click="activeCategory = 'Food'"
							>
								Foods
							</div>

							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-gray-500': activeCategory == 'Drink'}"
								@click="activeCategory = 'Drink'"
							>
								Drinks
							</div>
						</div>
					</div>
				</div>

				<div class="w-full mt-20 px-5">
					<div class="grid grid-cols-5 gap-4 flex justify-center items-center">
						<div class="w-full flex flex-col" v-for="product in restaurant.products.filter( x => { return x.category == activeCategory})" :key="product.id"
							style="border: 1px solid #E4B934"
						>
							<div class="w-full">
								<img class="w-full p-4" :src="'/images/uploads/' + product.image"
									style="height: 200px"
								/>
							</div>

							<div class="w-full text-center">
								<span class="text-lg font-bold">
									{{ product.name }} - ₱{{ product.amount.toFixed(2) }}
								</span>
							</div>
						</div>
					</div>
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
			restaurant: null,
			isLogin: false,
			activeCategory: 'Food',
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
			this.restaurant = arg
		}
	}
}

</script>

<style scoped>
.--main {
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
	background: #E4B934;
	color: #000000;
}

.--restaurant__list{
	border: 1px solid #E4B934;
	border-radius: 5px;
	height: 250px;
}


</style>