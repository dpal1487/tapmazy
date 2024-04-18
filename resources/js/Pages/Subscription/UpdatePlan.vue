<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import utils from "../../utils";

export default defineComponent({
    props: {
        plans: {
            type: Object,
            default: null,
        },
        subscription: {
            type: Object,
            default: null,
        },
        activePlanId: {
            type: String,
            default: null,
        }
    },

    data() {
        return {
            selectedPlan: '',
            title: 'Plans',
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
    },
    methods: {
        async upgradeSubscriptions(id) {
            this.isLoading = true;
            await utils.upgradeIndexDialog(route('subscription.update'), { id: id, subscription_id: this.subscription?.id });
            this.isLoading = false;
        },
    },
});
</script>
<template>
    <app-layout :title="title">

        <Head :title="title" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/plan" class="text-muted text-hover-primary">Plan</Link>
            </li>
        </template>
        <div class="card">
            <div class="card-body pt-5">
                <div class="d-flex flex-column">
                    <div class="mb-5 text-center">
                        <h1 class="fs-2hx fw-bold text-gray-700">Upgrade and Downgrade Plan</h1>
                        <div class="text-gray-400 fw-semibold fs-5">If you need more info about our pricing, please
                            check
                            <Link href="#" class="text-gray-700 text-hover-primary fw-bold">Pricing Guidelines</Link>.
                        </div>
                    </div>
                    <div class="row" v-if="subscription">
                        <div class="col-xl-4 col-lg-6 col-12" v-for="(plan, index) in plans.data" :key="index">
                            <div class="d-flex align-items-center position-relative mb-5">
                                <div @click="selectedPlan = `${plan.plan_name}`"
                                    class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                    <div class="mb-7 text-center">
                                        <h1 class="text-dark mb-5 fw-bolder text-capitalize">{{ plan.plan_name }}</h1>
                                        <div class="fw-semibold fs-5 mb-5">{{ plan.sort_description }}

                                        </div>
                                        <div class="text-center">
                                            <span class="mb-2 text-primary fs-3 fw-bold">{{
                                                plan?.currency?.symbols }}</span>
                                            <span class="fs-3x fw-bold text-primary">{{ plan.plan_price }}</span>
                                            <span class="fs-2 fw-semibold text-gray-600">/
                                                <span data-kt-element="period">{{ plan?.plan_type }}</span></span>
                                        </div>
                                    </div>
                                    <div class="w-100 mb-10" v-if="plan.description">
                                        <div class="d-flex align-items-center  mb-5"
                                            v-for="(description) in JSON.parse(plan?.description)">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                {{ description.description }}</span>
                                            <span class="svg-icon svg-icon-1 svg-icon-success">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="activePlanId === plan.id">
                                        <span class="grey">
                                            <Link href="#" class="btn btn-sm btn-primary">Active Plan</Link>
                                        </span>
                                    </div>
                                    <div v-else>
                                        <button @click="upgradeSubscriptions(plan.id)"
                                            class="btn btn-sm btn-primary">Select</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
