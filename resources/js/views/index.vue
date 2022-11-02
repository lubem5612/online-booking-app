<template>
    <v-container style="z-index: 1000;">
        <v-row class="d-flex justify-content-center align-content-center" style="height: 100vh">
            <v-col sm="12" md="5" class="align-self-center">
                <v-form class="px-4" @submit.prevent="submitForm" lazy-validation ref="form">
                    <v-card tile class="pa-6" color="white">
                        <div class="h6 font-weight-bold mt-3">Welcome to our online library</div>
                        <div class="h3 mt-3 mb-6">Log into your dashboard</div>

                        <label class="text-left">Email Address</label>
                        <v-text-field
                            outlined
                            dense
                            background-color="grey lighten-3"
                            flat
                            v-model="form.email"
                            placeholder="enter email"
                            append-icon="mdi-email"
                        />
                        <ol v-if="errors.email" class="validation-error">
                            <li class="error--text" v-for="(error, i) in errors.email" :key="i">{{error}}</li>
                        </ol>

                        <label class="text-left">Password</label>
                        <v-text-field
                            outlined
                            dense
                            flat
                            background-color="grey lighten-3"
                            v-model="form.password"
                            placeholder="enter password"
                            @keydown="errors.password = ''"
                            @click:append="() => (value = !value)"
                            :type="value? 'password' : 'text'"
                            :append-icon="value?'mdi-lock-outline' : 'mdi-lock-off-outline'"
                        />
                        <ol v-if="errors.password" class="validation-error">
                            <li class="error--text" v-for="(error, i) in errors.password" :key="i">{{error}}</li>
                        </ol>
                        <div class="text-center">
                            <router-link to="/register">Don't have an account? register</router-link>
                        </div>
                        <v-btn tile type="submit" block color="primary" class="pa-6 my-4">
                            <v-progress-circular indeterminate width="5" color="red" v-if="loading" class="mr-2"/>
                            Sign In
                        </v-btn>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapActions, mapGetters} from "vuex";

    export default {
        data: () => ({
            value: String,
            errors: {},
            form: new Form({
                email: "",
                password: "",
            })
        }),
        methods: {
            ...mapActions('auth', ["LOGIN_USER"]),
            submitForm() {
                if (this.$refs.form.validate()) {
                    this.LOGIN_USER(this.form);
                }else {
                    Toast.fire({icon: "error", title: "validation error"})
                }
            }
        },
        computed: {
            ...mapGetters('auth', ["loading"]),
        }
    }
</script>

<style scoped>

</style>
