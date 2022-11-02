<template>
    <div class="text-center">
        <v-overlay :value="show">
            <v-progress-circular indeterminate size="100" color="primary" width="10"></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
    export default {
        props: {
            show : {
                required: true,
                type: Boolean,
            }
        },
        watch: {
            show : function () {
                this.$emit('state', this.show)
            }
        }
    }
</script>
