<template>
    <v-row justify="end" class="dialogbox" v-if="isVisible">
        <v-dialog
            v-model="isVisible"
            @click:outside="$emit('onDismiss')"
            persistent
            max-width="450px"
            min-height="85vh"
            hide-overlay
            class="dialogbox"
        >
            <v-card
                class="py-1 px-0 overflow-y: scroll; overflow-x: hidden"
                style="height: 100%"
            >
                <v-card-title class="headline white--text pa-0">
                    <v-app-bar height="35" flat color="transparent">
                        <h5 class="font-weight-bold mt-3">
                            Book Checkout Details
                        </h5>
                        <v-spacer />
                        <v-btn icon @click="$emit('onDismiss')">
                            <v-icon small>mdi-close</v-icon>
                        </v-btn>
                    </v-app-bar>
                </v-card-title>
                <v-divider />
                <v-card-text>
                    <div v-if="!processing && book && book.booking">
                        <div class="d-flex justify-content-start" style="height: 80px">
                            <div class="align-self-center">
                                <v-img
                                    v-if="book.booking.user.photo_url"
                                    :src="book.booking.user.photo_url"
                                    :lazy-src="book.booking.user.photo_url"
                                    alt="user_pic"
                                    contain
                                    width="70px"
                                />
                                <v-icon v-else size="70">mdi-account</v-icon>
                            </div>
                            <div class="align-self-center ml-3">
                                <truncate-word class="font-weight-bold" :text="book.booking.user.name" :length="28"/>
                                <div>{{book.booking.user.email}}</div>
                                <div>{{book.booking.user.status}}</div>
                            </div>
                        </div>
                        <v-divider/>
                        <h6 class="font-weight-bold small">Booking Details</h6>
                        <v-row style="margin: 20px -10px -15px -10px;">
                            <v-col sm="4" class="text-left">
                                <h6 class="font-weight-bold small">Expected Check In Date</h6>
                                <div class="small">
                                    {{ book.booking.expected_checkin_date | dateWithDashes }}
                                </div>
                            </v-col>
                            <v-col sm="4" class="text-left">
                                <h6 class="font-weight-bold small">Actual Checkout Date</h6>
                                <div class="small">
                                    {{ book.booking.actual_checkin_date | dateWithDashes }}
                                </div>
                            </v-col>
                            <v-col sm="4" class="text-left">
                                <h6 class="font-weight-bold small">Checkout Date</h6>
                                <div class="small">
                                    {{ book.booking.created_at | dateWithDashes }}
                                </div>
                            </v-col>
                            <v-col sm="4" class="text-left">
                                <h6 class="font-weight-bold small">Days remaining</h6>
                                <div class="small">
                                    {{ daysRemaining || 'N/A' }}
                                </div>
                            </v-col>
                            <v-col sm="4" class="text-left">
                                <h6 class="font-weight-bold small">Days overdue</h6>
                                <div class="small">
                                    {{ daysOverdue || 'N/A' }}
                                </div>
                            </v-col>
                        </v-row>
                    </div>

                    <div v-else class="d-flex justify-center align-center" style="height: 250px">
                        <div class="align-self-center text-center">
                            <v-progress-circular size="50" indeterminate />
                        </div>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

    export default {
        name: "ViewCheckoutDetails",
        props: {
            bookId: {
                type: String,
                required: true,
            },
            isVisible: {
                type: Boolean,
                required: true,
            }
        },
        data: () => ({
            processing: false,
            book: {},
        }),
        methods: {
            getBook() {
                this.processing = true;
                api.get(`/api/books/${this.$props.bookId}`).then(res => {
                    this.book = res.data;
                }).catch(err => {
                }).finally(() => {
                    this.processing = false;
                });
            },
        },
        computed: {
            daysOverdue() {
                let current = dayjs();
                let expected = dayjs(this.book.booking.expected_checkin_date);

                let days = current.diff(expected, 'days');
                return days > 0? days : 'Not Due Yet';
            },
            daysRemaining() {
                let current = dayjs();
                let expected = dayjs(this.book.booking.expected_checkin_date);

                let days = expected.diff(current, 'days');
                return days > 0? days : 'Book Overdue';
            },
        },
        mounted() {
            this.getBook();
        },
    }
</script>

<style scoped lang="scss">
    .v-dialog__content {
        align-items: center !important;
        justify-content: flex-end !important;
        left: 0 !important;
        pointer-events: none;
        position: center !important;
        top: 10px !important;
        transition: 0.2s cubic-bezier(0.25, 0.8, 0.25, 1), z-index 1ms;
        width: 100%;
        z-index: 6;
        outline: none;
    }
    .link:hover {
        cursor: pointer;
    }
    .col {
        margin-top: -16px!important;
    }
</style>
