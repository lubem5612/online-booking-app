<template>
    <div>
        <div class="d-flex justify-content-start my-3 ml-3" style="height: 60px;">
            <v-icon size="40">mdi-book-open-page-variant</v-icon>
            <span class="primary--text pl-2 align-self-center" style="font-size: 15px!important;">Online Booking<br/> Platform</span>
        </div>


        <v-list dense nav v-model="selectedNav">
            <!-- added for children -->
            <template v-for="nav in navList">
                <!-- title -->
                <v-list-item v-if="nav.title">
                    <v-list-item-content>
                        <v-list-item-title class="text-uppercase">{{ nav.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <!-- first children -->
                <v-list-group v-else-if="nav.children" :value="active" :prepend-icon="nav.icon" :key="nav.name">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>{{ nav.name }}</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <router-link v-for="(child, i) in nav.children" :key="i" :to="child.path" class="link-unstyled">
                        <v-list-item link class="ml-5">
                            <v-list-item-icon>
                                <v-icon :style="activeNav(child.path)">{{ child.icon }}</v-icon>
                            </v-list-item-icon>

                            <v-list-item-title :style="activeNav(child.path)">{{ child.name }}</v-list-item-title>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <!-- for request -->
                <router-link v-else-if="nav.sub" :to="nav.path" :key="nav.name" class="link-unstyled">
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon :style="activeNav(nav.path)">{{ nav.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            {{ nav.name }}
                            <span class="py-1 px-2 rounded-10 accent text-white ml-2">{{ requests > 0 ? requests : 0 }}</span>
                        </v-list-item-title>
                    </v-list-item>
                </router-link>

                <!-- if item is divider -->
                <v-divider v-else-if="nav.divider" class="divider"></v-divider>

                <!-- no children -->

                <router-link v-else :to="nav.path" :key="nav.name" class="link-unstyled">
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon :style="activeNav(nav.path)">{{ nav.icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-title :style="activeNav(nav.path)" :class="nav.path === '#' ? 'text-gray' : ''">{{ nav.name }}</v-list-item-title>
                    </v-list-item>
                </router-link>
            </template>

            <v-divider class="mt-2 mb-2"></v-divider>
            <router-link to="/logout" class="link-unstyled logout-container">
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-cog</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </router-link>
        </v-list>
    </div>
</template>

<script>
    import { mapGetters} from 'vuex';
    import * as Nav from '../utils/nav-list';

    export default {
        name: 'Sidebar',
        data: () => ({
            selectedNav: 0,
            active: false,
            navList: [],
        }),
        methods: {
            activeNav(path) {
                if (this.$route.path === path) return { color: '#006600' };
            },
            getNavigationList() {
                switch (this.user.role) {
                    case 'librarian': {
                        this.navList = Nav.librarian;
                        break;
                    }
                    case 'reader': {
                        this.navList = Nav.reader;
                        break;
                    }
                    default: {
                        this.navList = [];
                    }
                }
            },
        },
        mounted() {
            this.getNavigationList();
        },
        computed: {
            ...mapGetters({
                user: 'auth/user',
            }),
        },
        watch: {
            user() {
                this.getNavigationList();
            }
        }
    };
</script>

<style scoped lang="scss">
    body:not(.layout-fixed) .main-sidebar {
        height: 100vh !important;
        min-height: 0 !important;
        position: absolute;
        top: 0;
    }
    .side-nav {
        position: fixed !important;
        height: 100vh !important;
        top: 0 !important;
    }
    .sidebar {
        height: calc(100% - (3.5rem + 1px));
        overflow-y: scroll;
        padding: 0 !important;
    }
    .v-list-item.v-list-item--link.theme--light {
        margin-bottom: 7px !important;
    }
    .admin {
        margin-bottom: 35vh;
    }
    .super-agent {
        margin-bottom: 15vh;
    }
    .fitting-agent {
        margin-bottom: 10px;
    }
    .client {
        margin-bottom: 10px;
    }
    .link-unstyled {
        text-decoration: none;
    }
    .divider {
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .logout-container {
        margin-top: 100px;
    }
</style>
