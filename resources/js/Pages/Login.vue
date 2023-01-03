<template>
	<div class="w-screen h-screen flex flex-row">
		<div style="background: #000000; height: 100%; "
			class="flex flex-col justify-center items-center fixed overflow-y-scroll --scrollable"
			:style="{'width': isMobile ? '100%': '20%'}"
			v-if="isLogin"
		>	
			<div class="w-full mb-5 relative" :style="{'top': isRegister ? '0' : '-5rem'}" v-if="!isRegister">
				<img class="w-full px-20" style="height: 150px" src="/images/logo2.jpg" />
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
						placeholder="Username" v-model="formloginData.username"
						@keyup.enter="login()"
					>

					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						id="loginPassword"
						placeholder="Password" v-model="formloginData.password"
						@keyup.enter="login()"
					>

					<input type="checkbox" @click="showLoginPassword()"><span class="text-white"> Show Password </span>

					<span class="text-red-500 text-xs ml-2" v-if="message && message != 'success'">
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

			<div class="mx-2 relative mb-5 mt-16" style="border: 1px solid #FFFFFF; border-radius: 5px;" v-else>
				<div class="w-full p-2 text-white">
					<span class="float-right cursor-pointer" @click="isLogin = false">
						<i class="fa-solid fa-xmark"></i>
					</span>
				</div>

				<div class="px-2 py-2">
					<select v-model="formRegisterData.role" class="w-full text-center my-2" style="height: 40px; border-radius: 10px">
						<option value="3">Customer</option>
						<option value="2">Local Food Joints</option>
					</select>

					<input type="text" class="w-full mt-2 --login__register--input text-center" style="text-transform: capitalize;"
						:class="{'mb-2' : !message}"
						placeholder="Food Joint Name" v-model="formRegisterData.restaurant_name"
						v-if="formRegisterData.role == 2"
					>
					<span class="text-xs text-red-500">{{validationError('restaurant_name', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center" style="text-transform: capitalize;"
						:placeholder="formRegisterData.role == 2 ? 'Owner Name' : 'Name'" v-model="formRegisterData.name"
					>
					<span class="text-xs text-red-500">{{validationError('name', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Username" v-model="formRegisterData.username"
					>
					<span class="text-xs text-red-500">{{validationError('username', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center"
						placeholder="Contact" v-model="formRegisterData.phone"
					>
					<span class="text-xs text-red-500">{{validationError('phone', saveError)}} </span>

					<input type="text" class="w-full  my-2 --login__register--input text-center" style="text-transform: capitalize;"
						placeholder="Street, Barangay, Town" v-model="formRegisterData.address"
					>
					<span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
			
					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						id="registerPassword"
						placeholder="Password" v-model="formRegisterData.password"
					>
					<span class="text-xs text-red-500">{{validationError('password', saveError)}} </span>

					<input type="password" class="w-full mt-2 --login__register--input text-center"
						:class="{'mb-2' : !message}"
						id="registerConfirmPassword"
						placeholder="Confirm Password" v-model="formRegisterData.confirm_password"
					>
					<span class="text-xs text-red-500">{{validationError('confirm_password', saveError)}} </span><br>

					<input type="checkbox" @click="showRegisterPassword()"><span class="text-white"> Show Password </span>

					<div class="text-white">
						<label class="mt-1 font-bold">ID Picture</label><br>
						<input type="file" class="w-full" accept="image/png, image/jpeg" @change="imageChange('picture_id', $event)" multiple><br>
						<span class="text-xs text-white">Note: Please upload front and a selfie holding ID picture.  </span><br>
						<span class="text-xs text-red-500">{{validationError('picture_id', saveError)}} </span>
					</div>
					

					<div class="w-full text-red-600" v-if="ids.length > 0">
						<span class="float-right mr-2 cursor-pointer" @click="removeIds()">
							<i class="fa-solid fa-trash"></i>
						</span>
					</div>

					<div class="w-full flex flex-row mt-2" v-if="ids.length > 0">
						<div class="w-full flex justify-center items-center px-2" v-for="i in ids" :key="i">
							<img :src="i.image" style="width: 100%; height: 100px"/>
						</div>
					</div>

					<div v-if="formRegisterData.role == 2" class="text-white">
						<label class="mt-1 font-bold">Local Food Joint Display Picture</label><br>
						<input type="file" class="w-full" accept="image/png, image/jpeg" @change="imageChange('image', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('image', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2" class="text-white">
						<label class="mt-1 font-bold">Local Food Joint Banner</label><br>
						<input type="file" class="w-full" accept="image/png, image/jpeg" @change="imageChange('banner', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('banner', saveError)}} </span>
					</div>

					<div v-if="formRegisterData.role == 2" class="text-white">
						<label class="mt-1 font-bold">Local Food Joint Business Permit</label><br>
						<input type="file" class="w-full" accept="image/png, image/jpeg" @change="imageChange('permit', $event)"><br>
						<span class="text-xs text-red-500">{{validationError('permit', saveError)}} </span><br>
					</div>

					
  					<input type="checkbox" v-model="termsAndCondition">
					<label class="text-white cursor-pointer hover:underline"
						@click="openTermsAndCondition(); isMobile ? isLogin = false : ''"
					> 
						Terms and Condition 
					</label><br>

					<button class="w-full  my-2 --login__register--button text-center"
						@click="sendRegisterVerification();"
						:disabled="!termsAndCondition"

						:class="{ 'cursor-not-allowed' : !termsAndCondition }"
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
			:style="{width: isLogin ? '80%' : '100%', 'left': isLogin ? '20%': '0', 'display': isMobile && isLogin ? 'none' : 'block'}"
			class="absolute"
		>
			<div class="w-full" style="height: 50px" :style="{'background': !isLogin ? '#000000' : '#FFFFFF'}">
				<p class="float-right cursor-pointer mt-3 mr-3" v-if="!isLogin" @click="isLogin = true">
					<span class="text-lg"  style="color: #E4B934">
						<i class="fa-solid fa-door-open mr-1"></i> <b>LOGIN</b> 
					</span>
				</p>
			</div>

			<div class="w-full pt-10 px-5 flex justify-center items-center">
				<carousel :navigationEnabled="false" :perPage="1" :paginationEnabled="false" :autoplay="true" :loop="true" :class="{'w-6/12' : !isMobile, 'w-full' : isMobile}">
					<slide v-for="banner in banners" :key="banner" class="w-full" style="border: 1px solid #E4B934">
						<div class="w-full">
							<img
								:src="'/images/uploads/' +banner"
								class="w-full"
								:style="{ 'height': '250px' }"
								alt=""
							/>
						</div>
					</slide>
				</carousel>
			</div>

			<div class="p-5 w-full" v-if="!restaurant"
				:class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}"
			>
				<div class="w-full --restaurant__list cursor-pointer" v-for="(arg, i) in restaurants" :key="i"
					@click="selectShop(arg)"
				>
					<div>
						<img :src="'/images/uploads/' + arg.image" 
							style="height: 220px; width: 100%"
							class="p-5"
						>
					</div>

					<div class="text-center font-bold" :class="{'--text': !isMobile, 'text-lg': isMobile}" style="text-transform: capitalize;">
						{{ arg.restaurant_name }}
					</div>

					<div class="text-center font-bold text-md">
						{{ arg.opening }} - {{ arg.closing }}
					</div>
				</div>
			</div>

			<div class="w-full" v-else>
				<div class="w-full text-2xl font-bold px-5 pt-5">
					<span class="cursor-pointer" @click="restaurant = null" :class="{'--text': !isMobile, 'text-lg': isMobile}">
						<i class="fa-solid fa-arrow-left"></i> Back
					</span>

                    <span class="float-right" :class="{'--text': !isMobile, 'text-lg': isMobile}" style="text-transform: capitalize;">
						{{ restaurant.restaurant_name }} ({{ restaurant.address }})
					</span>
				</div>

				<div class="w-full relative">
					<div class="w-full">
						<img :src="'/images/uploads/' + restaurant.banner" 
							style="width: 100%;"
							class="p-5 relative"
							:style="{'height' : isMobile ? '250px' : '400px'}"
						>

						<img :src="'/images/uploads/' + restaurant.image" class="absolute"
							style="top: 17rem; left: 4rem; border: 1px solid #E4B934"
							:style="{'width': isMobile ? '40%': '20%', 'height' : isMobile ? '130px' : '180px', 'top' : isMobile ? '10rem': '17rem'}"
						>
					</div>

					<div class="w-full mt-5 md:px-5">
						<div class="flex flex-row float-right mr-8" style="width: 30%">
							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Food', '--text': !isMobile, 'text-lg': isMobile}"
								@click="activeCategory = 'Food'"
							>
								Foods
							</div>

							<div class="w-full cursor-pointer text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Drink', '--text': !isMobile, 'text-lg': isMobile}"
								@click="activeCategory = 'Drink'"
							>
								Drinks
							</div>
						</div>
					</div>
				</div>

				<div class="w-full my-20 px-5">
					<div class="flex justify-center items-center"
						:class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}"
					>
						<div class="w-full flex flex-col" v-for="(product) in restaurant.products.filter( x => { return x.category == activeCategory})" :key="product.id"
							style="border: 1px solid #E4B934"
							:class="{'mb-2': isMobile}"
						>
							<div class="w-full inline-flex mt-1" :style="{cursor: product.description ? 'pointer' : 'not-allowed'}">
								<p @click="product.description ? openDescriptionModal(product) : ''">
									<i class="fa-solid fa-eye fa-lg p-1"></i>
								</p>
							</div>

							<div class="w-full">
								<img class="w-full p-4" :src="'/images/uploads/' + product.image"
									style="height: 200px"
								/>
							</div>

							<div class="w-full text-center flex flex-col mb-2">
                                <div class="px-4" style="width: 100%">
                                    <button class="w-full py-1 cursor-default"
                                        style="border-radius: 5px; background: #000000"
                                    >
                                        <span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}"> 
                                            <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b>
                                        </span>
                                    </button>

									<button class="w-full py-1 cursor-default mt-2"
                                        style="border-radius: 5px; border: 1px solid black"
                                    >
                                        <span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}"> 
                                            <b style="background: #FFFFFF; border-radius: 5px" class="px-1 text-black">₱{{ parseFloat(product.amount).toFixed(2) }}</b>
                                        </span>
                                    </button>
                                </div>

							</div>
						</div> 
					</div>
            	</div>

				<div id="descriptionModal" class="descriptionModal">
					<div class="description-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width': isMobile ? '90%': '35%'}"> 
						<div class="w-full">
							<span class="text-4xl font-bold">
								{{productName}}
							</span>

							<span class="float-right cursor-pointer"
								@click="closeDescriptionModal()"
							>
								<i class="fa-solid fa-xmark"></i>
							</span>
						</div>

						<div class="w-full flex flex-col mt-4">
							<div class="w-full flex flex-col justify-center items-center">
								<div class="w-full">
									<img class="w-full p-4" :src="productImage"
										style="height: 300px; border: 2px solid #E4B934"
									/>
								</div>

								<div class="w-full">
									<p class="w-full text-center text-4xl">
										₱ {{ parseFloat(productAmount).toFixed(2) }}
									</p>
								</div>
							
								
							</div>

							<div class="w-full mt-5 text-2xl">
								<p>
									{{ description }}
								</p>
							</div>
						</div>
					</div>
				</div>
				
			</div>

			<div id="registerModal" class="registerModal">
				<!-- Modal content -->
				<div class="register-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full">
						<span class="text-lg font-bold">
							Verification Code
						</span>

						<!-- <span class="float-right cursor-pointer"
							@click="closeRegisterModal()"
						>
							<i class="fa-solid fa-xmark"></i>
						</span> -->
					</div>

					<div class="w-full flex flex-col mt-4">
						<div class="w-full">
							<input type="text" class="w-full  my-2 --login__register--input text-center" style="border: 1px solid black"
								placeholder="Verification Code" v-model="formRegisterData.code"
							>
							<span class="text-xs text-red-500">{{validationError('code', saveError)}} </span><br>
						</div>
					</div>

					<div class="w-full flex flex-col mt-4">
						<button class="w-full --login__register--button" @click="otherRegister()">
							Proceed
						</button>
					</div>
				</div>
			</div>

			<div id="loginModal" class="loginModal">
				<!-- Modal content -->
				<div class="login-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full">
						<span class="text-lg font-bold">
							Verification Code
						</span>

						<!-- <span class="float-right cursor-pointer"
							@click="closeLoginModal()"
						>
							<i class="fa-solid fa-xmark"></i>
						</span> -->
					</div>

					<div class="w-full flex flex-col mt-4">
						<div class="w-full">
							<input type="text" class="w-full  my-2 --login__register--input text-center" style="border: 1px solid black"
								placeholder="Verification Code" v-model="formloginData.code"
							>
							<span class="text-xs text-red-500">{{validationError('code', saveError)}} </span><br>
						</div>
					</div>

					<div class="w-full flex flex-col mt-4">
						<button class="w-full --login__register--button" @click="otherLogin()">
							Proceed
						</button>
					</div>
				</div>
			</div>

			<div id="termsModal" class="termsModal overflow-y-scroll --scrollable">
				<!-- Modal content -->
				<div class="terms-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '90%' : '50%'}">
					<div class="w-full flex flex-col mt-4 text-sm">
						<div class="w-full">
							<span class="float-right cursor-pointer"
								@click="closeTermsAndCondition()"
							>
								<i class="fa-solid fa-xmark"></i>
							</span>
						</div>

						<div class="w-full mt-10">
							<iframe :src="'/images/' + 'tac.pdf'" style="width: 100%; height: 700px"></iframe>
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
	props:['options'],
	data(){
		return {
			formloginData : {
				username: null,
				password: null,
				code: null
			},
			formRegisterData: {
				name: '',
				phone: '',
				username: '',
				password: '',
				confirm_password: '',
				role: 3,
				user_type: 'customer',
				restaurant_name: '',
				address: '',
				code: ''
			},
			isRegister: false,
			saveError: null,
			formData: new FormData(),
			restaurants: [],
			restaurant: null,
			isLogin: false,
			activeCategory: 'Food',
			banners: [
				'foodhub.png'
			],
			description: null,
            productName: null,
			isMobile: window.screen.width <= 700,
			ids: [],
			isDone: false,
			message: null,
			termsAndCondition: false
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
		},
		
	},

	mounted(){
		this.restaurants = this.options.restaurants

		if(this.isMobile) {
			this.isLogin = false
		}

		this.banners = this.banners.concat(this.options.banners)

		console.log(this.banners)
	},

	methods: {
		showLoginPassword(){
			var x = document.getElementById("loginPassword");

			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		},
		showRegisterPassword(){
			var x = document.getElementById("registerPassword");
			var y = document.getElementById("registerConfirmPassword");

			if (x.type === "password") {
				x.type = "text";
				y.type = "text";
			} else {
				x.type = "password";
				y.type = "password";
			}
		},
		login() {
			// Inertia.post(this.$root.route + "/users/login", this.formloginData,
			// {
			// 	onSuccess: (res) => {
			// 		if(res.props.message == 'success') {
			// 			this.openLoginModal()
			// 		}
			// 	},
			// 	orError: (err) => {
			// 	}
			// });

			axios.post(this.$root.route + "/users/login", this.formloginData)
				.then(response => {

					if(response.data.status == 422) {
						// this.saveError = response.data.errors
						this.message = response.data.message
					} else {
						location.reload()
						// this.openLoginModal()
					}
				})
		},
		
		otherLogin() {
			axios.post(this.$root.route + "/users/verify-otp", this.formloginData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors
					} else {
						location.reload()
					}
				})
		},

		disableButton(){
			if(!this.formloginData.username){
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
			if(arg == 'picture_id') {
				this.formData.append('picture_id[]', image); 

				const reader = new FileReader();

				reader.readAsDataURL(image);

				reader.onload = e =>{
					this.ids.push({image: e.target.result})
				}
			} else {
				this.formData.append(arg, image);
			}
	      	
		},

		register() {
			this.formData.append('name', this.formRegisterData.name);
			this.formData.append('phone', this.formRegisterData.phone);
			this.formData.append('username', this.formRegisterData.username);
			this.formData.append('password', this.formRegisterData.password);
			this.formData.append('confirm_password', this.formRegisterData.confirm_password);
			this.formData.append('role', this.formRegisterData.role);
			this.formData.append('user_type', this.formRegisterData.user_type);
			this.formData.append('restaurant_name', this.formRegisterData.restaurant_name);
			this.formData.append('address', this.formRegisterData.address);
			
			axios.post(this.$root.route + "/users/create-account", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors
					} else {
						this.saveError = null
					}

					if(this.saveError == null) {
						axios.post(this.$root.route + "/users/save-verification", {phone: this.formRegisterData.phone })
							.then(response => {
								if(response.data.status == 422) {
									
								} else {
									this.isDone = true
									this.formData.append('isDone', this.isDone);
									this.openRegisterModal()
								}
							})
					}
				})
		},

		otherRegister() {
			this.formData.append('name', this.formRegisterData.name);
			this.formData.append('phone', this.formRegisterData.phone);
			this.formData.append('username', this.formRegisterData.username);
			this.formData.append('password', this.formRegisterData.password);
			this.formData.append('confirm_password', this.formRegisterData.confirm_password);
			this.formData.append('role', this.formRegisterData.role);
			this.formData.append('user_type', this.formRegisterData.user_type);
			this.formData.append('restaurant_name', this.formRegisterData.restaurant_name);
			this.formData.append('address', this.formRegisterData.address);
			this.formData.append('isDone', this.isDone);
			this.formData.append('code', this.formRegisterData.code);
			
			axios.post(this.$root.route + "/users/create-account", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors
					} else {
						this.saveError = null

						if(this.isDone) {
							this.formRegisterData = {
								name: '',
								phone: '',
								username: '',
								password: '',
								confirm_password: '',
								role: 3,
								user_type: 'customer',
								restaurant_name: '',
								address: '',
								code: ''
							}

							alert("Account successfully created.");

							this.isRegister = false

							this.formData = new FormData()
							
							location.reload()
						}
					}
				})
		},
		sendRegisterVerification(){
			this.register()
		},

		selectShop(arg){
			this.restaurant = arg
		},

		openDescriptionModal(arg){
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "block";

            this.description = arg.description
            this.productName = arg.name

			this.description = arg.description
            this.productName = arg.name
            this.productImage = '/images/uploads/' + arg.image
            this.productAmount = arg.amount
        },

        closeDescriptionModal(){
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "none";

            this.description = null
            this.productName = null
        },

		openRegisterModal(){
            var modal = document.getElementById("registerModal");

            modal.style.display = "block";

			this.isLogin = false
        },

        closeRegisterModal(){
            var modal = document.getElementById("registerModal");

            modal.style.display = "none";
        },

		openLoginModal(){
            var modal = document.getElementById("loginModal");

            modal.style.display = "block";
        },

        closeLoginModal(){
            var modal = document.getElementById("loginModal");

            modal.style.display = "none";
        },

		openTermsAndCondition() {
			var modal = document.getElementById("termsModal");

            modal.style.display = "block";
		},

		closeTermsAndCondition() {
			var modal = document.getElementById("termsModal");

            modal.style.display = "none";

			this.isLogin = true
		},

		removeIds() {
			this.ids = [];
			this.formData.delete('picture_id[]');
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
	height: 100%;
}

.--text {
	font-size: calc(.1em + 1vw);
}

.descriptionModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 10%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.description-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.registerModal {
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
.register-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.loginModal {
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
.login-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.termsModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 10%;
  width: 100%; /* Full width */
  height: 70vh; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.terms-content {
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

.--scrollable {
	overflow-x:hidden;
	overflow-y:scroll;
}

.--scrollable::-webkit-scrollbar {
	width: 0px;
	background: white;
}

.--scrollable::-webkit-scrollbar-thumb {
	background: #ffffff;
	height:30px;
}

.--scrollable::-webkit-scrollbar-track-piece{
	display:none;
}


</style>