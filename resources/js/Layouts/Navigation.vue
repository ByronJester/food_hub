<template>
    <div class="w-screen h-screen flex flex-col">
        <div class="w-full flex flex-row fixed"
            style="height: 10vh; background: #000000"
        >   
            <div :style="{'width' : isMobile ? '25%' : '7%'}"
            >
                <img src="/images/logo2.jpg" class="py-1 px-1 cursor-pointer"
                    style="width: 100%; height: 100%; border-radius: 10px"
                    @click="auth.role == '3' ? changeActive('/customers') : auth.role == '1' ? changeActive('/users') : changeActive('/restaurants')"
                />
            </div>

            <div class="flex items-center justify-end" :style="{'width' : isMobile ? '75%' : '97%'}">
                <div class="float-right flex flex-row mr-3 font-bold"
                    style="color: #FFFFFF;"
                >
                    <div class="cursor-pointer" :class="{'--active' : active == '/restaurants', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/restaurants')" v-if="auth.role == 2"
                    >
                        <i class="fa-solid fa-shop mr-2" :style="{'color': active == '/restaurants' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/restaurants' ? '#E4B934' : '#FFFFFF'}">
                            <b>MY FOODHUB</b>
                        </span>
                    </div>

                    <div class="cursor-pointer relative" :class="{'--active' : active == '/restaurants/my-trays', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/restaurants/my-trays')" v-if="auth.role == 2"
                    >
                        <i class="fa-solid fa-cart-shopping mr-2" :style="{'color': active == '/restaurants/my-trays' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/restaurants/my-trays' ? '#E4B934' : '#FFFFFF'}"> 
                            <b>MY TRAYS</b> 
                        </span>

                        <span class="absolute text-red-700 font-bold text-xl" style="top: -0.8rem; right: -0.8rem" v-if="auth.trays > 0">
                            {{ auth.trays }}
                        </span>
                    </div>

                    <div class="cursor-pointer relative" :class="{'--active' : active == '/restaurants/reports', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/restaurants/reports')" v-if="auth.role == 2"
                    >
                        <i class="fa-solid fa-file mr-2" :style="{'color': active == '/restaurants/reports' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/restaurants/reports' ? '#E4B934' : '#FFFFFF'}"> 
                            <b>REPORTS</b> 
                        </span>
                    </div>
                    

                    <div class="cursor-pointer" :class="{'--active' : active == '/customers', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/customers')" v-if="auth.role == 3"
                    >
                        <i class="fa-solid fa-shop mr-2" :style="{'color': active == '/customers' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/customers' ? '#E4B934' : '#FFFFFF'}">
                            <b>FOODHUBS</b>
                        </span>
                    </div>

                    <div class="cursor-pointer relative" :class="{'--active' : active == '/orders', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/orders')" v-if="auth.role == 3"
                    >
                        <i class="fa-solid fa-cart-shopping mr-2" :style="{'color': active == '/orders' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/orders' ? '#E4B934' : '#FFFFFF'}">
                             <b>TRAYS</b>
                        </span>

                        <span class="absolute text-red-700 font-bold text-xl" style="top: -0.8rem; right: -0.8rem" v-if="auth.trays > 0">
                            {{ auth.trays }}
                        </span>
                    </div>

                    <div class="cursor-pointer" :class="{'--active' : active == '/users', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/users')" v-if="auth.role == 1 || (auth.role == 2 && auth.user_type == 'owner')"
                    >
                        <i class="fa-solid fa-users-gear mr-2" :style="{'color': active == '/users' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/users' ? '#E4B934' : '#FFFFFF'}">
                            <b>USERS</b>
                        </span>
                    </div>

                    <div class="cursor-pointer" :class="{'--active' : active == '/users/profile', 'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="changeActive('/users/profile')"
                    >
                        <i class="fa-solid fa-user mr-1" :style="{'color': active == '/users/profile' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/users/profile' ? '#E4B934' : '#FFFFFF'}">
                            <b>PROFILE</b>
                        </span>
                    </div>

                    <div class="cursor-pointer" :class="{'--active' : active == '/trails', 'mx-5' : !isMobile, 'mx-1' : isMobile}" v-if="auth.user_type == 'admin'"
                        @click="changeActive('/trails')"
                    >
                        <i class="fa-solid fa-list mr-1" :style="{'color': active == '/trails' ? '#E4B934' : '#FFFFFF'}"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}" :style="{'color': active == '/trails' ? '#E4B934' : '#FFFFFF'}">
                            <b>TRAILS</b>
                        </span>
                    </div>

                    <!-- <div class="cursor-pointer" :class="{'mx-5' : !isMobile, 'mx-1' : isMobile}" @click="openWalletModal()"
                        v-if="auth.role == 2 && auth.user_type == 'owner'"
                    >
                        <i class="fa-solid fa-wallet mr-1"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}">
                            <b class="text-white">WAL</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">LET</b>
                        </span>
                    </div> -->


                    <div class="cursor-pointer" :class="{'mx-5' : !isMobile, 'mx-1' : isMobile}"
                        @click="logout()"
                    >
                        <i class="fa-solid fa-door-open mr-1"></i>
                        <span :class="{'--text': !isMobile, '--font-small': isMobile}">
                            <b>LOGOUT</b>
                        </span>
                    </div>

                </div>
                
            </div>

            <div id="walletModal" class="walletModal">
                <!-- Modal content -->
                <div class="wallet-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                            My Wallet
                        </span>

                        <span class="float-right cursor-pointer"
                            @click="closeWalletModal()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    </div>

                    <div class="w-full flex flex-col mt-4 text-center text-4xl">
                        ₱ {{ auth.wallet.toFixed(2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full --main--div"
            style="height: 100vh; margin-top: 10vh"
        >
            <div class="w-full ml-5 my-5 font-bold --text" v-if="auth.user_type == 'customer'">
                <i class="fa-solid fa-user mr-2"></i> {{ auth.name }} 
            </div>

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
            active: window.location.pathname,
            isMobile: window.screen.width <= 700,
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
        },

        openWalletModal(){
            var modal = document.getElementById("walletModal");

            modal.style.display = "block";
        },

        closeWalletModal(){
            var modal = document.getElementById("walletModal");

            modal.style.display = "none";
        },
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
    font-size: calc(.3em + 1vw);
}

.--font-small {
    font-size: 8px;
}

.walletModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 20%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.wallet-content {
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
