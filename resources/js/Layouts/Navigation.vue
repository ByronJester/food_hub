<template>
    <div class="w-screen h-screen flex flex-col">
        <div class="w-full flex flex-row"
            style="height: 7%; border: 1px solid black; background: #90A4AE"
        >   
            <div style="width: 7%">
                <img src="/images/logo.png" class="py-1 px-1 cursor-pointer"
                    style="width: 100%; height: 100%; border-radius: 10px"
                />
            </div>

            <div style="width: 93%">
                <div class="float-right flex flex-row mt-6 mr-3 font-bold"
                    style="color: #FFD600"
                >
                    <div class="mx-5 cursor-pointer" :class="{'--active' : active == '/users'}"
                        @click="changeActive('/users')"
                    >
                        <i class="fa-solid fa-users-gear mr-1"></i>USERS
                    </div>

                    <div class="mx-5 cursor-pointer"
                        @click="logout()"
                    >
                        <i class="fa-solid fa-door-open"></i>
                    </div>

                </div>
                
            </div>
        </div>

        <div class="w-full"
            style="height: 93%"
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
    border-bottom: 2px solid black;
}
</style>
