<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ["invoice"],
    data() {
        return {
            title: `${this.invoice.data.invoice_number}`,
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
    },
    methods: {
        printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        },
    },
    created() { },
});
</script>

<template>
    <Head :title="title" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/invoices" class="text-muted text-hover-primary">Invoices</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted text-hover-primary">Overview</span>
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link :href="`/invoice/${invoice.data.id}/edit`" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-pencil"></i>Edit Invoice</Link>
            </div>
        </template>
        <div class="app-content flex-column-fluid">
            <div class="card" id="printableArea">
                <div class="card-body p-lg-20">
                    <div class="d-flex flex-column">
                        <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                            <div class="mt-n1">
                                <div class="d-flex flex-stack pb-10">
                                    <!-- <Link href="/"> -->
                                    <img alt="Logo" src="/assets/images/logo-light.png" style="height: 100px" />
                                    <!-- </Link> -->
                                </div>
                                <div class="m-0">
                                    <div class="fw-bold fs-3 text-gray-800 mb-8">
                                        Invoice #{{ invoice.data.invoice_number }}
                                    </div>
                                    <div class="row g-5 mb-11">
                                        <div class="col-sm-6">
                                            <div class="fw-semibold fs-4 text-gray-600 mb-1">
                                                Issue Date:
                                            </div>
                                            <div class="fw-bold fs-4 text-gray-800">
                                                {{ invoice.data.issue_date }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="fw-semibold fs-4 text-gray-600 mb-1">
                                                Due Date:
                                            </div>
                                            <div class="fw-bold fs-4 text-gray-800 d-flex align-items-center flex-wrap">
                                                <span class="pe-2">{{ invoice.data.due_date }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-5 mb-12">
                                        <div class="col-sm-6">
                                            <div class="fw-semibold fs-4 text-gray-600 mb-1">
                                                From:
                                            </div>
                                            <div class="fw-bold fs-4 text-gray-800 mb-5">
                                                A.R Solution
                                            </div>
                                            <div class="fw-semibold fs-4 text-gray-600 mb-5">
                                                {{ invoice.data.from_address }}
                                            </div>
                                            <div class="fw-semibold fs-4 text-gray-600">
                                                GSTIN : 07CAYPR9267G1ZN
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="fw-semibold fs-4 text-gray-600 mb-1">
                                                To:
                                            </div>
                                            <div class="fw-bold fs-4 text-gray-800 mb-5">
                                                {{ invoice.data?.client?.name }}
                                            </div>
                                            <div class="fw-semibold text-gray-600 fs-4 mb-5 text-uppercase">
                                                {{ invoice.data.to_address }}
                                            </div>

                                            <div class="fw-semibold fs-4 text-gray-600"
                                                v-if="invoice.data?.client?.tax_number && invoice.data?.client?.tax_number != ''">
                                                GSTIN :
                                                {{ invoice.data?.client?.tax_number }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="table-responsive mb-0">
                                            <table class="table table-bordered bordered border">
                                                <thead class="text-center">
                                                    <tr class="bg-gray-300 fs-6 fw-bold text-gray-800">
                                                        <th class="col-3 text-start ps-5">
                                                            Project Name
                                                        </th>
                                                        <th class="col-3">
                                                            CPI
                                                        </th>
                                                        <th class="col-3">
                                                            Quantity
                                                        </th>
                                                        <th class="col-3 text-end pe-5">
                                                            Amount
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    <tr class="fw-bold text-gray-700 fs-5"
                                                        v-for="(item, index) in invoice.data.items" :key="index">
                                                        <td class="text-start ps-5">
                                                            {{ item.project_name }}
                                                        </td>
                                                        <td>
                                                            {{ invoice.data.currency.symbol }}
                                                            {{ item.cpi }}
                                                        </td>
                                                        <td>
                                                            {{ item.quantity }}
                                                        </td>
                                                        <td class="text-end text-dark fw-bolder pe-5">
                                                            {{ invoice.data.currency.symbol }}
                                                            {{ (item.cpi * item.quantity).toFixed(2) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"
                                                            class="border-0 text-right px-10 text-gray-950 fw-bold fs-6">
                                                            Sub Total
                                                        </td>
                                                        <td class="border-0 text-right pe-5 text-gray-950 fw-bold fs-6">
                                                            {{ invoice.data.currency.symbol }}
                                                            {{ invoice.data.total_amount }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="d-flex my-2">
                                                <div class="fw-semibold w-200px pe-10 text-gray-600 fs-4">
                                                    Account Holder
                                                </div>
                                                <div class="fw-bold fs-4 text-gray-800 flex-root text-start">
                                                    A.R Solution
                                                </div>
                                            </div>
                                            <div class="d-flex my-2">
                                                <div class="fw-semibold w-200px pe-10 text-gray-600 fs-4">
                                                    Account Number
                                                </div>
                                                <div class="fw-bold fs-4 flex-root text-gray-800 text-start">
                                                    097863300000931
                                                </div>
                                            </div>
                                            <div class="d-flex my-2">
                                                <div class="fw-semibold w-200px pe-10 text-gray-600 fs-4">
                                                    IFSC
                                                </div>
                                                <div class="fw-bold fs-4 flex-root text-gray-800">
                                                    YESB0000978
                                                </div>
                                            </div>
                                            <div class="d-flex my-2">
                                                <div class="fw-semibold w-200px pe-10 text-gray-600 fs-4">
                                                    Account Type
                                                </div>
                                                <div class="fw-bold fs-4 flex-root text-gray-800">
                                                    Current
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- check client Exist -->
                                        <div v-if="invoice.data.client">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="fw-semibold w-250px text-gray-700 fs-4">
                                                        Net payable amount :
                                                    </div>
                                                </div>
                                                <div class="col-6" v-if="invoice?.data?.client
                                                    ?.tax_number !=
                                                    ''
                                                    ">
                                                    <div class="fw-bold fs-4 text-gray-800 flex-root text-end">
                                                        {{
                                                            invoice?.data?.currency
                                                                .symbol
                                                        }}
                                                        {{
                                                            invoice?.data
                                                                ?.total_amount
                                                        }}
                                                        X
                                                        {{
                                                            invoice?.data
                                                                ?.conversion_rate
                                                        }}
                                                        INR = INR
                                                        {{
                                                            Number(
                                                                invoice?.data
                                                                    ?.total_amount *
                                                                invoice?.data
                                                                    ?.conversion_rate
                                                            ) +
                                                            Number(
                                                                parseFloat(
                                                                    ((invoice?.data
                                                                        ?.total_amount *
                                                                        invoice
                                                                            ?.data
                                                                            ?.conversion_rate) /
                                                                        118) *
                                                                    invoice
                                                                        ?.data
                                                                        ?.tax_rate
                                                                ).toFixed(2)
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                                <div class="col-6" v-else>
                                                    <div class="fw-bold fs-4 text-gray-800 flex-root text-end">
                                                        {{
                                                            invoice?.data?.currency
                                                                .symbol
                                                        }}
                                                        {{
                                                            invoice?.data
                                                                ?.total_amount
                                                        }}
                                                        X
                                                        {{
                                                            invoice?.data
                                                                ?.conversion_rate
                                                        }}
                                                        INR = INR
                                                        {{ (invoice?.data
                                                            ?.total_amount *
                                                            invoice?.data
                                                                ?.conversion_rate
                                                        ).toFixed(2)
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="invoice?.data?.client
                                                    ?.tax_number != ''
                                                ">
                                                <div class="d-flex my-2">
                                                    <div class="fw-semibold w-250px text-gray-700 fs-4">
                                                        Invoice value before GST
                                                        :
                                                    </div>
                                                    <div class="fw-bold fs-4 text-gray-800 flex-root text-end">
                                                        INR
                                                        {{ (invoice?.data?.total_amount
                                                            * invoice?.data?.conversion_rate).toFixed(2) }}
                                                    </div>
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="fw-semibold w-250px text-gray-700 fs-4">
                                                        GST value at
                                                        {{ invoice?.data?.tax_rate }}% :
                                                    </div>
                                                    <div class="fw-bold fs-4 text-gray-800 flex-root text-end">
                                                        INR
                                                        {{ parseFloat(((invoice?.data?.total_amount
                                                            * invoice?.data?.conversion_rate).toFixed(2) / 118)
                                                            * invoice?.data?.tax_rate).toFixed(2) }}
                                                    </div>
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="fw-semibold w-250px text-gray-700 fs-4">
                                                        Invoice value with GST :
                                                    </div>
                                                    <div class="fw-bold fs-4 text-gray-800 flex-root text-end">
                                                        INR
                                                        {{ Number(invoice?.data?.total_amount
                                                            * invoice?.data?.conversion_rate) +
                                                            Number(parseFloat(((invoice?.data?.total_amount *
                                                                invoice?.data?.conversion_rate) / 118)
                                                                * invoice?.data?.tax_rate).toFixed(2))
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="my-5 text-dark fw-bolder border-top fs-2 text-start">
                                            <div class="mt-5">
                                                Notes / Terms
                                                <div class="text-gray-800 fw-semibold fs-4 text-start">
                                                    1
                                                    {{ invoice?.data?.currency.name }}
                                                    to
                                                    {{ invoice?.data?.conversion_rate }}
                                                    INR ({{ invoice?.data?.date }})
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-10">
                            <div class="mw-400px">
                                <div class="d-flex flex-stack mb-3">
                                    <div class="text-end fw-bold fs-4 text-gray-800">
                                        Contact Details
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-semibold pe-10 text-gray-600 fs-4">
                                        Contact Number :
                                    </div>
                                    <div class="text-end fw-bold fs-4 text-gray-800">
                                        +91 75038-76258
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-3">
                                    <div class="fw-semibold pe-10 text-gray-600 fs-4">
                                        Contact Email :
                                    </div>
                                    <div class="text-end fw-bold fs-4 text-gray-800">
                                        rahul.kumar@alrestion.com
                                    </div>
                                </div>
                            </div>
                            <div class="mw-400px">
                                <div class="d-flex flex-stack">
                                    <!-- <Link href="/"> -->
                                    <img alt="Logo" src="/assets/images/logo-light.png" style="height: 100px" />
                                    <!-- </Link> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-5">
            <button class="btn btn-success" @click="printDiv('printableArea')">
                <i class="bi bi-filetype-pdf"></i>Save PDF
            </button>
        </div>
    </AppLayout>
</template>
