<script>
import { defineComponent } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import CustomerSummery from "./Components/CustomerSummary.vue";
import RecentEvent from "./Components/RecentEvent.vue";
import ProductDetails from './Components/ProductDetails.vue';
import Invoices from './Components/Invoices.vue';
export default defineComponent({
    props: {
        subscription: {
            type: Object,
            default: null
        },
        events: {
            type: Array,
            default: null
        },
        customer: {
            type: Object,
            default: null
        },
        product: {
            type: Object,
            default: null
        },
        card_details: {
            type: Object,
        },
        invoice: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            title: "Subscription Show",
        }
    },
    components: {
        AppLayout,
        Head,
        Link,
        CustomerSummery,
        RecentEvent,
        ProductDetails,
        Invoices
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
                <Link href="/subscriptions" class="text-muted text-hover-primary">{{ title }}</Link>
            </li>
        </template>

        <Head :title="title" />
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid me-lg-5 order-2 order-lg-1 mb-10 mb-lg-0">
                <ProductDetails :subscription="subscription" :customer="customer" :product="product" />
                <RecentEvent :events="events" />
                <Invoices :invoice="invoice" />
            </div>
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">

                <CustomerSummery :subscription="subscription.data" :customer="customer" :product="product"
                    :card_details="card_details" />
            </div>
        </div>
    </app-layout>
</template>
