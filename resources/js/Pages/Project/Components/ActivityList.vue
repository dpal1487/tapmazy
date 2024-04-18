<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';

export default defineComponent({
    props: ["activities", "show"],
    components: {
        Link,
        Loading
    },
    data() {
        return {
            isLoading: false,
            isFullPage: true,
            allPosts: this.activities.data,
        }
    },
    methods: {
        loadMorePosts() {
            if (this.activities.links.next === null) {
                return
            }
            this.isLoading = true;
            Inertia.get(this.activities.links.next, {}, {
                preserveState: true,
                preserveScroll: true,
                only: ['activities'],
                onSuccess: () => {
                    this.allPosts = [...this.allPosts, ...this.activities.data],
                        this.isLoading = false;

                }
            })
        },
        loadFirstPage() {
            this.isLoading = true;
            Inertia.get(this.activities.links.first, {}, {
                preserveState: true,
                preserveScroll: true,
                only: ['activities'],
                onSuccess: () => {
                    this.allPosts = [...this.allPosts, ...this.activities.data],
                        this.isLoading = false;

                }
            })
        }
    }
});
</script>
<template>
    <div class="tab-content">
        <div class="show card-body p-0 tab-pane fade  active">
            <loading :active="isLoading" :can-cancel="true" :is-full-page="isFullPage"></loading>

            <div class="timeline">
                <div class="timeline-item" v-for="activity in activities?.data">
                    <div class="timeline-line w-40px"></div>
                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                        <div class="symbol-label bg-light">
                            <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3"
                                        d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z"
                                        fill="currentColor" />
                                    <path
                                        d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="timeline-content mb-10 mt-n2">
                        <div class="overflow-auto pe-3">
                            <div class="fs-5 fw-semibold mb-2">{{ activity.text }}</div>
                            <div class="d-flex align-items-center mt-1 fs-6">
                                <div class="text-muted me-2 fs-7">{{ activity?.date }} Sent at {{ activity?.time }} by</div>
                                <!-- <div v-if="activity.profile" class="symbol symbol-circle symbol-25px"
                                    data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top"
                                    :title="activity.user">
                                    <img :src="activity.profile" alt="img" />
                                </div>
                                <div v-else class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
                                    data-bs-boundary="window" data-bs-placement="top" title="User Profile">
                                    <img src="/assets/media/avatars/300-1.jpg" alt="img" />
                                </div> -->
                                <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
                                    data-bs-boundary="window" data-bs-placement="top" title="User Profile">
                                    <img src="/assets/media/avatars/300-1.jpg" alt="img" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">

                <button @click="loadMorePosts" v-if="activities.links.next" class="btn btn-primary ">Load More</button>
                <button @click="loadFirstPage" v-if="activities.links.next == null" class="btn btn-primary ">First
                    page</button>
            </div>
        </div>
    </div>
</template>
