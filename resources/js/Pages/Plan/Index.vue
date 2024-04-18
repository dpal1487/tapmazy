
<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import utils from "../../utils";
import AddNewRecord from "../../Components/AddNewRecord.vue";
import { toast } from "vue3-toastify";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: {
        plans: {
            type: Object,
            default: null,
        },
        subscription: {
            type: Object,
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
        AddNewRecord
    },
    methods: {
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('plan.destroy', id), this.plans.data, index);
            this.isLoading = false;
        },
        checkPlanSubscriptions(id) {
            if (!this.subscription.includes(id)) {
                Inertia.get(`/plan/${id}`)
            } else {
                toast.error('Plan already exists in subscriptions')
            }
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
        <template #toolbar>
            <AddNewRecord link="/plan/create" name="Plan" />
        </template>
        <div class="card">
            <div class="card-body pt-5">
                <div class="d-flex flex-column">
                    <div class="mb-13 text-center">
                        <h1 class="fs-2hx fw-bold mb-5">Choose Your Plan</h1>
                        <div class="text-gray-400 fw-semibold fs-5">If you need more info about our pricing, please check
                            <a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
                        </div>
                    </div>
                    <div class="row" v-if="subscription">
                        <div class="col-xl-4 col-lg-6 col-12" v-for="(plan, index) in plans.data" :key="index">
                            <div class="d-flex align-items-center position-relative mb-5">
                                <div @click="selectedPlan = `${plan.name}`"
                                    class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                    <div class="mb-7 text-center">
                                        <h1 class="text-dark mb-5 fw-bolder text-capitalize">{{ plan.name }}</h1>
                                        <div class="fw-semibold fs-5 mb-5">{{ plan.sort_description }}

                                        </div>
                                        <div class="text-center">
                                            <span class="mb-2 text-primary fs-3 fw-bold">{{
                                                plan?.currency?.symbols }}</span>
                                            <span class="fs-3x fw-bold text-primary">{{ plan.price }}</span>
                                            <span class="fs-2 fw-semibold text-gray-600">/
                                                <span data-kt-element="period">{{ plan?.plan_type }}</span></span>
                                        </div>
                                    </div>
                                    <div class="w-100 mb-10">
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{
                                                plan?.allowed_users }}
                                                Active
                                                Users</span>
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
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{
                                                plan?.allowed_projects }}
                                                Project
                                                Integrations</span>
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
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{
                                                plan?.allowed_invoices }}
                                                Invoice
                                                Integrations</span>
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
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{
                                                plan?.allowed_clients }}
                                                Client
                                                Integrations</span>
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
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{
                                                plan?.allowed_suppliers }}
                                                Supplier
                                                Integrations</span>
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
                                        <div class="d-flex align-items-center mb-5"
                                            v-for="(description) in JSON.parse(plan.description)">
                                            <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">{{
                                                description.description }}</span>
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
                                        <div class="d-flex align-items-center mb-5">
                                            <span class="fw-semibold fs-6 text-gray-400 flex-grow-1">Finance Module</span>
                                            <span class="svg-icon svg-icon-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                                        fill="currentColor" />
                                                    <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                                        transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                                    <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                                        transform="rotate(45 8.41422 7)" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <button @click="checkPlanSubscriptions(plan.id)"
                                        class="btn btn-sm btn-primary">Select</button>
                                </div>

                                <div class="flex-1 text-end position-absolute" style="top:5px; right: 5px;">
                                    <button class="btn btn-icon btn-light btn-sm btn-outline btn-circle"
                                        :id="`dropdown-${plan.id}`" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="text-left dropdown-menu-end dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4 my-1"
                                        :aria-labelled:by="`dropdown-${plan.id}`">
                                        <div class="menu-item px-3">
                                            <Link :href="`/plan/${plan.id}/edit`" class="menu-link">
                                            <span><i class="bi bi-pencil me-2"></i>Edit</span>
                                            </Link>
                                        </div>
                                        <div class="menu-item px-3">
                                            <span @click="confirmDelete(plan.id, index)" class="menu-link"><i
                                                    class="bi bi-trash3 me-2"></i>Delete</span>
                                        </div>
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
