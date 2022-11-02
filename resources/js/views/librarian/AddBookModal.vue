<template>
    <v-row justify="center" v-if="isVisible">
        <v-dialog
            v-model="isVisible"
            @click:outside="$emit('onClickOutside')"
            persistent
            max-width="600px"
        >
            <v-form @submit.prevent="handleFormSubmit" v-model="isValid" ref="form" lazy-validation>
                <v-sheet class="pa-2">
                    <v-card-title class="headline white--text pa-0 mb-5">
                        <v-app-bar
                            height="50"
                            color="transparent"
                            class="pa-0 ma-0"
                            flat
                        >
                            <v-spacer/>
                            <v-app-bar-title class="text-uppercase modal-title" style="font-size: 18px !important;">{{editing?"Edit Book" : "Add Book"}}</v-app-bar-title>
                            <v-spacer/>
                            <v-btn icon @click.prevent="dismissModal">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </v-app-bar>
                    </v-card-title>

                    <v-card-text>

                        <v-row class="mt-lg-10">
                            <v-col cols="6">
                                <v-text-field
                                    v-model="form.title"
                                    :rules="[v => !!v || 'title is required']"
                                    rounded
                                    filled
                                    label="Title"
                                    placeholder="enter title"
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.title" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.title" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                            <v-col col="6">
                                <v-text-field
                                    v-model="form.isbn"
                                    :rules="[v => !!v || 'ISBN is required']"
                                    rounded
                                    filled
                                    label="ISBN"
                                    placeholder="enter ISBN"
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.isbn" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.isbn" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                        </v-row>

                        <v-row class="mt-2 pt-1">
                            <v-col col="6">
                                <v-text-field
                                    v-model="form.publisher"
                                    :rules="[v => !!v || 'publisher is required']"
                                    rounded
                                    filled
                                    label="Publisher"
                                    placeholder="enter publisher"
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.publisher" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.publisher" :key="i">{{error}}</li>
                                </ol>
                            </v-col>

                            <v-col col="6">
                                <v-menu
                                    v-model="menu1"
                                    :close-on-content-click="false"
                                    max-width="290"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            :value="publishedTimeFormat"
                                            clearable
                                            label="Published Date"
                                            readonly
                                            rounded
                                            filled
                                            append-icon="mdi-calendar"
                                            v-bind="attrs"
                                            v-on="on"
                                            @click:clear="form.published_date = null"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="form.published_date"
                                        @change="menu1 = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col col="6">
                                <v-text-field
                                    v-model="form.revision_no"
                                    :rules="[v => !!v || 'Revision No is required']"
                                    rounded
                                    filled
                                    label="Revision Number"
                                    placeholder="enter revision no"
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.revision_no" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.revision_no" :key="i">{{error}}</li>
                                </ol>
                            </v-col>

                            <v-col col="6">
                                <div class="grey lighten-3 d-block d-flex justify-content-center file-input"
                                     style="height: 54px; margin-top: 2px"
                                     @click.prevent="$refs.cover.click()"
                                >
                                    <v-icon class="align-self-center" size="30" color="primary accent-3">mdi-file-edit</v-icon>
                                    <div class="align-self-center px-2">
                                        <div class="text-capitalize">{{form.cover.name?form.cover.name.substr(0, 15)+'...' : 'upload cover image'}}</div>
                                    </div>
                                </div>
                                <ol v-if="photo_errors.length" class="" style="margin-bottom: -10px;">
                                    <li class="error--text" v-for="(error, i) in photo_errors" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="6">
                                <v-select
                                    v-model="form.genre_id"
                                    :items="genres"
                                    :rules="[v => !!v || 'genre is required']"
                                    item-value="id"
                                    item-text="name"
                                    label="Genre"
                                    placeholder="select genre"
                                    rounded
                                    filled
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.genre_id" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.genre_id" :key="i">{{error}}</li>
                                </ol>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                    v-model="form.authors"
                                    rounded
                                    filled
                                    label="Authors"
                                    placeholder="separate with comma"
                                    @keydown="errors={}"
                                />
                                <ol v-if="errors.authors" class="validation-error">
                                    <li class="error--text" v-for="(error, i) in errors.authors" :key="i">{{error}}</li>
                                </ol>
                            </v-col>
                        </v-row>
                        <input
                            type="file"
                            ref="cover"
                            @change.prevent="selectCover"
                            class="d-none mb-2"
                            accept="image/*"
                        />
                    </v-card-text>

                    <v-card-actions class="mr-sm-2 mt-2">
                        <v-spacer/>

                        <v-btn
                            color="primary darken-1"
                            class="py-md-6 px-md-6 mr-1 py-sm-4 px-sm-4"
                            type="submit"
                            tile
                        >
                            <v-progress-circular color="error" width="5" indeterminate v-if="processing" class="mr-2"/>
                            {{editing? "Save Changes" : "Add Book"}}
                        </v-btn>
                    </v-card-actions>
                </v-sheet>
            </v-form>
        </v-dialog>
    </v-row>
</template>

