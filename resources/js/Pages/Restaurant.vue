<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
            <div class="w-full relative">
				<div class="w-full">
					<img :src="restaurant.banner" 
						style="height: 400px; width: 100%; "
						class="p-5 relative cursor-pointer"
                        @click="uploadImage('banner')"
					>

					<img :src="restaurant.image" class="absolute cursor-pointer"
						style="height: 180px; width: 20%; top: 17rem; left: 4rem; border: 1px solid #E4B934"
                        @click="uploadImage('image')" 
					>

                    <input type="file" ref="image" @change="imageChangeRestaurant('image', $event)" accept="image/png, image/jpeg" style="display:none">
                    <input type="file" ref="banner" @change="imageChangeRestaurant('banner', $event)" accept="image/png, image/jpeg" style="display:none">
				</div>

                <div class="w-full flex justify-center items-center mt-20">
                    <div class="w-6/12 flex flex-row" style="border-bottom: 1px solid #E4B934;">
                        <div class="w-full cursor-pointer mx-2 text-center --text"
                            :class="{'bg-yellow-200': activeTab == 'menus'}"
                            @click="activeTab = 'menus'"
                        >
                            Menus
                        </div>

                        <div class="w-full cursor-pointer mx-2 text-center --text"
                            :class="{'bg-yellow-200': activeTab == 'places'}"
                            @click="activeTab = 'places'"
                        >
                            Places
                        </div>
                    </div>
                </div>

                <div class="w-full" v-if="activeTab == 'places'">
                    <span class="float-right mr-2 cursor-pointer" style="border: 1px solid black" @click="openAddressModal()">
                        <i class="fa-solid fa-plus p-2"></i>
                    </span>
                </div>

                <div class="w-full flex justify-center items-center" v-if="activeTab == 'places'">
                    <div class="w-4/12 --scroll" style="overflow-y: scroll; height:300px; border: 1px solid black;">
                        <p class="inline-flex relative w-full px-1" v-for="add in options.address" :key="add.id" style="border-bottom: 1px solid black">
                            <span>
                                {{add.address}}
                            </span>

                            <span class="absolute cursor-pointer" style="right: .5rem" @click="placeSelected = add">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="w-full mt-5 px-5" v-if="activeTab == 'menus'">
                    <div class="flex flex-row float-right" style="width: 30%">
                        <div class="w-full cursor-pointer mx-2 text-center --text"
                            style="border: 1px solid #E4B934;"
                            :class="{'bg-yellow-200': activeCategory == 'Food'}"
                            @click="activeCategory = 'Food'"
                        >
                            Foods
                        </div>

                        <div class="w-full cursor-pointer mx-2 text-center --text"
                            style="border: 1px solid #E4B934;"
                            :class="{'bg-yellow-200': activeCategory == 'Drink'}"
                            @click="activeCategory = 'Drink'"
                        >
                            Drinks
                        </div>

                        <div class="cursor-pointer mx-2 text-center"
                            style="border: 1px solid #E4B934; width: 20%"
                            @click="openModal()"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                </div>
			</div>

            <div class="w-full mt-8 px-5" v-if="activeTab == 'menus'">
                <div class="grid grid-cols-5 gap-4 flex justify-center items-center">
                    <div class="w-full flex flex-col" v-for="product in products.filter( x => { return x.category == activeCategory})" :key="product.id"
                        style="border: 1px solid #E4B934"
                    >   
                        <div class="w-full inline-flex mt-3">
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
                            <div class="w-full px-4">
                                <button class="w-full py-1 cursor-default"
                                    style="border-radius: 5px; background: #000000"
                                >
                                    <span class="--text px-2"> 
                                        <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">₱{{ parseFloat(product.amount).toFixed(2) }}</b>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content flex flex-col" style="width: 20%">
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
                            style="border: 1px solid black; border-radius: 5px;  height: 40px"
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
                            @click="createProduct()"
                        >
                            SUBMIT
                        </button>
                    </div>
                </div>
            </div>

            <div id="addressModal" class="addressModal">
                <!-- Modal content -->
                <div class="address-content flex flex-col" style="width: 20%">
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
                <!-- Modal content -->
                <div class="description-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
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
                                    style="height: 200px; border: 2px solid #E4B934"
                                />
                            </div>

                            <div class="w-full">
                                <p class="w-full text-center text-4xl">
                                    ₱ {{ parseFloat(productAmount).toFixed(2) }}
                                </p>
                            </div>
                           
                            
                        </div>

                        <div class="w-full mt-5">
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
                amount: '0.00',
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
            isMobile: window.screen.width <= 700
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
            this.form.amount = ''
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