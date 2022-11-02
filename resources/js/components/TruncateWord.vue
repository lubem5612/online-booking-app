<template>
    <div>
        <span v-if="text == null || text === ''">
            N/A
        </span>
        <span v-else-if="text.length - length <= 0">
            {{text}}
        </span>
        <v-tooltip top v-else>
            <template v-slot:activator="{ on, attrs }">
            <span
                class="hover"
                v-bind="attrs"
                v-on="on"
            >
                {{truncate()}}
            </span>
            </template>
            <span>{{text}}</span>
        </v-tooltip>
    </div>
</template>

<script>
    export default {
        props: {
            text: {
                required: true,
                type: String,
            },
            length: {
                required: true,
                type: Number,
            }
        },
        methods: {
            truncate() {
                return this.$props.text.substr(0, this.$props.length - 4)+'...';
            }
        }
    }
</script>

<style scoped lang="scss">
    .hover:hover{
        cursor: pointer;
    }
</style>
