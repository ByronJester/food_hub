<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col overflow-x-hidden --main--div">
            <div class="w-full relative">
				<div class="w-full">
					<!-- <img :src="restaurant.banner" 
						style="height: 400px; width: 100%; "
						class="p-5 relative cursor-pointer"
                        @click="uploadImage('banner')"
					>

					<img :src="restaurant.image" class="absolute cursor-pointer"
						style="height: 180px; width: 20%; top: 17rem; left: 4rem; border: 1px solid #E4B934"
                        @click="uploadImage('image')" 
					> -->

                    <div class="w-full">
						<img :src="restaurant.banner" 
							style="width: 100%;"
							class="p-5 relative"
							:style="{'height' : isMobile ? '250px' : '400px'}"
                            @click="uploadImage('image')" 
						>

						<img :src="restaurant.image" class="absolute"
							style="top: 17rem; left: 5%; border: 1px solid #E4B934"
							:style="{'width': isMobile ? '40%': '20%', 'height' : isMobile ? '130px' : '180px', 'top' : isMobile ? '10rem': '17rem'}"
                            @click="uploadImage('image')" 
						>
					</div>

                    <input type="file" ref="image" @change="imageChangeRestaurant('image', $event)" accept="image/png, image/jpeg" style="display:none">
                    <input type="file" ref="banner" @change="imageChangeRestaurant('banner', $event)" accept="image/png, image/jpeg" style="display:none">
				</div>

                <div class="w-full mt-20 md:mt-5 md:px-5" v-if="activeTab == 'menus'">
                    <div class="w-full mt-5 px-5">
						<div class="flex flex-row md:float-right" style="width: 30%">
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

                            <div class="cursor-pointer mx-2 text-center"
                                style="border: 1px solid #E4B934; width: 100%"
                                :class="{'px-2': isMobile}"
                                @click="openModal()"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </div>
						</div>
					</div>
                </div>
			</div>

            <div class="w-full inline-flex ml-5 mt-10" v-if="!isMobile">
                <div class="pr-2">
                    <label class="font-bold">Opening Time:</label><br><br>

                    <input type="time" required style="border: 1px solid #E4B934" class="p-2" v-model="formTime.opening_time">
                </div>

                <div class="pr-3">
                    <label class="font-bold">Closing Time:</label><br><br>

                    <input type="time" required style="border: 1px solid #E4B934" class="p-2" v-model="formTime.closing_time">
                </div>

                <div>
                    <button style="background: #E4B934; width: 150px" class="text-center pt-3 pb-2 mt-12"
                        @click="confirmTime()"
                    >
                        SAVE
                    </button>
                </div>
                
            </div>

            <div class="w-full flex flex-col md:ml-5 mt-10" v-else>

                <div class="flex flex-row items-center justify-center">
                    <div class="pr-2">
                        <label class="font-bold">Opening Time:</label><br><br>

                        <input type="time" required style="border: 1px solid #E4B934" class="p-2" v-model="formTime.opening_time">
                    </div>

                    <div class="md:pr-3">
                        <label class="font-bold">Closing Time:</label><br><br>

                        <input type="time" required style="border: 1px solid #E4B934" class="p-2" v-model="formTime.closing_time">
                    </div>
                </div>
                

                <div class="flex items-center justify-center">
                    <button style="background: #E4B934; width: 150px" class="text-center pt-3 pb-2 mt-12"
                        @click="confirmTime()"
                    >
                        SAVE
                    </button>
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
                            <p @click="viewProduct(product)">
                                <i class="fa-solid fa-pen-to-square fa-lg mx-1 cursor-pointer p-1"></i>
                            </p>
                            
                            <p @click="openDescriptionModal(product)" v-if="product.description">
                                <i class="fa-solid fa-eye fa-lg cursor-pointer p-1"></i>
                            </p>

                            <Toggle :value="product.is_active" :url="'/restaurants/product/deactivate-reactivate'" :id="product.id" class="ml-1 mt-1"/>
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

            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content flex flex-col" :style="{'width': isMobile ? '90%': '20%'}">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                            {{activeCategory}}
                        </span>
                        <span class="float-right cursor-pointer"
                            @click="closeModal()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full mt-4">
                        <input type="text" class="w-full text-center" placeholder="Name" v-model="form.name"
                            style="border: 1px solid black; border-radius: 5px; height: 40px"
                        >
                        <span class="text-xs text-red-500">{{validationError('name', saveError)}} </span>
                    </div>

                    <div class="w-full mt-4">
                        <textarea placeholder="Description" rows="5" cols="50" class="w-full"
                            style="border: 1px solid black; padding: 5px; border-radius: 5px"
                            v-model="form.description"
                        ></textarea>
                        <span class="text-xs text-red-500">{{validationError('description', saveError)}} </span>
                    </div>

                    <div class="w-full mt-4">
                        <input type="text" inputmode="decimal" class="w-full text-right pr-2" placeholder="Amount" v-model="form.amount"
                            style="border: 1px solid black; border-radius: 5px;  height: 40px" @click="form.amount = '0.00'"
                        >
                        <span class="text-xs text-red-500">{{validationError('amount', saveError)}} </span>
                    </div>

                    <div class="w-full mt-4">
                        <input type="file" class="w-full text-center" @change="imageChange('image', $event)">
                        <span class="text-xs text-red-500">{{validationError('image', saveError)}} </span>
                    </div>

                    <div class="w-full mt-4">
                        <button class="w-full py-2"
                            style="border-radius: 5px; background: #E4B934"
                            @click="confirmProduct()"
                        >
                            SUBMIT
                        </button>
                    </div>
                </div>
            </div>

            <div id="addressModal" class="addressModal">
                <!-- Modal content -->
                <div class="address-content flex flex-col" :style="{'width': isMobile ? '90%': '20%'}">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                            New Address
                        </span>
                        <span class="float-right cursor-pointer"
                            @click="closeAddressModal()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full mt-4">
                        <input type="text" class="w-full text-center" placeholder="Address" v-model="formAddress.address"
                            style="border: 1px solid black; border-radius: 5px; height: 40px"
                        >
                        <span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
                    </div>

                    <div class="w-full mt-4">
                        <button class="w-full py-2"
                            style="border-radius: 5px; background: #E4B934"
                            @click="createAddress()"
                        >
                            SUBMIT
                        </button>
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

            <div id="removeModal" class="removeModal">
				<!-- Modal content -->
				<div class="remove-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full text-lg font-bold text-center">
						Are you sure to delete this address ?
					</div>

					<div class="w-full flex flex-row mt-10">
                        <div class="w-full">
                            <button class="w-full py-1 text-white" style="border-radius: 5px; width: 93%; background: #000000"
                                @click="closeRemoveModal();"
                            >
                                No
                            </button>
                        </div>

                        <div class="w-full">
                            <button class="w-full py-1 text-black" style="border-radius: 5px; width: 93%; background: #E4B934"
                                @click="removeAddress(placeSelected)"
                            >
                                Yes
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
import axios from "axios";
import Toggle from '../Components/Toggle.vue';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle
    },
    data(){
        return {
            restaurant: null,
            activeCategory: 'Food',
            form:{
                restaurant_id: '',
                category: '',
                name: '',
                amount: null,
                image: '',
                description: null
            },
            formData: new FormData(),
            saveError: null,
            products: [],
            description: null,
            productName: null,
            productAmount: 0,
            productImage: null,
            activeTab: 'menus',
            formAddress: {
                restaurant_id: null,
                address: null
            },
            placeSelected: null,
            isMobile: window.screen.width <= 700,
            formTime: {
                opening_time: null,
                closing_time: null
            },
        }
    },

    created(){
        this.restaurant = this.options.restaurant

        this.restaurant.image = '/images/uploads/' + this.restaurant.image;
        this.restaurant.banner = '/images/uploads/' + this.restaurant.banner;

        this.form.restaurant_id = this.restaurant.id

        this.form.category = this.activeCategory

        this.products = this.restaurant.products

        this.formAddress.restaurant_id = this.restaurant.id

        this.formTime.opening_time = this.restaurant.opening_time
        this.formTime.closing_time = this.restaurant.closing_time
    },

    watch: {
        activeCategory(arg){
            this.form.category = arg
        },

        // 'form.amount': function (arg) {
        //     this.form.amount = parseFloat(arg).toFixed(2);
        // }

        placeSelected(arg) {
            if(!arg) return;

            this.openRemoveModal()
        },
    },

    methods: {
        changeAmount(){
            this.form.amount = parseFloat(this.form.amount).toFixed(2);
        },

        openModal(){
            var modal = document.getElementById("myModal");

            modal.style.display = "block";
        },

        closeModal(){
            var modal = document.getElementById("myModal");

            modal.style.display = "none";

            this.form.name = ''
            this.form.amount = null
            this.form.category = this.activeCategory
            this.form.image = ''
            this.form.restaurant_id = this.restaurant.id
            this.form.description = null

            this.formData = new FormData()
        },

        openDescriptionModal(arg){
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "block";

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

        openAddressModal(){
            var modal = document.getElementById("addressModal");

            modal.style.display = "block";
        },

        closeAddressModal(){
            var modal = document.getElementById("addressModal");

            modal.style.display = "none";

            this.address = null
        },

        imageChange(arg, e) {
	      	const image = e.target.files[0];

	      	this.formData.append(arg, image);
		},
        
        confirmProduct(){
            swal({
                title: 'Are you sure to save this menu ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.createProduct()
                } else {
                    
                }
            });
        },
        createProduct(){
            this.formData.append('restaurant_id', this.form.restaurant_id);
            this.formData.append('category', this.form.category);
            this.formData.append('name', this.form.name);
            this.formData.append('amount', this.form.amount);
            this.formData.append('description', this.form.description);

            axios.post(this.$root.route + "/restaurants/create-product", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully created product");
                        
						location.reload()
					}
				})
        },

        createAddress(){
            axios.post(this.$root.route + "/restaurants/create-address", this.formAddress)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully created new address");
                        
						location.reload()
					}
				})
        },

        removeAddress(arg){
            axios.post(this.$root.route + "/restaurants/remove-address", arg)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully remove address");
                        
						location.reload()
					}
				})
        },

        viewProduct(arg){
            this.openModal()

            this.formData.append('id', arg.id);
            this.form.name = arg.name
            this.form.amount = arg.amount
            this.form.category = arg.category
            this.form.image = ''
            this.form.restaurant_id = arg.restaurant_id
            this.form.description = arg.description

        },

        uploadImage(arg) {
            if(arg == 'image') {
                this.$refs.image.click()
            }

            if(arg == 'banner') {
                this.$refs.banner.click()
            }
        },

        imageChangeRestaurant(arg, e) {
            const image = e.target.files[0];

            var formData = new FormData()

            formData.append('id', this.restaurant.id);
            formData.append(arg, image);

            axios.post(this.$root.route + "/restaurants/change-image", formData)
				.then(response => {
				})

            const reader = new FileReader();

            reader.readAsDataURL(image);

            reader.onload = e =>{
                this.restaurant[arg] = e.target.result
            };
		},

        openRemoveModal(){
            var modal = document.getElementById("removeModal");

            modal.style.display = "block";

        },

        closeRemoveModal(){
            var modal = document.getElementById("removeModal");

            modal.style.display = "none";

            this.placeSelected = null
        },

        confirmTime(){
            swal({
                title: 'Are you sure to set this opening and closing time of your food hub ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.saveTime()
                } else {
                    
                }
            });
        },

        saveTime() {
            axios.post(this.$root.route + "/restaurants/save-time", this.formTime)
				.then(response => {
				})
        }
    }
}
</script>

<style scoped>
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.descriptionModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 10%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
}

/* Modal Content */
.description-content {
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

.--text {
	font-size: calc(.1em + 1vw);
}

.addressModal {
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
.address-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.--scroll::-webkit-scrollbar {
  width: 0px;
  background: white;
}

.--scroll::-webkit-scrollbar-thumb {
  background: #ffffff;
  height:30px;
}

.--scroll::-webkit-scrollbar-track-piece{
   display:none;
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

.removeModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 20%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.remove-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px; 
  border: 1px solid #888;
  width: 80%;
}
</style>