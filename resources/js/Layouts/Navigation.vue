<template>
    <div class="w-screen h-screen flex flex-col">
        <div class="w-full flex flex-row"
            style="height: 10vh; background: #000000"
        >   
            <div style="width: 7%">
                <img src="/images/logo2.png" class="py-1 px-1 cursor-pointer"
                    style="width: 100%; height: 100%; border-radius: 10px"
                />
            </div>

            <div style="width: 93%" class="flex items-center justify-end">
                <div class="float-right flex flex-row mr-3 font-bold"
                    style="color: #FFFFFF;"
                >
                    <div class="mx-5 cursor-pointer" :class="{'--active' : active == '/restaurants'}"
                        @click="changeActive('/restaurants')" v-if="auth.role == 2 && auth.user_type == 'owner'"
                    >
                        <i class="fa-solid fa-shop mr-2" style="color: #FFFFFF"></i>
                        <span class="--text">
                            <b class="text-white">MY FOOD</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">HUB</b>
                        </span>
                    </div>

                    <div class="mx-5 cursor-pointer" :class="{'--active' : active == '/customers'}"
                        @click="changeActive('/customers')" v-if="auth.role == 3"
                    >
                        <i class="fa-solid fa-shop mr-2" style="color: #FFFFFF"></i>
                        <span class="--text">
                            <b class="text-white">FOOD</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">HUBS</b>
                        </span>
                    </div>

                    <div class="mx-5 cursor-pointer" :class="{'--active' : active == '/orders'}"
                        @click="changeActive('/orders')" v-if="auth.role == 3"
                    >
                        <i class="fa-solid fa-cart-shopping mr-2" style="color: #FFFFFF"></i>
                        <span class="--text">
                            <b class="text-white">CA</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">RTS</b>
                        </span>
                    </div>

                    <div class="mx-5 cursor-pointer" :class="{'--active' : active == '/users'}"
                        @click="changeActive('/users')" v-if="auth.role == 1 || (auth.role == 2 && auth.user_type == 'owner')"
                    >
                        <i class="fa-solid fa-users-gear mr-1"></i>
                        <span class="--text">
                            <b class="text-white">US</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">ERS</b>
                        </span>
                    </div>

                    <div class="mx-5 cursor-pointer"
                        @click="logout()"
                    >
                        <i class="fa-solid fa-door-open mr-1"></i>
                        <span class="--text">
                            <b class="text-white">LOG</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">OUT</b>
                        </span>
                    </div>

                </div>
                
            </div>
        </div>

        <div class="w-full"
            style="height: 90vh"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
	props:['auth'],
	data(){
        return {
            leftPanel: '3%',
            rightPanel: '97%',
            active: window.location.pathname
        }
	},

    created(){
        
    },

	methods: {
        logout(){
            Inertia.post(this.$root.route + "/users/logout", {},
			{
				onSuccess: (res) => {
				},
				orError: (err) => {
				}
			});
        },

        changeActive(arg){
            Inertia.get(
                this.$root.route + arg,
                {
                    onSuccess: () => { },
                },
            );
        }
	}
}

</script>

<style scoped>
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #ffffff;
  min-width: 130px;
  z-index: 1;
  border: 1px solid black;
  border-radius: 5px;
  left: -2.5rem;
}

.dropdown-content a {
  color: black;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #2c85c1;}

.dropdown:hover .dropdown-content {display: block;}

.--active {
    border-bottom: 2px solid #E4B934;
}

.--text {
    font-size: calc(.1em + 1vw);
}
</style>
