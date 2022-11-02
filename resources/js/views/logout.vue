<template>

</template>

<script>
    import {mapActions} from "vuex";

    export default {
        name: "Logout",
        methods: {
            ...mapActions('auth', ["LOGOUT_USER"]),
        },
        mounted() {
            this.LOGOUT_USER({});
        }
    }
</script>
