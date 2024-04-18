<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import NoRecordMessage from "../../Components/NoRecordMessage.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import axios from "axios";
import { toast } from "vue3-toastify";
export default defineComponent({
    props: ["subscriptions"],
    data() {
        return {
            form: {},
            selectAll: false,
            isLoading: false,
            isFullPage: true,
            title: "Subscriptions",
            tbody: [
                "User Name",
                "Subscription ID",
                "Plan Name",
                "Price",
                "Plan Start Date",
                "Expire On",
                "Status",
                "Action",
            ],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Pagination,
        Multiselect,
        NoRecordMessage,
        Loading
    },
    methods: {
        search() {
            Inertia.get("/subscriptions", this.form);
        },
        subscriptionUpdate(id) {
            axios.get(`/subscription/plan-update/${id}`)
                .then((res) => {
                    console.log("Subscription sada", res)
                }).catch((error) => {
                    console.error("Error updating subscription:", error);
                    toast.error('Failed to update subscription. Please try again.'); // Display error toast
                })
                .finally(() => {
                    // Any cleanup code you want to execute
                });
        },
        cancelSubscription(id) {
            this.isLoading = true;
            axios.post("/subscription/cancel", { subscription_id: id })
                .then((res) => {
                    if (res.data.status) {
                        console.log("Subscription success", res)
                        toast.success(res.data.message)
                    }
                    else {
                        toast.error(res.data.message)
                    }
                }).catch((error) => {
                    console.error("Error updating subscription:", error);
                    toast.error('Failed to update subscription. Please try again.'); // Display error toast
                })
                .finally(() => {
                    this.isLoading = false;
                    // Any cleanup code you want to execute
                });
        }
    },
});
</script>
<template>
    <app-layout :title="title">
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/subscriptions" class="text-muted text-hover-primary">{{ title }}</Link>
            </li>
        </template>

        <Head :title="title" />
        <div class="card">
            <div>
                <form class="card-header justify-content-start p-5 gap-3" @submit.prevent="search()">
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
                        <input type="text" v-model="form.q" class="form-control form-control-solid w-250px ps-14"
                            placeholder="Search " />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-gray-700 fw-bold fs-7 text-uppercase">
                                <th v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500" v-if="subscriptions.data.length > 0">
                            <tr v-for="(subscription, index) in subscriptions.data" :key="index">
                                <td class="text-gray-700 text-hover-primary fs-5 fw-bold mb-1 text-capitalize">
                                    <Link :href="`subscription/${subscription?.id}`">
                                    {{ subscription.user_name }}
                                    </Link>
                                </td>
                                <td>{{ subscription.stripe_subscription_id }}</td>
                                <td class="text-capitalize">{{ subscription?.plan?.plan_name }}</td>
                                <td class="text-capitalize">{{ subscription?.plan?.plan_price }}</td>
                                <td class="text-capitalize">{{ subscription?.current_plan_start }}</td>
                                <td class="text-capitalize">{{ subscription?.current_plan_end }}</td>
                                <td>
                                    <span
                                        :class="`text-capitalize badge badge-light-${subscription?.status == 'active' ? 'success' : 'danger'} `">
                                        {{ subscription?.status }}</span>
                                </td>

                                <td class="min-w-150px">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                            :id="`dropdown-${subscription.id}`" data-bs-toggle="dropdown"
                                            aria-expanded="false">Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu text-small menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            :aria-labelled:by="`dropdown-${subscription.id}`">
                                            <li class="menu-item px-3">
                                                <Link :href="`subscription/plan-update/${subscription.id}`"
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center">
                                                <i class="bi bi-pencil"></i> Update
                                                </Link>
                                            </li>
                                            <li class="menu-item px-3">
                                                <button @click="cancelSubscription(subscription.id, index)"
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center">
                                                    <i class="bi bi-x-lg"></i> Cancel
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="8" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" v-if="subscriptions.data.length > 0">
                <Pagination :links="subscriptions" />
            </div>
        </div>
    </app-layout>
</template>
