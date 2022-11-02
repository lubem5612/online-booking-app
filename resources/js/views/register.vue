<template>
    <v-container style="z-index: 1000;">
        <v-row class="d-flex justify-content-center align-content-center" style="height: 100vh">
            <v-col sm="12" md="6" class="align-self-center">
                <v-form class="px-4" @submit.prevent="submitForm" lazy-validation ref="form">
                    <v-card tile class="pa-6" color="white">
                        <div class="h6 font-weight-bold mt-3">Welcome to our online library</div>
                        <div class="h3 mt-3 mb-6">Create Your Account</div>

                        <label class="text-left">Full Name</label>
                        <v-text-field
                            outlined
                            dense
                            background-color="grey lighten-3"
                            flat
                            v-model="form.name"
                            placeholder="enter full name"
                            append-icon="mdi-account"
                        />
                        <ol v-if="errors.name" class="validation-error">
                            <li class="error--text" v-for="(error, i) in errors.name" :key="i">{{error}}</li>
                        </ol>

                        <v-row class="mt-n5">
                            <v-col sm="12" md="6">
                                <label class="text-left">Email </label>
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
                            </v-col>
                            <v-col md="6" sm="12">
                                <label class="text-left">Upload Profile Pic</label>
                                <div class="green lighten-4 d-block d-flex justify-content-center photo-input"
                                     style="height: 42px;"
                                     @click.prevent="$refs.photo.click()"
                                >
                                    <v-icon class="align-self-center" size="30" color="primary accent-3">mdi-account-edit</v-icon>
                                    <div class="align-self-center px-2">
                                        <div class="text-capitalize">{{form.photo.name?form.photo.name.substr(0, 15)+'...' : 'upload picture'}}</div>
                                    </div>
                                </div>
                                <ol v-if="photo_errors.length" class="" style="margin-bottom: -10px;">
                                    <li class="error--text" v-for="(error, i) in photo_errors" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                        </v-row>

                        <v-row class="mt-n6">
                            <v-col sm="12" md="6">
                                <label class="text-left">Password </label>
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
                            </v-col>
                            <v-col sm="12" md="6">
                                <label class="text-left">Confirm Password</label>
                                <v-text-field
                                    background-color="grey lighten-3"
                                    :rules="[form.password === form.password_confirmation || 'Password must match']"
                                    outlined
                                    dense
                                    flat
                                    v-model="form.password_confirmation"
                                    placeholder="re-enter password"
                                    @keydown="errors.password_confirmation = ''"
                                    @click:append="() => (value2 = !value2)"
                                    :type="value2? 'password' : 'text'"
                                    :append-icon="value2?'mdi-lock-outline' : 'mdi-lock-off-outline'"
                                />
                                <ol v-if="errors.password_confirmation" class="validation-error">
                                    <li v-for="(error, i) in errors.password_confirmation" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                        </v-row>

                        <input
                            type="file"
                            ref="photo"
                            @change.prevent="selectPhoto"
                            class="d-none mb-2"
                            accept="image/*"
                        />

                        <div class="text-center">
                            <router-link to="/">Already have account? login</router-link>
                        </div>
                        <v-btn tile type="submit" block color="primary" class="pa-6 my-4">
                            <v-progress-circular indeterminate width="5" color="red" v-if="loading" class="mr-2"/>
                            Sign Up
                        </v-btn>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    export default {
        name: "Register",
        data: () => ({
            value: String,
            value2: String,
            processing: false,
            errors: {},
            photo_errors: [],
            form: new Form({
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                photo: {}
            })
        }),
        methods: {
            submitForm() {
                if(this.$refs.form.validate()) {
                    const data = new FormData();
                    data.append('name', this.form.name);
                    data.append('email', this.form.email);
                    data.append('password', this.form.password);
                    data.append('password_confirmation', this.form.password_confirmation);
                    data.append('photo', this.form.photo);

                    this.processing = true;
                    api.post(`/api/register`, data).then(res => {
                        if(res.success) {
                            Toast.fire({icon: "success", title: "registration successful"});
                            Swal.fire({
                                title: 'Registered',
                                text: 'Account has been created, proceed to login',
                                icon: 'success',
                                showConfirmButton: true,
                            }).then(result => {
                                if (result.isConfirmed) {
                                    location.replace('/');
                                }
                            });
                        }
                    }).catch(err => {
                        this.errors = err.errors;
                        Toast.fire({icon: "error", title: err.message});
                    }).finally(() => {
                        this.processing = false;
                    })
                }else {
                    Toast.fire({icon: "error", title: 'form fields error'});
                }
            },
            selectPhoto(event) {
                this.form.photo = event.target.files[0];
            },
        },
    }
</script>

<style scoped lang="scss">
    .photo-input:hover {
        background-color: #bcc1c6;
        cursor: pointer;
    }
</style>
