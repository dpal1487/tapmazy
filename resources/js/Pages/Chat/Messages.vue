<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import ConversationTile from "./Components/ConversationTile.vue";
import ConversationSearch from "./Components/ConversationSearch.vue";
import OutMessage from "./Components/OutMessage.vue";
import InMessage from "./Components/InMessage.vue";
import TypingMessage from "./Components/TypingMessage.vue";
import ChatInputSection from './Components/ChatInputSection.vue'
import axios from "axios";

export default defineComponent({
    props: ["users", "messages", "user", "conversation_id"],

    data() {
        return {
            title: "Messaging",
            form: {},
            q: "",
            isTyping: false,
            isLoading: false,
            chats: [],
            pathname: "",
            isNewMessageSent: false,
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        ConversationTile,
        InMessage,
        OutMessage,
        ChatInputSection,
        TypingMessage,
        ConversationSearch
    },
    methods: {
        async search() {
            try {
                const response = await axios.get('/messaging/search', { params: { q: this.q } });
                this.chats = response.data.data;
            } catch (error) {
                // console.error("An error occurred while fetching search results:");
            }
        },
        scrollToElement() {
            document.getElementById('chat_container').scrollTop = document?.getElementById('chat_container')?.scrollHeight
        },
        typingMessage(value) {
            this.isTyping = value;
        },
        messageSent() {
            this.isNewMessageSent = true;
        }
    },
    mounted() {
        window.Echo.channel("conversation").listen("SendMessageEvent",
            (event) => {
                this.messages?.data.push(event?.data);
                this.scrollToElement();
            }
        );
        
        // console.log("sadasdd", this.conversation_id);

        // window.Echo.private(`conversation.${this.conversation_id}`).listen("SendMessageEvent",
        //     (event) => {
        //         console.log("sadasdd", event);
        //         this.messages?.data.push(event?.data);
        //         this.scrollToElement();
        //     }
        // );

        this.chats = this.users.data;
        this.pathname = window.location.pathname;
    },
    watch: {
        users: {
            handler() {
                chats = this.users.data;
            }
        },
        q: {
            handler() {
                if (this.q.length > 0) {
                    this.chats = this.users.data;
                }
            }
        }
    }
});
</script>
<template>
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted">{{ title }}</span>
            </li>
        </template>

        <Head :title="title" />
        <div class="d-flex flex-column flex-lg-row ">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-300px mb-10 mb-lg-0">
                <div class="card">
                    <div class="card-header pt-7">
                        <form class="card-header justify-content-start py-4 px-0 w-100 gap-md-5">
                            <div class="d-flex align-items-center position-relative">
                                <span class="svg-icon svg-icon-1 position-absolute ms-1"><svg width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                            transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor"></path>
                                    </svg></span>
                                <input type="text" class="form-control form-control-solid w-250px ps-10"
                                    placeholder="Search by username or email..." v-model="q" @input="search" />
                            </div>
                        </form>
                    </div>

                    <div class="card-body pt-5">
                        <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" style="min-height: 480px; max-height: 480px">
                            <div v-for="(user, index) in chats" :key="index">
                                <div v-if="user.conversation_id">
                                    <conversation-tile :user="user" />
                                </div>
                                <div v-else>
                                    <ConversationSearch :user="user" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                <div class="card" v-if="pathname == '/messaging'">
                    <div class="card-body">
                        <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto">
                            <img src="/assets/images/chat_image.jpg" class="w-100" style="height: 565px;">
                        </div>
                    </div>
                </div>
                <div class="card" v-else>
                    <div class="card-header px-5">
                        <div class="card-title">
                            <div class="symbol symbol-45px symbol-circle me-5"><span
                                    class="symbol-label  bg-light-danger text-danger fs-6 fw-bolder ">
                                    {{ user?.data?.first_name.substring(0, 1).toUpperCase() }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-center flex-column">
                                <span
                                    class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1 text-capitalize">{{
                                        user?.data?.first_name }}
                                    {{ user?.data?.last_name }}</span>
                                <div class="mb-0 lh-1">
                                    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                    <span class="fs-7 fw-semibold text-muted">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" id="chat_container"
                            style="min-height: 390px; max-height: 400px">
                            <div v-for="(message, index) in messages?.data" :key="index">
                                <out-message v-if="$page.props.user.data.id !=
                                    message.sender_id
                                    " :message="message" />
                                <in-message v-else :message="message" />
                            </div>
                            <div v-if="isTyping">
                                <typing-message :user="user.data" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <chat-input-section :isTyping="isTyping" @typingMessage="typingMessage" @messageSent="messageSent"
                            :conversation="conversation_id" :user="user?.data" />
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
