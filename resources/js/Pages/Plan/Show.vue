<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Customer from "./Components/Customer.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import PlanDetails from "./Components/PlanDetails.vue";
import axios from "axios";
export default defineComponent({
    props: ["plan", "user"],
    data() {
        return {
            title: 'Plan',
        };
    },
    components: {
        AppLayout,
        Customer,
        Link,
        Head,
        PlanDetails,
    },
    mounted() {
    },
    methods: {
        submit() {
            axios.post(route('subscription.checkout'), { plan_id: this.plan.data.id })
                .then((res) => {
                    if (res.data) {
                        console.log(res.data)
                        window.location.href = res.data.url
                    }
                })
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
                <Link href="/plans" class="text-muted text-hover-primary">Plans</Link>
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row gap-5">
            <div class="flex-lg-row-fluid">
                <Customer :user="user.data" />
                <PlanDetails :plan="plan?.data" />
            </div>
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-300px mb-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Summary</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <h5 class="">Customer details</h5>
                            <div class="d-flex align-items-center mb-1">
                                <span class="fw-semibold text-gray-600">Name : </span>
                                <Link :href="`account${user.data.id}`"
                                    class="fw-bold text-gray-800 text-hover-primary me-2">
                                {{ user.data?.first_name + ' ' + user.data?.last_name }}</Link>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="fw-semibold text-gray-600">Email : </span>
                                <span class="fw-bold text-gray-700">{{ user.data?.email }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-7"></div>
                        <div class="mb-7">
                            <h5 class="mb-3">Product details</h5>
                            <span class="fw-semibold text-gray-600">Plan Name : </span>
                            <span class="badge badge-light-info me-2">{{ plan?.data?.plan_name }}</span>
                            <div class="my-2">
                                <span class="fw-semibold text-gray-600">Plan Price : </span>
                                <span class="fw-semibold text-gray-600">{{ plan?.data?.currency?.symbols + '' +
                                    plan.data?.plan_price }} /
                                    {{ plan.data?.plan_type }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-10"></div>
                        <div class="text-center">
                            <div class="mb-0">
                                <form @submit.prevent="submit()">
                                    <button class="btn btn-light-primary">Pay now!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
