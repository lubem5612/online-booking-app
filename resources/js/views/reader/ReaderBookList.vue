<template>
    <div class="pa-3">
        <div v-if="!books.length" class="" style="height: 70vh">
            <v-row class="fill-height" align-content="center" justify="center">
                <v-col sm="6" class="text-center" v-if="search || date ">
                    <v-icon size="130" color="secondary">mdi-emoticon-confused</v-icon>
                    <div class="h4 font-weight-bold my-4">No Result found</div>
                    <div>No search result was found at the moment</div>
                    <v-btn
                        class="px-sm-6 px-md-10 py-sm-3 py-md-4 mt-4"
                        tile
                        color="primary"
                        @click.prevent="resetSearch"
                    >
                        Search Again
                    </v-btn>
                </v-col>
                <v-col sm="4" class="text-center" v-else>
                    <hr class="dashed-divider"/>
                    <hr class="plain-divider"/>
                    <hr class="plain-divider"/>
                    <hr class="plain-divider"/>
                    <div class="text-center">
                        <h6 class="info--text text-bold">
                            No Book List Yet
                        </h6>
                        <div class="info--text mb-5">Book has not been added yet.</div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <div v-else class="mt-4">

            <v-row class="">
                <v-col md="4" sm="12">
                    <div class="text-h6 font-weight-bold my-md-4 my-sm-2">Books</div>
                </v-col>
                <v-col md="4" sm="12">
                    <v-text-field
                        v-model="search"
                        label="search"
                        clearable
                        placeholder="search by name"
                        @input="isTyping = true"
                        prepend-inner-icon="mdi-magnify"
                        outlined
                        @click:clear="search = ''"
                        dense
                    />
                </v-col>
                <v-col md="4" sm="6">
                    <v-menu
                        v-model="menu1"
                        :close-on-content-click="false"
                        max-width="290"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                :value="dateTimeFormat"
                                clearable
                                label="Search by Date"
                                readonly
                                dense
                                outlined
                                append-icon="mdi-calendar"
                                v-bind="attrs"
                                v-on="on"
                                @click:clear="date = null"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="date"
                            @change="menu1 = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>

            </v-row>
            <!------------ books here --------->
            <v-data-table
                class="px-3"
                :headers="headers"
                :items="books"
                disable-pagination
                :hide-default-footer="true"
            >
                <template v-slot:[`item.index`]="{ item }">
                    {{ books.indexOf(item) + 1 }}
                </template>

                <template v-slot:[`item.cover`]="{ item }">
                    <div style="border-radius: 5px; padding: 5px;">
                        <v-img height="auto" width="60" :src="item.cover_url" :lazy-src="item.cover_url" contain/>
                    </div>
                </template>

                <template v-slot:[`item.title`]="{ item }">
                    <truncate-word :text="item.title" :length="28"/>
                </template>
                <template v-slot:[`item.authors`]="{ item }">
                    <v-chip
                        v-for="(author, index) in item.authors"
                        class="mr-1"
                        color="primary"
                        small
                        :key="index"
                    >
                        <truncate-word :text="author.name" :length="10"/>
                    </v-chip>
                </template>
                <template v-slot:[`item.genre`]="{ item }">
                    <truncate-word :text="item.genre && item.genre.name" :length="15"/>
                </template>
                <template v-slot:[`item.created_at`]="{ item }">
                    <div>{{ item.created_at | dashboardDateFormat }}</div>
                </template>
                <template v-slot:[`item.action`]="{ item }">
                   <v-btn small color="primary pa-2" @click.prevent="checkoutBook(item)">checkout</v-btn>
                </template>
            </v-data-table>
            <div class="float-right mt-3" v-if="books.length">
                <v-pagination
                    v-model="page"
                    :length="bookCount"
                    total-visible="7"
                    @input="getBooks"
                ></v-pagination>
            </div>
        </div>

        <processing-overlay :show="loading"/>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapMutations} from "vuex";

    export default {
        name: "ReaderBookList",
        data: () => ({
            tabs: null,
            search: '',
            status: "checked_in",
            menu1: false,
            date: null,
            isTyping: false,
            page: 1,
            book_id: '',
            headers: [
                {text: 'S/No', value: 'index', align: 'start', sortable: false},
                {text: 'Cover', value: 'cover'},
                {text: 'Title', value: 'title'},
                {text: 'Authors', value: 'authors'},
                {text: 'Genre', value: 'genre'},
                {text: 'Date Added', value: 'created_at'},
                {text: 'Action', value: 'action'},
            ],
        }),
        methods: {
            ...mapActions('book', ['GET_BOOKS']),
            ...mapMutations('book', ['saveBook', 'setLoading']),
            defaultStates() {
                this.page = 1;
                this.date = null;
            },
            getParams() {
                return {
                    search: this.search !== null? this.search : "",
                    date: this.date !== null? this.date : "",
                    status: this.status !== null? this.status : "",
                    page: this.page || 1,
                    limit: 10,
                };
            },
            resetSearch() {
                this.GET_BOOKS({search: '', date: '', page: 1, status: ''});
            },
            getBooks() {
                this.GET_BOOKS(this.getParams())
            },
            checkoutBook(item) {
                const data = {
                    book_id: item.id,
                    user_id: this.user.id,
                };
                api.post(`/api/bookings`, data).then(res => {
                    Toast.fire({icon: 'success', title: 'booking checkout successful'});
                    this.getBooks();
                }).catch(err => {
                    Toast.fire({icon: 'error', title: err.messages});
                })
            }
        },
        created() {
            this.defaultStates();
            this.getBooks();
        },
        computed: {
            ...mapGetters('auth', ["user"]),
            ...mapGetters('book', ["books", "bookCount", "loading"]),
            dateTimeFormat() {
                return this.date ? dayjs(this.date).format('Do MMM [,] YYYY') : ''
            },
        },
        watch: {
            search: _.debounce(function() {
                this.isTyping = false;
                this.defaultStates();
            }, 1000),
            isTyping: function(value) {
                if (!value) {
                    this.getBooks();
                }
            },
            date(newVal, oldVal) {
                if(newVal !== null && newVal !== oldVal) {
                    this.getBooks();
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .dashed-divider {
        border-top: 6px dashed #E0E0E0
    }
    .plain-divider {
        border-top: 6px solid #E0E0E0
    }
    .col {
        margin-top: -25px!important;
    }
</style>
