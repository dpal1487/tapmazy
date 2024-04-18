<script>
import { defineComponent } from "vue";
import axios from "axios";
export default defineComponent({
    props: ["conversation", "isTyping", "user"],
    emits: ['typingMessage', 'messageSent'],
    data() {
        return {
            form: this.$inertia.form({
                conversation_id: this.conversation,
                message: " ",
                id: this.user?.id,
            }),
        };
    },
    components: {},
    methods: {
        submit() {
            this.form
            axios.post(route("message.store"), this.form)
                .then(response => {
                    this.$emit("messageSent")
                    if (response.data) {
                        this.form = {
                            conversation_id: this.conversation,
                            message: " ",
                            id: this.user?.id,
                        }
                    }
                }
                );
        }
    },
    mounted() {

    },
});
</script>
<template>
    <div class="chat-input-section mb-0">
        <form class="" @submit.prevent="submit">
            <div class="g-0 row">
                <div class="col">
                    <div>
                        <input placeholder="Enter Message..." type="text"
                            class="form-control form-control-lg bg-light border-light form-control" v-model="form.message"
                            @blur="$emit('typingMessage', false)" @keydown="$emit('typingMessage', true)" />
                    </div>
                </div>
                <div class="col-auto">
                    <div class="chat-input-links ms-md-2">
                        <ul class="list-inline mb-0 ms-0">
                            <li class="list-inline-item">
                                <button type="submit"
                                    class="font-size-16 btn-lg chat-send waves-effect waves-light btn btn-primary">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                                        height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
