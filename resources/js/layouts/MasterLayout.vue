<template>
   <div>
       <v-navigation-drawer v-model="drawer" fixed app>
           <sidebar></sidebar>
       </v-navigation-drawer>
       <v-app-bar :clipped-left="clipped" fixed app tile>
           <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
           <v-spacer />
           <v-toolbar-items class="nav-item align-center" v-if="$vuetify.breakpoint.width < width.mobile.max_width">
               <v-icon class="mt-1 nav-link" size="25">mdi-magnify</v-icon>
           </v-toolbar-items>
           <v-toolbar-items class="nav-item align-center" style="mx-3">
               {{ new Date() | dashboardDateFormat }}
           </v-toolbar-items>

           <v-toolbar-items
               v-if="$vuetify.breakpoint.width > width.mobile.max_width"
               class="nav-item bg-white d-flex rounded-20 py-1 px-2 mt-1 align-content-center justify-content-center"
               style="height: 40px"
           >
               <v-avatar size="30" class="mr-2 nav-link">
                   <v-icon size="20">mdi-account</v-icon>
               </v-avatar>
               <div class="text-dark align-self-center font-weight-bold">{{ user.email }}</div>
               <v-divider vertical class="mx-3" />
               <v-icon class="logout" @click.prevent="logout">mdi-power-standby</v-icon>
               <v-btn text class="text-dark align-self-center font-weight-bold text-uppercase logout" style="font-size: 80%" @click.prevent="logout">
                   Log out
               </v-btn>
           </v-toolbar-items>

           <v-toolbar-items
               v-else
               class="nav-item bg-white d-flex rounded-20 py-1 px-2 mt-1 mr-2 align-content-center justify-content-center"
               style="height: 40px"
           >
               <v-menu bottom offset-y left style="min-width: 250px">
                   <template v-slot:activator="{ on, attrs }">
                       <v-avatar v-bind="attrs" v-on="on" size="30" class="nav-link">
                           <v-icon size="20">mdi-account</v-icon>
                       </v-avatar>
                   </template>
                   <v-list class="p-0 m-0">
                       <v-list-item class="px-3 m-0" link @click.prevent="logout">
                           <v-list-item-title> logout </v-list-item-title>
                       </v-list-item>
                   </v-list>
               </v-menu>
           </v-toolbar-items>
       </v-app-bar>
       <v-main style="background-color: ghostwhite; height: 100vh;">
           <v-container>
               <router-view></router-view>
           </v-container>
       </v-main>
   </div>
</template>

<script>
    import { mapActions, mapGetters} from 'vuex';
    import { screenWidth } from '../utils/variables';
    import Sidebar from "../components/Sidebar";

    export default {
        name: 'DefaultLayout',
        components: {Sidebar},
        middleware: 'auth',

        data() {
            return {
                width: screenWidth,
                clipped: false,
                drawer: true,
            };
        },
        methods: {
            ...mapActions({
                loggedInUser: 'auth/AUTH_USER',
                logoutUser: 'auth/LOGOUT_USER',
            }),
            logout() {
                this.logoutUser({});
            }
        },
        computed: {
            ...mapGetters({ user: 'auth/user', token: 'auth/token' }),
        },
        mounted() {
            if(!this.token) {
                this.loggedInUser();
            }
        },
    };
</script>

<style scoped lang="scss">
    .v-sheet.v-toolbar:not(.v-sheet--outlined) {
        box-shadow: 0 0 0 0 #e0e0e0 !important;
    }
    .logout:hover {
        cursor: pointer;
    }
    @media only screen and (max-width: 768px) {
        .mobile-hide {
            display: none;
        }
    }
    #wrapper-content {
        height: inherit !important;
    }
    .nav-item {
        height: inherit;
        align-self: center;
    }
</style>
