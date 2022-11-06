<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
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

            <div class="w-full mt-8 px-5">
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
                                        <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">₱{{ product.amount.toFixed(2) }}</b>
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
                            @click="close()"
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

            <div id="descriptionModal" class="descriptionModal">
                <!-- Modal content -->
                <div class="description-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                            {{productName}}
                        </span>

                        <span class="float-right cursor-pointer"
                            @click="closeDescriptionModal()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full mt-4">
                        <p>
                            {{ description }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </Navigation>
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
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
            productName: null
        }
    },

    created(){
        this.restaurant = this.options.restaurant

        this.form.restaurant_id = this.restaurant.id

        this.form.category = this.activeCategory

        this.products = this.restaurant.products

        // this.form.amount = parseFloat(this.form.amount).toFixed(2);
    },

    watch: {
        activeCategory(arg){
            this.form.category = arg
        },

        // 'form.amount': function (arg) {
        //     this.form.amount = parseFloat(arg).toFixed(2);
        // }
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
        },

        closeDescriptionModal(){
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "none";

            this.description = null
            this.productName = null
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

        viewProduct(arg){
            this.openModal()

            this.formData.append('id', arg.id);
            this.form.name = arg.name
            this.form.amount = arg.amount
            this.form.category = arg.category
            this.form.image = ''
            this.form.restaurant_id = arg.restaurant_id
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
  top: 40%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
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
</style>