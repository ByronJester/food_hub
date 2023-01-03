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
						</div>

						<div class="w-full mt-5">
							<b>TERMS and CONDITIONS</b><br><br>

							These Terms and Conditions (“Terms”) govern your use of the websites provided by Food Hub (or referred to as “us”) (collectively the “website”). Please read these Terms carefully. By accessing and using the website, you agree that you have read, understood, and accepted the Terms including any additional terms and conditions and any policies referenced herein, available on the website. If you do not agree to or fall within the Terms, please do not use the website.
							The website may be used by (i) natural persons who have (ii) corporate legal entities, e.g local food joints. Where applicable, these Terms shall be subject to country provisions as set out herein.
							Food Hub reserves the right to change or modify these Terms (including our policies which are incorporated into these Terms) at any time. You are strongly recommended to read these Terms regularly. You will be deemed to have agreed to the amended Terms by your continued use of the website following the date on which the amended Terms are posted.
						</div>
						
						<div class="w-full mt-2">
							<b>1. Food Hub</b><br><br>
							
							<span class="w-full">
								1.1 What we do
							</span>

							These Terms and Conditions (“Terms”) govern your use of the websites provided by Food Hub (or referred to as “us”) (collectively the “website”). Please read these Terms carefully. By accessing and using the website, you agree that you have read, understood, and accepted the Terms including any additional terms and conditions and any policies referenced herein, available on the website. If you do not agree to or fall within the Terms, please do not use the website.
							The website may be used by (i) natural persons who have (ii) corporate legal entities, e.g local food joints. Where applicable, these Terms shall be subject to country provisions as set out herein.
							Food Hub reserves the right to change or modify these Terms (including our policies which are incorporated into these Terms) at any time. You are strongly recommended to read these Terms regularly. You will be deemed to have agreed to the amended Terms by your continued use of the website following the date on which the amended Terms are posted.

							<br><br>
							<span class="w-full">
								1.2  How to contact us
							</span>

							These Terms and Conditions (“Terms”) govern your use of the websites provided by Food Hub (or referred to as “us”) (collectively the “website”). Please read these Terms carefully. By accessing and using the website, you agree that you have read, understood, and accepted the Terms including any additional terms and conditions and any policies referenced herein, available on the website. If you do not agree to or fall within the Terms, please do not use the website.
							The website may be used by (i) natural persons who have (ii) corporate legal entities, e.g local food joints. Where applicable, these Terms shall be subject to country provisions as set out herein.
							Food Hub reserves the right to change or modify these Terms (including our policies which are incorporated into these Terms) at any time. You are strongly recommended to read these Terms regularly. You will be deemed to have agreed to the amended Terms by your continued use of the website following the date on which the amended Terms are posted.
						
						</div>

						<div class="w-full mt-2">
							<b>2. Terms and Condition for Local Food Joints</b><br>
							<b>Use of the website and food hub Account (Owner and Customer)</b><br><br>


							
							<span class="w-full">
								2.1 You will need to register food hub (“User Account”) for you to use the Platform. When you register for a food hub Account, we will ask you to provide your personal information including your Full name, Email Address, Contact Number, Password, ID Picture, Restaurant Banner, and Restaurant Display Picture (hereinafter collectively referred as “User’s Details”). We reserve the right to request further information or documentation from you to comply with our legal obligations. Your unique password should not be shared with anyone, and you agree to keep it always secret. You are solely responsible for keeping your password safe. Save for cases of fraud or abuse which are not your fault, you accept that all Orders placed under your food hub Account are your sole responsibility.
							</span>

							<br><br>
							<span class="w-full">
								2.2 If you wish to delete your Account, please send us an email requesting the same. We may restrict, suspend, or terminate your Food Hub Account and/or use of the Platforms, if we reasonably believe that:
							</span>

							<br><br>
							<span class="w-full">
								2.3. Where you are suspected or discovered to have been involved in any activity or conduct that is in breach of these Terms, our policies, and guidelines, or involved in activity or conduct which we deem in our sole discretion to be an abuse of the Platforms.
							</span>
						</div>

						<div class="w-full mt-2">
							<b>3. Intellectual Property</b><br><br>
							
							<span class="w-full">
								All trademarks, logos, images, and service marks, including these Terms as displayed on the Platforms or in our marketing material, whether verified or non-verified, are the intellectual property of food hub and/or third parties who have authorized us to use their trademarks, logos, images, and service marks (collectively the “Trademarks”). You may not use, copy, reproduce, republish, upload, post, transmit, distribute, or modify these Trademarks in any way without our prior express written consent. The use of food hub’s trademarks on any other website not approved by us is strictly prohibited. Food hub will aggressively fully enforce its intellectual property rights of the law, including criminal prosecution. Food hub neither warrants nor represents that your use of materials displayed on the Platforms will not infringe rights of third parties not owned by or affiliated with food hub. Use of any materials on the Platforms is at your own risk.
							</span>
						</div>

						<div class="w-full mt-2">
							<b>4. Orders</b><br><br>

							
							<span class="w-full">
								4.1 When you place an Order with food hub, food joints will confirm your order containing the Order receipt. Where applicable, Orders will include delivery fees.
							</span>

							<br><br>
							<span class="w-full">
								4.2 Special Instructions – food hub and the food joints (as the case may be) reasonably endeavor to comply with your special instructions for an Order. However, in some cases where this is not feasible, possible, or commercially reasonable, food hub and/or the food joints reserve the right to proceed to prepare the Order in accordance with standard operating procedures.
							</span>

							<br><br>
							<span class="w-full">
								4.3 Allergens – food hub is not obligated to provide ingredient information or allergen information on the Platforms. Further, food hub does not guarantee that the Goods sold by food joints are free of allergens. If you have allergies, allergic reactions or dietary restrictions and requirements, please contact the food joints before placing an Order on our Platforms.
							</span>

							<br><br>
							<span class="w-full">
								4.4 Please note that your Order may be subject to additional terms and conditions provided by the food joints.
							</span>

							<br><br>
							<span class="w-full">
								4.5 Prior to placing the Order
							</span><br>

							To complete an Order, please follow the onscreen instructions after clicking ‘Checkout’. You may be required to provide additional details for us to complete your order. You are required to review and confirm that all the information you provide, including the amounts, delivery details, payment information is true, accurate and complete before you click “PLACE ORDER”. An Order is successfully placed when you see your orders is on process containing your receipt from us. You are required to provide the delivery address in profile the Platform to display the food joints available in your delivery area. Once you select a food joints, you will be taken to that food joint’s menu page for you to select and add your Goods to the tray.
							<br><br>

							<span class="w-full">
								4.6 Subscription Fees

							</span><br>

							The administrator’s profit will be automatically computed from a 3% subscription of the local food joint’s profit. Upon signing up, the entire plan value will be immediately charged to the profit of local food joints' accounts once they start having profits or transactions. The 3% subscription will be started or processed automatically when they register their account. This will be gathered first in a face-to-face meeting between the client and the system administrator to make sure the system they use can be trusted in their business.

							<br><br>


							<b class="w-full">
								No Cancelling an Order
							</b><br><br>

							The customers have no cancellation of orders once they have paid or place an order<br><br>

							<b>G-cash Payment Orders / Cash-on-Delivery Orders (“COD”)</b><br><br>

							Our food hub has no refund for your order if a food joint has not accepted your Order. Should you decide to order another product if your wants have not available you understand that no refunds will be issued to you, and you forfeit the delivery of your cancelled order.

							<br><br>
						
						</div>

						<div class="w-full mt-2">
							<b>5. Prices</b><br><br>

							
							<span class="w-full">
								5.1 Prices quoted on the Platform shall be displayed in the applicable country’s national currency. Prices and offers on the Platforms may vary from the prices and you accept the offers offered by our food joints.
							</span>

							<br><br>
							<span class="w-full">
								5.2 The way we display the prices of our goods may Cary depending on the food joint and the prices reflected on our platforms may:
							</span>

							<br><br>
							<span class="w-full">
								5.3 Delivery fees are fixed.
							</span>

							<br><br>
							<span class="w-full">
								5.4 You can only choose to pay an order using cash on delivery (COD) or G-cash payment methods offered on the platforms
							</span><br><br>
						
						</div>

						<div class="w-full mt-2">
							<b>6. Delivery Areas</b><br><br>

							
							<span class="w-full">
								You understand that our food joint offers their products in specific delivery areas and our food joints vary from delivery area to delivery area. By entering your address on the platforms, you will see the food joints that we make available to you at that time.
							</span>

						</div>

						<div class="w-full mt-2">
							<b>7. Food Joints Liability</b><br><br>

							
							<span class="w-full">
								Food joints are responsible for the preparation, condition, and quality of Goods. In cases of food joints Delivery, food joints are responsible for delivery of the Goods and/or Orders. But food hub is not responsible in deliver. Food hub shall not be liable for any loss or damage arising from your contractual relationship with the Vendor.
							</span>

						</div>

						<div class="w-full mt-2">
							<b>8. Personal Data (Personal Information) Protection</b><br><br>

							
							<span class="w-full">
								You agree and consent to food hub and any of its affiliate companies collecting, using, processing, and disclosing your Personal Data in accordance with these Terms and as further described in our Privacy Policy. 
							</span>

						</div>

						<div class="w-full mt-2">
							<b>9. Amendments</b><br><br>

							
							<span class="w-full">
								Food hub may amend these Terms at any time in its sole discretion. The amended Terms shall be effective immediately upon posting and you agree to the new Terms by continued use of the Platforms. It is your responsibility to check the Terms regularly. If you do not agree with the amended Terms, whether in whole or in part, you must stop using the Platforms immediately.
							</span>

						</div>

						<div class="w-full mt-2">
							<b>10. Deletion</b><br><br>

							
							<span class="w-full">
								Food hub has the right to suspend or delete your Food hub Account and access to the Platforms with immediate effect for any reason, including, without limitation, if Food hub, in its sole discretion, considers your use to be unacceptable, or in case of noncompliance or serious suspicion of non-compliance with the applicable laws, including, but not limited to, anti-money laundering and terrorist financing laws and regulations. Food hub may, but shall be under no obligation to, provide you a warning prior to termination of your use of the Platforms.
							</span>

						</div>

						<div class="w-full mt-2">
							<b>11. Severability</b><br><br>

							
							<span class="w-full">
								If any provision of these Terms is found to be invalid by any court having competent jurisdiction, the invalidity of such provision shall not affect the validity of the remaining provisions of these Terms which shall remain in full force and effect. No waiver of any provision in these Terms shall be deemed a further or continuing waiver of such provision or any other provision.
							</span>

						</div>

						<div class="w-full mt-2">
							<b>11 Prevailing Language</b><br><br>

							
							<span class="w-full">
								In the event of a dispute as to the Terms, the English version shall prevail. The English language version of these Terms shall control in all respects and shall prevail in case of any inconsistencies with translated versions.
							</span>

						</div>	

						<div class="w-full mt-2">
							<b>Privacy Policy</b><br><br>

							<b>1. Who we are</b><br><br>
							<span class="w-full">
								We are the developers of the food hub for the local food joints.

								As regards the processing activities conducted on our platform, we will be the data controller responsible for what happens with your personal data. “Data Controller” is a legal term and simply means that we are the party determining how your personal data is processed, for what purposes this is done and by what means. While we are required by law to provide you with all the following information, we do so also out of the belief that a partnership should always be honest.

								While using our platform, registering, or placing orders you agree to this privacy policy. This privacy policy applies to all personal data obtained by us through your use of our platform. The relevant privacy policies set out in the respective sites shall apply in those cases.
							</span><br><br>

							<b>2. Privacy is your right, and the choice is yours</b><br><br>
							<span class="w-full">
								As a customer you have the choice which information you would like to share with us. Please be aware, however, that when signing up to our platform, you are required to accept our terms of use the platform. Legally speaking this means you will enter a contract with us under which you are entitled to use the platform, in accordance with terms of use. Of course, we need some information from you to be able to perform our obligations under this contract. However, it is entirely up to you to choose whether you would like to provide such information or would rather not use our platform.

								Cookies & web-tracking: You can set your device or web browser to decline cookies and other web-tracking technologies. If you deactivate web-tracking you will no longer see any personalized contents, offers or ads.
							</span><br><br>

							<b>3. Your Legal Rights</b><br><br>
							<b>Right to access</b><br><br>
							<span class="w-full">
								You have the right to be informed which data we store about you and how we process this data.
							</span><br><br>

							<b>Right to rectification</b><br><br>
							<span class="w-full">
								If you notice that stored data is incorrect, you can always ask us to correct it.
							</span><br><br>

							<b>Right to erasure </b><br><br>
							<span class="w-full">
								You can ask at any time to delete the data we have stored about you.
							</span><br><br>

							<b>Right to data portability</b><br><br>
							<span class="w-full">
								You can ask us to transmit the data stored about you in a machine-readable format to you or to another responsible person.
							</span><br><br>

							<b>4. An overview of the personal data we process</b><br><br>

							In this section you can find general information about the categories of personal data we process about you. For your understanding, personal data is information that directly identifies you (such as your name or photo picture) or enables us to indirectly identify you (for example, since a user ID linked with the personal information in your profile).
							<br><br>

							A. Profile data (master data) <br><br>
							This includes your full name, email address, phone number, address, age.<br>
							Why do we process this category?<br>

							This data is your personal data, which we absolutely need for our services. Without an email address / contact number, Valid ID and a password, you cannot create a profile together with your full name, this is your personal data. We need you to ensure that you are legitimate customers.
							<br><br>

							B. Customer care data<br><br>
							This Includes your full name, address, contact number, email address and your Valid ID.<br>
							Why do we process this category?<br>
							If you contact us, we collect this data because we need to know who we are talking to and what we have been talking about so that we can help you with your reason for contacting us. <br>
							<br><br>
							<b>5. We process your personal data only in accordance with relevant data protection laws. </b><br><br>

							We pay particular attention to the fact that all principles for the processing of personal data are considered. Therefore, we only process your data if this lawful and you reasonably expect it to be processed.
							<br>
							The processing of your personal data is essential. You do provide us with some of this data proactively y entering them on your device. Other data we collect automatically when you are using our platforms.
							<br>

							We process your personal data for the following purposes(“Purpose”):
							<br><br>

							I.Creating and operating your account: delivering your orders<br><br>
							When creating a customer, you will be asked to enter your data. This is necessary, as we cannot create a customer profile without this data. Your email address and contact number are particularly important, as we can use this information to identify you in our system the next time you want to login again. Furthermore, we would like to ask you to choose your password carefully.
							<br><br>

							Login to an existing account<br><br>
							If you already have an existing customer account, you will need to enter your email address and password to log in.<br><br>


							<b>6. Order Processing. </b><br><br>
							You can log in to your profile at any time and change your personal data, such as name, email address or contact number. You can also view your previous orders.<br><br>

							<b>7. Managing your profile. </b><br><br>
							Once you have successfully verified and decided to place your order, we will store this information in your profile and process it in further processes so that you can submit your order to us. When you submit your order, your personal data is transferred to our backend where it is transferred to other window for further processing.<br><br>
							

							<b>8. Delivering your order </b><br><br>
							Once you have successfully placed your order, several processes are running in the background to ensure that your order is delivered quickly. This includes sharing your order data with the restaurant preparing your meal as well as with the rider delivering your order.<br><br>


							<b>9. Customer Relationship Management </b><br><br>
							Your satisfaction is our biggest goal. Therefore, we are very keen to be available for all your questions and to answer them. To be able to answer these questions and understand the overall problem, we store the conversation content in our Customer Relationship Management System when you contact us.<br><br>

							<b>10. Who we share your personal data with </b><br><br>
							We never give your data to unauthorized third parties. However, to run our business efficiently, we obtain the services of selected service providers and give them limited and strictly monitored access to some of our data, to fulfil the Purposes. Before we forward personal data to the local food joint for processing on our behalf, all data recipients must meet the legal data protection requirements and undertake to protect your personal data to a comparable standard as required under the DPA, the DPA IRR and other relevant data protection laws.<br>

							<br>
							<b>11. Right of modification </b><br><br>
							We reserve the right to change this privacy notice to ensure compliance with relevant legal and statutory provisions, including the DPA and the DPA IRR. We will inform you of any significant changes, such as changes of purpose or new purposes of processing. The updated version will be posted on our website it is your responsibility to review the Privacy Policy regularly. Your continued use of the website to purchase products from us or engage with food hub following the modifications, updates, or amendments to this Policy, whether or not reviewed by you, shall constitute your agreement to be bound by such amendments.<br><br>



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