<script>
    import {mapActions, mapGetters, mapMutations} from "vuex";
    import {api} from "../../services";
    export default {
        props: {
            isVisible: {
                type: Boolean,
                default: false,
            },
            editing: {
                type: Boolean,
                required: true,
                default: false,
            }
        },
        data: () => ({
            menu1: null,
            errors: {},
            isValid: false,
            processing: false,
            photo_errors: [],
            genres: [],
            form: new Form({
                title: "",
                isbn: "",
                revision_no: "",
                publisher: "",
                published_date: null,
                cover: {},
                authors: "",
                genre_id: "",
            }),
        }),
        methods: {
            ...mapMutations({
                saveBook: 'book/saveBook'
            }),
            handleFormSubmit() {
                this.$props.editing? this.updateBook() : this.createBook();
            },
            createBook() {
                if (this.$refs.form.validate()) {
                    const data = new FormData();
                    data.append('title', this.form.title);
                    data.append('isbn', this.form.isbn);
                    data.append('revision_no', this.form.revision_no);
                    data.append('publisher', this.form.publisher);
                    data.append('published_date', this.form.published_date);
                    data.append('cover', this.form.cover);
                    data.append('authors', this.form.authors);
                    data.append('genre_id', this.form.genre_id);

                    this.processing = true;
                    api.post(`/api/books`, data).then(res => {
                        Toast.fire({icon: "success", title: `book added successfully`});
                        this.saveBook(res.data);
                        this.$emit('onComplete');
                    }).catch(error => {
                        if (error.statusCode === 422) {
                            this.errors = error.errors;
                        }
                        Toast.fire({icon: "error", title: error.message});
                    }).finally(() => {
                        this.processing = false;
                    })
                }else {
                    Toast.fire({icon: 'error', title: 'failed validation errors'});
                }
            },
            updateBook() {
                if (this.$refs.form.validate()) {
                    const data = new FormData();
                    data.append("title",this.form.title !== 'null' && this.form.title !== null?  this.form.title : '');
                    data.append("isbn",this.form.isbn !== 'null' && this.form.isbn !== null?  this.form.isbn : '');
                    data.append("revision_no",this.form.revision_no !== 'null' && this.form.revision_no !== null?  this.form.revision_no : '');
                    data.append("publisher",this.form.publisher !== 'null' && this.form.publisher !== null?  this.form.publisher : '');
                    data.append("published_date",this.form.published_date !== 'null' && this.form.published_date !== null?  this.form.published_date : '');
                    data.append("authors",this.form.authors !== 'null' && this.form.authors !== null?  this.form.authors : '');
                    data.append("genre_id",this.form.genre_id !== 'null' && this.form.genre_id !== null?  this.form.genre_id : '');
                    if (!_.isEqual(this.form.cover, {})) {
                        data.append("cover", this.form.cover);
                    }

                    this.processing = true;
                    api.post(`/api/books/${this.book.id}`, data).then(res => {
                        Toast.fire({icon: "success", title: `book updated successfully`});
                        this.saveBook(res.data);
                        this.$emit('onComplete');
                    }).catch(error => {
                        if (error.statusCode === 422) {
                            this.errors = error.errors;
                        }
                        Toast.fire({icon: "error", title: error.message});
                    }).finally(() => {
                        this.processing = false;
                    })
                }else {
                    Toast.fire({icon: 'error', title: 'failed validation errors'});
                }
            },
            getParams() {
                return {search: "", start: "", end: "", page: 1};
            },
            handleEditing() {
                if (this.$props.editing) {
                    this.form.title = this.book.title;
                    this.form.publisher =this.book.publisher;
                    this.form.isbn = this.book.isbn;
                    this.form.revision_no = this.book.revision_no;
                    this.form.published_date = this.book.published_date;
                    this.form.authors = '';
                    this.form.genre_id = this.book.genre.id;
                }else {
                    this.form.reset();
                }
            },
            selectCover(event) {
                this.form.cover = event.target.files[0];
            },
            getGenres() {
                api.get(`/api/genres`).then(res => {
                    this.genres = res.data;
                }).catch(err => {});
            },
            dismissModal() {
                this.$emit('onDismiss')
            }
        },
        computed: {
            ...mapGetters({
                book: 'book/book',
            }),
            publishedTimeFormat() {
                return this.form.published_date ? dayjs(this.form.published_date).format('DD-MM-YYYY') : ''
            },
        },
        mounted() {
            this.handleEditing();
            this.getGenres();
        },
        watch: {
            editing(value) {
                if (value === false) {
                    this.form.reset();
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    .v-text-field--rounded>.v-input__control>.v-input__slot {
        padding: 0 2px!important;
    }
    .v-text-field--rounded {
        border-radius: 3px!important;
        padding: 0 2px !important;
    }
    .file-input:hover {
        background-color: gray;
        cursor: pointer;
    }
    .col {
        margin-top: -25px!important;
    }
    .link:hover {
        cursor: pointer;
        color: #2e7d32;
        text-decoration: underline darkblue solid;
    }
    .form-row-10 {
        margin-top: -25px;
    }
</style>
