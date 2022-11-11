<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
			<div class="w-full mt-3">
				<input type="text" style="width: 300px; height: 50px; border: 1px solid black; border-radius: 10px" class="float-right px-5"
					placeholder="Search..." v-model="search"
				>
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

					<div class="text-center font-bold text-xl --text">
						{{ arg.restaurant_name }}
					</div>
				</div>
			</div>

            <div class="w-full" v-else>
				<div class="w-full text-2xl font-bold px-5 pt-5">
					<span class="cursor-pointer --text" @click="restaurant = null">
						<i class="fa-solid fa-arrow-left"></i> Back
					</span>

                    <span class="cursor-pointer float-right --text" @click="restaurant = null">
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
						<div class="flex flex-row float-right --text" style="width: 30%">
							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Food'}"
								@click="activeCategory = 'Food'"
							>
								Foods
							</div>

							<div class="w-full cursor-pointer mx-2 text-center --text"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Drink'}"
								@click="activeCategory = 'Drink'"
							>
								Drinks
							</div>
						</div>
					</div>
				</div>

				<div class="w-full my-20 px-5">
					<div class="grid grid-cols-5 gap-4 flex justify-center items-center">
						<div class="w-full flex flex-col" v-for="(product, index) in restaurant.products.filter( x => { return x.category == activeCategory})" :key="product.id"
							style="border: 1px solid #E4B934"
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
                                        <span class="--text px-2"> 
                                            <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">₱{{ product.amount.toFixed(2) }}</b>
                                        </span>
                                    </button>
                                </div>

								<div class="w-full flex flex-row mt-2 justify-center items-center">
									<div class="pr-1 w-full" style="width: 15%">
										<input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" v-model="forms[index].quantity">
									</div>

									<div class="pr-4 w-full" style="width: 15%">
										<button class="w-full cursor-poineter --text"
											style="border-radius: 5px; background: #E4B934"
											@click="addToCart(forms[index], index)"
										>
											<i class="fa-solid fa-cart-shopping pt-3 pb-2" style="color: #000000"></i>
										</button>
									</div>
								</div>
							</div>
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
        </div>
    </Navigation>
</template>


<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";
import { Inertia } from '@inertiajs/inertia';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
    },
    data(){
        return {
            restaurants: [],
			searchRestaurants: [],
            restaurant: null,
            activeCategory: 'Food',
			forms: [],
			list: [],
			description: null,
            productName: null,
			search: null
        }
    },
    created(){
        this.restaurants = this.options.restaurants
    },
	watch: {
		activeCategory(arg) {
			this.list = this.restaurant.products.filter( x => { return x.category == arg})

			this.forms = [];

			for (let i = 0; i < this.list.length; i++){
				this.forms.push(
					{
						product_id: this.list[i].id,
						restaurant_id: arg.id,
						quantity: 1,
						status: 'pending',
					}
				);
			}
		},
		search(arg){
			if(!arg) {
				this.restaurants = this.options.restaurants
			} else {
				this.restaurants = this.options.restaurants.filter(x => {
					var name = x.restaurant_name.toLowerCase();
					var search = arg.toLowerCase()
					return name.includes(search)
				});
			}

			
			
		}
	},
    methods: {
        selectShop(arg){
			this.restaurant = arg
			
			this.list = arg.products.filter( x => { return x.category == this.activeCategory})

			this.forms = [];

			for (let i = 0; i < this.list.length; i++){
				this.forms.push(
					{
						product_id: this.list[i].id,
						restaurant_id: arg.id,
						quantity: 1,
						status: 'pending',
					}
				);
			}

		},

		addToCart(selected, index){
			axios.post(this.$root.route + "/customers/place-order", selected)
				.then(response => {
					this.forms[index].quantity = 1

					Inertia.get(
						this.$root.route + '/orders',
						{
							onSuccess: () => { },
						},
					);
				})
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
    }
}
</script>

<style scoped>
.--restaurant__list{
	border: 1px solid #E4B934;
	border-radius: 5px;
	height: 250px;
}

.--text {
	font-size: calc(.1em + 1vw);
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
</style>