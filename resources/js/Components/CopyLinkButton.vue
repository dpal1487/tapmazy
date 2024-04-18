<script>
import { defineComponent } from 'vue';
import { copyText } from "vue3-clipboard";
export default defineComponent({
    props: ["link", "text", "tooltip"],

    data() {
        return {
            copied: false,
        }
    },
    methods: {
        doCopy(link) {
            copyText(link, undefined, (error, event) => {
                if (error) {
                    return;
                } else {
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 1000)
                }
            });
        }
    }
})

</script>

<template>
    <button style="min-width: 120px;"
        :class="`btn btn-outline btn-sm clipboard ${copied ? 'bg-light-success btn-outline-success' : ' btn-outline-dark'}`"
        @click="copied ? () => null : doCopy(link)" :title="tooltip">
        <span v-if="copied">
            Copied !!
        </span>
        <span v-else>
            <i class="bi bi-link"></i>
            {{ text || "Copy link" }}
        </span>
    </button>
</template>