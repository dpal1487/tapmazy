<script>
import { defineComponent } from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import Header from './Components/Header.vue';
import { Link } from '@inertiajs/inertia-vue3';
import Pagination from '../../Jetstream/Pagination.vue';
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: ['employees', 'tickets'],
    data() {
        return {
            form: {},
            title: 'Ticket',
        }
    },
    components: {
        AppLayout,
        Header,
        Link,
        Pagination
    },
    methods: {
        search() {
            Inertia.get(
                "/support/tickets", this.form,
            );
        },
    }
})
</script>


<template>
    <AppLayout :title="title">

        <Header :employees="employees.data" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/support" class="text-muted text-hover-primary">Support</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/support/tickets" class="text-muted text-hover-primary">Tickets</Link>
            </li>

        </template>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-xl-row p-0">
                    <div class="flex-lg-row-fluid me-xl-15 mb-20 mb-xl-0">
                        <div class="">
                            <form @submit.prevent="search" class="card-header justify-content-start p-5 gap-2 gap-md-5">
                                <div class="d-flex align-items-center position-relative">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <input type="text" v-model="form.q"
                                        class="form-control form-control-solid w-250px ps-14" placeholder="Search Client" />
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                            <h1 class="text-dark my-5">Tickets</h1>
                            <div class="mb-10">
                                <div class="d-flex mb-10" v-for="(ticket, index) in tickets.data" :key="index">
                                    <span class="svg-icon svg-icon-2x me-5 ms-n1 mt-2 svg-icon-success">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z"
                                                fill="currentColor" />
                                            <path
                                                d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z"
                                                fill="currentColor" />
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <Link :href="`/support/${ticket.id}/view-tickets`"
                                                class="text-dark text-hover-primary fs-4 me-3 fw-semibold">{{ ticket.subject
                                                }}</Link>
                                            <span class="badge badge-light my-1">{{ ticket.project }}</span>
                                        </div>
                                        <span class="text-muted fw-semibold fs-6">{{ ticket.description }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer" v-if="tickets.data.length > 0">
                                <Pagination :links="tickets" />
                            </div>
                        </div>
                    </div>
                    <div class="flex-column flex-lg-row-auto w-100 mw-lg-300px mw-xxl-350px">
                        <div class="card-rounded bg-primary bg-opacity-5 p-10 mb-15">
                            <h2 class="text-dark fw-bold mb-11">More Channels</h2>
                            <div class="d-flex align-items-center mb-10">
                                <i class="bi bi-file-earmark-text text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bold">Project Briefing</h5>
                                    <div class="fw-semibold">
                                        <span class="text-muted">Check out our</span>
                                        <a href="#" class="link-primary">Support Policy</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-10">
                                <i class="bi bi-chat-square-text-fill text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bold">More to discuss?</h5>
                                    <div class="fw-semibold">
                                        <span class="text-muted">Email us to</span>
                                        <a href="#" class="link-primary">support@keenthemes.com</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-10">
                                <i class="bi bi-twitter text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bold">Latest News</h5>
                                    <div class="fw-semibold">
                                        <span class="text-muted">Follow us at</span>
                                        <a href="#" class="link-primary">KeenThemes Twitter</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-github text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bold">Github Access</h5>
                                    <div class="fw-semibold">
                                        <span class="text-muted">Our github repo</span>
                                        <a href="#" class="link-primary">KeenThemes Github</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
